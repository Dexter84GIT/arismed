<?php
function create_sbp_qr($order) {
  $amount = $order->get_total() * 100;
  $payment_purpose = 'Оплата заказа #' . $order->get_id();
  $callback_url = home_url('/callback-url/');

  $data = [
    'type' => 'createQr',
    'login' => 'evolenta',
    'callback' => $callback_url,
    'qrcType' => '02',
    'currency' => 'RUB',
    'amount' => $amount,
    'paymentPurpose' => $payment_purpose,
    'orgId' => '430',
    'redirectUrl' => home_url('/thankyou/')
  ];

  $response = wp_remote_post('http://sbpekvtest.el-plat.ru', [
    'method'    => 'POST',
    'body'      => json_encode($data),
    'headers'   => ['Content-Type' => 'application/json', 'Cache-Control' => 'no-cache'],
  ]);

  if (is_wp_error($response)) {
    return false;
  }

  $body = wp_remote_retrieve_body($response);
  $result = json_decode($body, true);

  if (isset($result['code']) && $result['code'] === 0 && isset($result['info']['qrData'])) {
    return $result['info'];
  }

  return false;
}