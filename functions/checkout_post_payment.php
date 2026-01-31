<?php
function process_post_payment($order, $is_invoice) {
  if ($is_invoice) {
    $order->set_payment_method_title('Оплата по счёту');
    $order->update_meta_data('_arismed_payment_choice', 'invoice');
    $order->set_status('on-hold', 'Invoice requested');
  } else {
    $order->set_payment_method_title('DEV оплата');
    $order->set_status('processing', 'DEV: payment bypass');
  }

  $redirect_path = $is_invoice ? '/invoice/' : '/thankyou/';
  $redirect = add_query_arg([
    'order_id' => $order->get_id(),
    'key'      => $order->get_order_key(),
  ], home_url($redirect_path));

  wp_send_json_success([
    'redirect' => $redirect,
    'order_id' => $order->get_id(),
    'is_invoice' => $is_invoice,
  ]);
}