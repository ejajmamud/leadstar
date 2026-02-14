-- 20260213_licensing.sql
-- LeadStar licensing schema for Supabase (PostgreSQL)

begin;

create extension if not exists pgcrypto;

-- =========================
-- 1) Core tables
-- =========================
create table if not exists public.profiles (
  id uuid primary key references auth.users(id) on delete cascade,
  plan text not null default 'free' check (plan in ('free', 'pro')),
  pro_expires_at timestamptz,
  last_verified_at timestamptz,
  created_at timestamptz not null default now(),
  updated_at timestamptz not null default now()
);

create table if not exists public.usage_daily (
  user_id uuid not null references public.profiles(id) on delete cascade,
  feature text not null,
  usage_day date not null default (timezone('utc', now()))::date,
  used integer not null default 0 check (used >= 0),
  created_at timestamptz not null default now(),
  updated_at timestamptz not null default now(),
  primary key (user_id, feature, usage_day)
);

create index if not exists idx_profiles_plan on public.profiles(plan);
create index if not exists idx_usage_daily_day on public.usage_daily(usage_day);

-- =========================
-- 2) Timestamps
-- =========================
create or replace function public.tg_set_updated_at()
returns trigger
language plpgsql
as $$
begin
  new.updated_at = now();
  return new;
end;
$$;

drop trigger if exists trg_profiles_updated_at on public.profiles;
create trigger trg_profiles_updated_at
before update on public.profiles
for each row execute function public.tg_set_updated_at();

drop trigger if exists trg_usage_daily_updated_at on public.usage_daily;
create trigger trg_usage_daily_updated_at
before update on public.usage_daily
for each row execute function public.tg_set_updated_at();

-- =========================
-- 3) Auto-create profile row
-- =========================
create or replace function public.handle_new_user()
returns trigger
language plpgsql
security definer
set search_path = public
as $$
begin
  insert into public.profiles (id)
  values (new.id)
  on conflict (id) do nothing;
  return new;
end;
$$;

drop trigger if exists on_auth_user_created on auth.users;
create trigger on_auth_user_created
after insert on auth.users
for each row execute function public.handle_new_user();

-- Backfill profile rows for existing users
insert into public.profiles (id)
select u.id
from auth.users u
on conflict (id) do nothing;

-- =========================
-- 4) Plan protection
-- =========================
create or replace function public.tg_block_client_plan_change()
returns trigger
language plpgsql
security definer
set search_path = public
as $$
declare
  jwt_role text;
begin
  jwt_role := coalesce(current_setting('request.jwt.claim.role', true), '');

  -- only service_role may change plan/pro_expires_at
  if jwt_role <> 'service_role' then
    if new.plan is distinct from old.plan
       or new.pro_expires_at is distinct from old.pro_expires_at then
      raise exception 'Not allowed to change plan fields';
    end if;
  end if;

  return new;
end;
$$;

drop trigger if exists trg_block_client_plan_change on public.profiles;
create trigger trg_block_client_plan_change
before update on public.profiles
for each row execute function public.tg_block_client_plan_change();

-- =========================
-- 5) Helper functions
-- =========================
create or replace function public.is_pro(p_user_id uuid)
returns boolean
language sql
stable
as $$
  select exists (
    select 1
    from public.profiles p
    where p.id = p_user_id
      and p.plan = 'pro'
      and (p.pro_expires_at is null or p.pro_expires_at > now())
  );
$$;

create or replace function public.feature_limit(p_feature text, p_is_pro boolean)
returns integer
language plpgsql
immutable
as $$
begin
  if p_is_pro then
    return null; -- unlimited
  end if;

  case p_feature
    when 'maps_leads' then return 20;
    when 'list_extract_rows' then return 500;
    when 'page_to_pdf' then return 5;
    when 'export_csv' then return null; -- allowed
    when 'export_excel' then return 0;  -- blocked on free
    when 'automation_run' then return 0; -- blocked on free
    else
      return 0; -- unknown feature blocked by default
  end case;
end;
$$;

create or replace function public.increment_usage(p_feature text, p_quantity integer default 1)
returns jsonb
language plpgsql
security definer
set search_path = public
as $$
declare
  v_user_id uuid;
  v_today date := (timezone('utc', now()))::date;
  v_is_pro boolean;
  v_limit integer;
  v_used integer;
begin
  v_user_id := auth.uid();
  if v_user_id is null then
    raise exception 'Not authenticated';
  end if;

  if p_quantity is null or p_quantity <= 0 then
    raise exception 'Invalid quantity';
  end if;

  insert into public.profiles (id)
  values (v_user_id)
  on conflict (id) do nothing;

  v_is_pro := public.is_pro(v_user_id);
  v_limit := public.feature_limit(p_feature, v_is_pro);

  -- free-blocked feature
  if v_limit = 0 then
    return jsonb_build_object(
      'ok', false,
      'reason', 'plan_blocked',
      'feature', p_feature,
      'plan', case when v_is_pro then 'pro' else 'free' end
    );
  end if;

  insert into public.usage_daily (user_id, feature, usage_day, used)
  values (v_user_id, p_feature, v_today, p_quantity)
  on conflict (user_id, feature, usage_day)
  do update set used = public.usage_daily.used + excluded.used
  returning used into v_used;

  if v_limit is not null and v_used > v_limit then
    update public.usage_daily
    set used = used - p_quantity
    where user_id = v_user_id and feature = p_feature and usage_day = v_today;

    return jsonb_build_object(
      'ok', false,
      'reason', 'limit_exceeded',
      'feature', p_feature,
      'limit', v_limit,
      'used', v_used - p_quantity
    );
  end if;

  return jsonb_build_object(
    'ok', true,
    'feature', p_feature,
    'used', v_used,
    'limit', v_limit,
    'plan', case when v_is_pro then 'pro' else 'free' end
  );
end;
$$;

create or replace function public.get_license_status()
returns jsonb
language plpgsql
security definer
set search_path = public
as $$
declare
  v_user_id uuid;
  v_profile public.profiles%rowtype;
  v_today date := (timezone('utc', now()))::date;
  v_usage jsonb;
begin
  v_user_id := auth.uid();
  if v_user_id is null then
    return jsonb_build_object('ok', false, 'error', 'not_authenticated');
  end if;

  insert into public.profiles (id)
  values (v_user_id)
  on conflict (id) do nothing;

  select * into v_profile
  from public.profiles
  where id = v_user_id;

  select coalesce(
    jsonb_object_agg(feature, used),
    '{}'::jsonb
  )
  into v_usage
  from public.usage_daily
  where user_id = v_user_id
    and usage_day = v_today;

  return jsonb_build_object(
    'ok', true,
    'user_id', v_user_id,
    'plan', case
      when public.is_pro(v_user_id) then 'pro'
      else 'free'
    end,
    'pro_expires_at', v_profile.pro_expires_at,
    'last_verified_at', v_profile.last_verified_at,
    'usage_today', v_usage
  );
end;
$$;

-- =========================
-- 6) RLS
-- =========================
alter table public.profiles enable row level security;
alter table public.usage_daily enable row level security;

-- profiles: user can read own row
drop policy if exists "profiles_select_own" on public.profiles;
create policy "profiles_select_own"
on public.profiles
for select
to authenticated
using (id = auth.uid());

-- profiles: user can update only own row (trigger blocks plan/pro changes)
drop policy if exists "profiles_update_own" on public.profiles;
create policy "profiles_update_own"
on public.profiles
for update
to authenticated
using (id = auth.uid())
with check (id = auth.uid());

-- usage_daily: user can read own usage
drop policy if exists "usage_daily_select_own" on public.usage_daily;
create policy "usage_daily_select_own"
on public.usage_daily
for select
to authenticated
using (user_id = auth.uid());

-- No direct insert/update/delete policies for clients on usage_daily.
-- Writes should go via increment_usage().

-- =========================
-- 7) Grants
-- =========================
grant usage on schema public to authenticated;
grant execute on function public.increment_usage(text, integer) to authenticated;
grant execute on function public.get_license_status() to authenticated;
grant execute on function public.is_pro(uuid) to authenticated;
grant execute on function public.feature_limit(text, boolean) to authenticated;

commit;
