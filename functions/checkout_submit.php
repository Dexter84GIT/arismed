<?php
add_action('wp_ajax_arismed_checkout_prepare', 'arismed_checkout_prepare');
add_action('wp_ajax_nopriv_arismed_checkout_prepare', 'arismed_checkout_prepare');

function arismed_checkout_prepare()
{

  if (!function_exists('WC')) {
    wp_send_json_error(['message' => 'WooCommerce not loaded'], 500);
  }

  $nonce = isset($_POST['nonce'])
    ? sanitize_text_field(wp_unslash($_POST['nonce']))
    : '';

  if (!wp_verify_nonce($nonce, 'arismed_checkout_prepare')) {
    wp_send_json_error(['message' => 'Bad nonce'], 403);
  }

  $cart = WC()->cart;
  if (!$cart || $cart->is_empty()) {
    wp_send_json_error(['message' => 'Cart is empty'], 400);
  }

  $payment = isset($_POST['payment'])
    ? sanitize_text_field(wp_unslash($_POST['payment']))
    : '';


  if (!in_array($payment, ['online', 'invoice'], true)) {
    wp_send_json_error(['message' => 'Invalid payment method'], 400);
  }

  if ($payment === 'invoice' && !is_user_logged_in()) {
    wp_send_json_error([
      'code' => 'login_required',
      'message' => 'Login required',
    ], 403);
  }

  $data = [
    'email' => isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '',
    'phone' => isset($_POST['phone']) ? wc_clean(wp_unslash($_POST['phone'])) : '',
    'name' => isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '',
    'surname' => isset($_POST['surname']) ? sanitize_text_field(wp_unslash($_POST['surname'])) : '',
    'address' => isset($_POST['address']) ? sanitize_text_field(wp_unslash($_POST['address'])) : '',
    'city' => isset($_POST['city']) ? sanitize_text_field(wp_unslash($_POST['city'])) : '',
    'state' => isset($_POST['state']) ? sanitize_text_field(wp_unslash($_POST['state'])) : '',
    'post' => isset($_POST['post']) ? sanitize_text_field(wp_unslash($_POST['post'])) : '',
    'building' => isset($_POST['building']) ? sanitize_text_field(wp_unslash($_POST['building'])) : '',
    'entrance' => isset($_POST['entrance']) ? sanitize_text_field(wp_unslash($_POST['entrance'])) : '',
    'floor' => isset($_POST['floor']) ? sanitize_text_field(wp_unslash($_POST['floor'])) : '',
    'note' => isset($_POST['description']) ? sanitize_textarea_field(wp_unslash($_POST['description'])) : '',
    'payment' => $payment,
  ];

  $intent = wp_generate_uuid4();

  WC()->session->set("arismed_intent_$intent", [
    'mode' => $payment,
    'checkout' => $data,
    'cart_hash' => WC()->cart->get_cart_hash(),
    'expires' => time() + 15 * MINUTE_IN_SECONDS,
    'user_id' => is_user_logged_in() ? get_current_user_id() : null,
  ]);

  $redirect = add_query_arg(
    'intent',
    $intent,
    home_url($payment === 'online' ? '/payment/' : '/invoice/')
  );

  wp_send_json_success([
    'redirect' => $redirect,
  ]);
}
