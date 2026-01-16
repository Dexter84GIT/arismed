<?php

add_action('init', function () {
    if (
        empty($_POST['do_login']) ||
        empty($_POST['custom_login_nonce']) ||
        !wp_verify_nonce($_POST['custom_login_nonce'], 'custom_login')
    ) {
        return;
    }

    $email = isset($_POST['login_email']) ? sanitize_email(wp_unslash($_POST['login_email'])) : '';
    $password = isset($_POST['login_password']) ? (string) wp_unslash($_POST['login_password']) : '';

    if ($email === '' || $password === '') {
        wc_add_notice('Введите email и пароль.', 'error');
        return;
    }

    $user = get_user_by('email', $email);
    if (!$user) {
        wc_add_notice('Неверный email или пароль.', 'error');
        return;
    }

    $signon = wp_signon([
        'user_login'    => $user->user_login,
        'user_password' => $password,
        'remember'      => true,
    ], false);

    if (is_wp_error($signon)) {
        wc_add_notice('Неверный email или пароль.', 'error');
        return;
    }

    wp_safe_redirect(home_url('/account/'));
    exit;
});

add_filter('login_redirect', function ($redirect_to, $requested_redirect_to, $user) {
    if ($user instanceof WP_User) {
        return home_url('/account/');
    }
    return $redirect_to;
}, 10, 3);