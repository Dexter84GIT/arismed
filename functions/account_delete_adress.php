<?php

add_action('wp_ajax_arismed_delete_address', 'arismed_delete_address');

function arismed_delete_address() {
  if ( ! is_user_logged_in() ) {
    wp_send_json_error(['message' => 'Not logged in'], 401);
  }

  $address_id = isset($_POST['address_id']) ? sanitize_text_field(wp_unslash($_POST['address_id'])) : '';
  if ($address_id === '') {
    wp_send_json_error(['message' => 'Missing address_id'], 400);
  }

  $user_id = get_current_user_id();
  $addresses = get_user_meta($user_id, 'arismed_saved_addresses', true);
  if ( ! is_array($addresses) ) $addresses = [];

  $before = count($addresses);

  $addresses = array_values(array_filter($addresses, function($a) use ($address_id) {
    return !(is_array($a) && isset($a['id']) && (string)$a['id'] === (string)$address_id);
  }));

  if (count($addresses) === $before) {
    wp_send_json_error(['message' => 'Address not found'], 404);
  }

  update_user_meta($user_id, 'arismed_saved_addresses', $addresses);

  wp_send_json_success(['address_id' => $address_id]);
}
