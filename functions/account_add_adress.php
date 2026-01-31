<?php

add_action('wp_ajax_arismed_add_address', 'arismed_add_address');

function arismed_add_address() {
  if ( ! is_user_logged_in() ) {
    wp_send_json_error(['message' => 'Not logged in'], 401);
  }

  $user_id = get_current_user_id();

  $addr = [
    'id'         => wp_generate_uuid4(),
    'address'  => sanitize_text_field($_POST['address'] ?? ''),
    'city'       => sanitize_text_field($_POST['city'] ?? ''),
    'state'      => sanitize_text_field($_POST['state'] ?? ''),
    'postcode'   => sanitize_text_field($_POST['postcode'] ?? ''),
    'country'    => sanitize_text_field($_POST['country'] ?? 'RU'),
    'building'   => sanitize_text_field($_POST['building'] ?? ''),
    'entrance'   => sanitize_text_field($_POST['entrance'] ?? ''),
    'floor'      => sanitize_text_field($_POST['floor'] ?? ''),
    'created'    => time(),
  ];

  if ( empty($addr['address']) ) {
    wp_send_json_error(['message' => 'Адрес отсутствует'], 400);
  }

  $addresses = get_user_meta($user_id, 'arismed_saved_addresses', true);
  if ( ! is_array($addresses) ) $addresses = [];

  $addresses[] = $addr;

  update_user_meta($user_id, 'arismed_saved_addresses', $addresses);

  wp_send_json_success(['address' => $addr]);
}