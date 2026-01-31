<?php 

defined('ABSPATH') || exit;
function arismed_fill_user_profile_from_order_if_empty($user_id, $billing, $shipping) {
  if ( ! $user_id ) return;

  $user = get_user_by('id', $user_id);
  if ( ! $user ) return;

  $meta_map = [
    'first_name' => $billing['first_name'] ?? '',
    'last_name'  => $billing['last_name'] ?? '',
  ];

  foreach ($meta_map as $meta_key => $value) {
    if ( ! $value ) continue;

    $current = get_user_meta($user_id, $meta_key, true);
    if ( empty($current) ) {
      update_user_meta($user_id, $meta_key, $value);
    }
  }

  // Woo-поля адреса
  $woo_meta = [
    'billing_first_name' => $billing['first_name'] ?? '',
    'billing_last_name'  => $billing['last_name'] ?? '',
    'billing_email'      => $billing['email'] ?? '',
    'billing_phone'      => $billing['phone'] ?? '',
    'billing_address_1'  => $billing['address_1'] ?? '',
    'billing_city'       => $billing['city'] ?? '',
    'billing_state'      => $billing['state'] ?? '',
    'billing_postcode'   => $billing['postcode'] ?? '',
    'billing_country'    => $billing['country'] ?? 'RU',

    'shipping_first_name' => $shipping['first_name'] ?? '',
    'shipping_last_name'  => $shipping['last_name'] ?? '',
    'shipping_address_1'  => $shipping['address_1'] ?? '',
    'shipping_city'       => $shipping['city'] ?? '',
    'shipping_state'      => $shipping['state'] ?? '',
    'shipping_postcode'   => $shipping['postcode'] ?? '',
    'shipping_country'    => $shipping['country'] ?? 'RU',
  ];

  foreach ($woo_meta as $meta_key => $value) {
    if ( ! $value ) continue;

    $current = get_user_meta($user_id, $meta_key, true);
    if ( empty($current) ) {
      update_user_meta($user_id, $meta_key, $value);
    }
  }
}