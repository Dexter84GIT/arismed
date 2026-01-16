<?php 

add_action('woocommerce_save_account_details', function ($user_id) {
    if (!$user_id) return;

    $phone = isset($_POST['billing_phone']) ? wc_clean(wp_unslash($_POST['billing_phone'])) : '';
    update_user_meta($user_id, 'billing_phone', $phone);
}, 20);