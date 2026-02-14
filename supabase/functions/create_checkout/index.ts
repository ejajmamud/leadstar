import Stripe from 'npm:stripe@14.25.0';
import { createClient } from 'https://esm.sh/@supabase/supabase-js@2';

const SUCCESS_URL = 'https://apps.ejaj.space/leadstar/account?success=1';
const CANCEL_URL = 'https://apps.ejaj.space/leadstar/pricing';

Deno.serve(async (req: Request) => {
  if (req.method === 'OPTIONS') {
    return new Response('ok', { status: 204, headers: corsHeaders() });
  }

  if (req.method !== 'POST') {
    return json({ ok: false, error: 'Method not allowed' }, 405);
  }

  const SUPABASE_URL = Deno.env.get('SUPABASE_URL') ?? '';
  const SUPABASE_ANON_KEY = Deno.env.get('SUPABASE_ANON_KEY') ?? '';
  const STRIPE_SECRET_KEY = Deno.env.get('STRIPE_SECRET_KEY') ?? '';
  const STRIPE_PRICE_ID = Deno.env.get('STRIPE_PRICE_ID') ?? '';

  if (!SUPABASE_URL || !SUPABASE_ANON_KEY) {
    return json({ ok: false, error: 'Server missing Supabase secrets' }, 500);
  }
  if (!STRIPE_SECRET_KEY || !STRIPE_PRICE_ID) {
    return json({ ok: false, error: 'Server missing Stripe secrets' }, 500);
  }

  const stripe = new Stripe(STRIPE_SECRET_KEY, {
    apiVersion: '2024-06-20'
  });

  const authHeader = req.headers.get('authorization') ?? req.headers.get('Authorization') ?? '';
  if (!authHeader.toLowerCase().startsWith('bearer ')) {
    return json({ ok: false, error: 'Missing bearer token' }, 401);
  }

  const supabase = createClient(SUPABASE_URL, SUPABASE_ANON_KEY, {
    auth: { persistSession: false },
    global: { headers: { Authorization: authHeader } }
  });

  const userResp = await supabase.auth.getUser();
  const user = userResp.data.user;
  if (!user || !user.email) {
    return json({ ok: false, error: 'Unauthorized' }, 401);
  }

  const checkout = await stripe.checkout.sessions.create({
    mode: 'subscription',
    customer_email: user.email,
    line_items: [{ price: STRIPE_PRICE_ID, quantity: 1 }],
    success_url: SUCCESS_URL,
    cancel_url: CANCEL_URL,
    metadata: {
      user_id: user.id
    }
  });

  if (!checkout.url) {
    return json({ ok: false, error: 'Checkout URL missing' }, 500);
  }

  return json({ ok: true, url: checkout.url });
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
    'Access-Control-Allow-Headers': 'authorization, x-client-info, apikey, content-type',
    'Access-Control-Allow-Methods': 'POST, OPTIONS'
  };
}
