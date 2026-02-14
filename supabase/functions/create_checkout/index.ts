const SUCCESS_URL = 'https://apps.ejaj.space/leadstar/account?success=1';
const CANCEL_URL = 'https://apps.ejaj.space/leadstar/pricing';

function corsHeaders() {
  return {
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Headers': 'authorization, x-client-info, apikey, content-type',
    'Access-Control-Allow-Methods': 'POST, OPTIONS'
  };
}

function json(data, status = 200) {
  const headers = corsHeaders();
  headers['Content-Type'] = 'application/json';
  return new Response(JSON.stringify(data), { status, headers });
}

Deno.serve(async (req) => {
  try {
    if (req.method === 'OPTIONS') {
      return new Response('ok', { status: 204, headers: corsHeaders() });
    }

    if (req.method !== 'POST') {
      return json({ ok: false, error: 'Method not allowed' }, 405);
    }

    const SUPABASE_URL = Deno.env.get('SUPABASE_URL') || '';
    const SUPABASE_ANON_KEY = Deno.env.get('SUPABASE_ANON_KEY') || '';
    const STRIPE_SECRET_KEY = Deno.env.get('STRIPE_SECRET_KEY') || '';
    const STRIPE_PRICE_ID = Deno.env.get('STRIPE_PRICE_ID') || '';

    if (!SUPABASE_URL || !SUPABASE_ANON_KEY) {
      return json({ ok: false, error: 'Server missing Supabase secrets' }, 500);
    }
    if (!STRIPE_SECRET_KEY || !STRIPE_PRICE_ID) {
      return json({ ok: false, error: 'Server missing Stripe secrets' }, 500);
    }

    const authHeader = req.headers.get('authorization') || req.headers.get('Authorization') || '';
    if (!authHeader.toLowerCase().startsWith('bearer ')) {
      return json({ ok: false, error: 'Missing bearer token' }, 401);
    }

    const userResp = await fetch(SUPABASE_URL + '/auth/v1/user', {
      method: 'GET',
      headers: {
        'apikey': SUPABASE_ANON_KEY,
        'Authorization': authHeader
      }
    });
    if (!userResp.ok) {
      return json({ ok: false, error: 'Unauthorized' }, 401);
    }

    const user = await userResp.json().catch(() => null);
    if (!user || !user.id || !user.email) {
      return json({ ok: false, error: 'Unauthorized' }, 401);
    }

    const form = new URLSearchParams();
    form.append('mode', 'subscription');
    form.append('customer_email', user.email);
    form.append('line_items[0][price]', STRIPE_PRICE_ID);
    form.append('line_items[0][quantity]', '1');
    form.append('success_url', SUCCESS_URL);
    form.append('cancel_url', CANCEL_URL);
    form.append('metadata[user_id]', user.id);

    const stripeResp = await fetch('https://api.stripe.com/v1/checkout/sessions', {
      method: 'POST',
      headers: {
        'Authorization': 'Bearer ' + STRIPE_SECRET_KEY,
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: form
    });

    const stripeJson = await stripeResp.json().catch(() => ({}));
    if (!stripeResp.ok) {
      const msg = stripeJson && stripeJson.error && stripeJson.error.message
        ? stripeJson.error.message
        : 'Stripe checkout creation failed';
      return json({ ok: false, error: msg }, 500);
    }

    if (!stripeJson || !stripeJson.url) {
      return json({ ok: false, error: 'Checkout URL missing' }, 500);
    }

    return json({ ok: true, url: stripeJson.url });
  } catch (err) {
    return json({ ok: false, error: String(err) }, 500);
  }
});
