<?php

add_action('wp_ajax_arismed_get_saved_addresses', 'arismed_get_saved_addresses');

function arismed_get_saved_addresses() {
  if ( ! is_user_logged_in() ) {
    wp_send_json_error([], 401);
  }

  $addresses = get_user_meta(get_current_user_id(), 'arismed_saved_addresses', true);
  if ( ! is_array($addresses) ) $addresses = [];

  wp_send_json_success([
    'addresses' => $addresses,
  ]);
}