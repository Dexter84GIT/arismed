<?php 

add_action('woocommerce_save_account_details', 'arismed_save_extra_account_fields', 10, 1);

function arismed_save_extra_account_fields($user_id) {
  if ( ! $user_id ) return;

  if ( isset($_POST['billing_phone']) ) {
    update_user_meta(
      $user_id,
      'billing_phone',
      sanitize_text_field(wp_unslash($_POST['billing_phone']))
    );
  }
}