<?php

add_filter('woocommerce_registration_errors', function ($errors, $username, $email) {
    $full = isset($_POST['account_full_name']) ? trim(wp_unslash($_POST['account_full_name'])) : '';
    $p1 = isset($_POST['password']) ? (string) wp_unslash($_POST['password']) : '';
    $p2 = isset($_POST['password2']) ? (string) wp_unslash($_POST['password2']) : '';
    $agree = !empty($_POST['agree']);

    if ($full === '') $errors->add('account_full_name', 'Введите ФИО.');
    if (!$agree) $errors->add('agree', 'Нужно согласие на обработку персональных данных.');
    if ($p1 === '' || $p2 === '' || $p1 !== $p2) $errors->add('password2', 'Пароли не совпадают.');

    return $errors;
}, 10, 3);


add_action('woocommerce_created_customer', function ($customer_id) {
    $full = isset($_POST['account_full_name']) ? trim(wp_unslash($_POST['account_full_name'])) : '';
    $phone = isset($_POST['billing_phone']) ? wc_clean(wp_unslash($_POST['billing_phone'])) : '';

    if ($phone !== '') update_user_meta($customer_id, 'billing_phone', $phone);

    if ($full !== '') {
        $parts = preg_split('~\s+~u', $full, -1, PREG_SPLIT_NO_EMPTY);
        $first = $parts[0] ?? '';
        $last = $parts[1] ?? '';
        if ($first !== '') update_user_meta($customer_id, 'first_name', $first);
        if ($last !== '') update_user_meta($customer_id, 'last_name', $last);
        if ($first !== '' || $last !== '') wp_update_user(['ID' => $customer_id, 'display_name' => trim($first . ' ' . $last)]);
    }
}, 10);

// редирект на аккаунт после регистрации

add_filter('woocommerce_registration_redirect', function ($redirect) {
    return home_url('/account/');
}, 20);