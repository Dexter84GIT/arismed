<?php
add_action('wp_ajax_arismed_dev_pay', 'arismed_dev_pay');
add_action('wp_ajax_nopriv_arismed_dev_pay', 'arismed_dev_pay');

function arismed_dev_pay() {
  if ( ! function_exists('WC') ) {
    wp_send_json_error(['message' => 'WooCommerce not loaded'], 500);
  }

  $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
  if ( ! wp_verify_nonce($nonce, 'arismed_dev_pay') ) {
    wp_send_json_error(['message' => 'Bad nonce'], 403);
  }

  $cart = WC()->cart;
  if ( ! $cart || $cart->is_empty() ) {
    wp_send_json_error(['message' => 'Cart is empty'], 400);
  }

  // --- Входные данные ---
  $email   = isset($_POST['email'])   ? sanitize_email(wp_unslash($_POST['email'])) : '';
  $name    = isset($_POST['name'])    ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
  $surname = isset($_POST['surname']) ? sanitize_text_field(wp_unslash($_POST['surname'])) : '';

  $address = isset($_POST['adress']) ? sanitize_text_field(wp_unslash($_POST['adress'])) : '';
  $city    = isset($_POST['city'])   ? sanitize_text_field(wp_unslash($_POST['city'])) : '';
  $state   = isset($_POST['state'])  ? sanitize_text_field(wp_unslash($_POST['state'])) : '';
  $post    = isset($_POST['post'])   ? sanitize_text_field(wp_unslash($_POST['post'])) : '';
  $phone   = isset($_POST['phone'])  ? wc_clean(wp_unslash($_POST['phone'])) : '';

  $building = isset($_POST['building']) ? sanitize_text_field(wp_unslash($_POST['building'])) : '';
  $entrance = isset($_POST['entrance']) ? sanitize_text_field(wp_unslash($_POST['entrance'])) : '';
  $floor    = isset($_POST['floor'])    ? sanitize_text_field(wp_unslash($_POST['floor'])) : '';

  $note    = isset($_POST['description']) ? sanitize_textarea_field(wp_unslash($_POST['description'])) : '';
  $payment = isset($_POST['payment']) ? sanitize_text_field(wp_unslash($_POST['payment'])) : '';

  $is_invoice = ($payment === 'invoice');

  // --- Базовая валидация ---
  if ( ! is_user_logged_in() ) {
    if ( ! $email || ! is_email($email) ) {
      wp_send_json_error(['message' => 'Invalid email'], 400);
    }
  }

  // Если хочешь, чтобы имя НЕ было обязательным — не валидируем его вообще.
  // Но если нужно — раскомментируй:
  // if ( $name === '' ) wp_send_json_error(['message' => 'Name required'], 400);

  try {
    $was_guest   = ! is_user_logged_in();
    $customer_id = $was_guest ? 0 : get_current_user_id();

    // Гостю создаём/берём пользователя по email
    if ( $was_guest ) {
      $customer_id = arismed_get_or_create_user_id_by_email($email, $name, $surname);
    }

    // --- Создаём заказ ---
    $order = wc_create_order();
    if ( $customer_id > 0 ) {
      $order->set_customer_id($customer_id);
    }

    foreach ( $cart->get_cart() as $cart_item ) {
      $product = $cart_item['data'];
      if ( ! $product || ! $product->exists() ) {
        continue;
      }

      $order->add_product($product, (int) $cart_item['quantity'], [
        'variation' => $cart_item['variation'] ?? [],
      ]);
    }

    $billing = [
      'first_name' => $name,
      'last_name'  => $surname,
      'email'      => $email,
      'phone'      => $phone,
      'address_1'  => $address,
      'city'       => $city,
      'state'      => $state,
      'postcode'   => $post,
      'country'    => 'RU',
    ];

    $shipping = [
      'first_name' => $name,
      'last_name'  => $surname,
      'address_1'  => $address,
      'city'       => $city,
      'state'      => $state,
      'postcode'   => $post,
      'country'    => 'RU',
    ];

    $order->set_address($billing, 'billing');
    $order->set_address($shipping, 'shipping');

    if ( $building !== '' ) $order->update_meta_data('_shipping_building', $building);
    if ( $entrance !== '' ) $order->update_meta_data('_shipping_entrance', $entrance);
    if ( $floor !== '' )    $order->update_meta_data('_shipping_floor', $floor);

    if ( $note !== '' ) {
      $order->set_customer_note($note);
    }

    if ( $is_invoice ) {
      $order->set_payment_method_title('Оплата по счёту');
      $order->update_meta_data('_arismed_payment_choice', 'invoice');
      $order->set_status('on-hold', 'Invoice requested');
    } else {
      $order->set_payment_method_title('DEV оплата');
      $order->set_status('processing', 'DEV: payment bypass');
    }

    $order->calculate_totals();
    $order->save();

    if ( $was_guest && $customer_id > 0 ) {

      if ( function_exists('arismed_fill_user_profile_from_order_if_empty') ) {
        arismed_fill_user_profile_from_order_if_empty($customer_id, $billing, $shipping);
      }

      $u = get_userdata($customer_id);
      if ( $u ) {
        $display = trim($name . ' ' . $surname);
        if ( $display === '' ) {
          $display = $email; 
        }

        $args = ['ID' => $customer_id];
        $need = false;

        if ( empty($u->display_name) ) { $args['display_name'] = $display; $need = true; }
        if ( empty($u->nickname) )     { $args['nickname']     = $display; $need = true; }

        if ( empty($u->first_name) && $name !== '' )   { $args['first_name'] = $name; $need = true; }
        if ( empty($u->last_name)  && $surname !== '' ){ $args['last_name']  = $surname; $need = true; }

        if ( $need ) {
          wp_update_user($args);
        }
      }

      wp_set_current_user($customer_id);
      wp_set_auth_cookie($customer_id, true);
    }

    $cart->empty_cart();

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

  } catch ( Exception $e ) {
    wp_send_json_error(['message' => $e->getMessage()], 500);
  }
}
