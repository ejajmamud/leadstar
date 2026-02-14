import { createClient } from 'https://esm.sh/@supabase/supabase-js@2';

const SUPABASE_URL = Deno.env.get('SUPABASE_URL') ?? '';
const SUPABASE_SERVICE_ROLE_KEY = Deno.env.get('SUPABASE_SERVICE_ROLE_KEY') ?? '';
const ADMIN_UPGRADE_KEY = Deno.env.get('ADMIN_UPGRADE_KEY') ?? '';

if (!SUPABASE_URL || !SUPABASE_SERVICE_ROLE_KEY) {
  throw new Error('Missing SUPABASE_URL or SUPABASE_SERVICE_ROLE_KEY');
}

const admin = createClient(SUPABASE_URL, SUPABASE_SERVICE_ROLE_KEY, {
  auth: { persistSession: false }
});

Deno.serve(async (req) => {
  if (req.method === 'OPTIONS') {
    return new Response('ok', { status: 204, headers: corsHeaders() });
  }

  if (req.method !== 'POST') {
    return json({ ok: false, error: 'Method not allowed' }, 405);
  }

  const incomingAdminKey = req.headers.get('x-admin-key') ?? '';
  if (!ADMIN_UPGRADE_KEY || incomingAdminKey !== ADMIN_UPGRADE_KEY) {
    return json({ ok: false, error: 'Invalid admin key' }, 401);
  }

  const body = await req.json().catch(() => null);
  if (!body || !body.user_id || !body.plan) {
    return json({ ok: false, error: 'Missing payload fields' }, 400);
  }

  const plan = String(body.plan).toLowerCase();
  if (plan !== 'pro' && plan !== 'free') {
    return json({ ok: false, error: 'Invalid plan value' }, 400);
  }

  const updatePayload: Record<string, unknown> = {
    plan,
    last_verified_at: new Date().toISOString()
  };

  if (body.pro_expires_at) {
    updatePayload.pro_expires_at = body.pro_expires_at;
  } else if (plan === 'free') {
    updatePayload.pro_expires_at = null;
  }

  const { error } = await admin
    .from('profiles')
    .update(updatePayload)
    .eq('id', body.user_id);

  if (error) {
    return json({ ok: false, error: error.message }, 500);
  }

  return json({
    ok: true,
    plan,
    pro_expires_at: updatePayload.pro_expires_at ?? null
  });
});

function json(data: unknown, status = 200): Response {
  return new Response(JSON.stringify(data), {
    status,
    headers: {
      'Content-Type': 'application/json',
      ...corsHeaders()
    }
  });
}

function corsHeaders(): HeadersInit {
  return {
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Headers': 'authorization, x-client-info, apikey, content-type, x-admin-key',
    'Access-Control-Allow-Methods': 'POST, OPTIONS'
  };
}
