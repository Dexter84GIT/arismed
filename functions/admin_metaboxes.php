<?php
add_action('add_meta_boxes', function () {
    add_meta_box(
        'arismed_order_data',
        'Данные клиента (Arismed)',
        'arismed_render_order_meta_box',
        'shop_order',
        'side',
        'default'
    );
});

function arismed_render_order_meta_box($post)
{
    $order = wc_get_order($post->ID);
    if (!$order) {
        echo '<p>Заказ не найден</p>';
        return;
    }

    $fields = [
        'name' => 'Имя',
        'surname' => 'Фамилия',
        'email' => 'Email',
        'phone' => 'Телефон',
        'city' => 'Город',
        'state' => 'Область',
        'post' => 'Индекс',
        'address' => 'Адрес',
        'building' => 'Дом/корпус',
        'entrance' => 'Подъезд',
        'floor' => 'Этаж',
        'note' => 'Комментарий',
        'payment' => 'Метод оплаты',
    ];

    echo '<div style="font-size:13px;">';

    foreach ($fields as $key => $label) {
        $value = $order->get_meta($key);

        if ($value === '') {
            continue;
        }

        echo '<p><strong>' . esc_html($label) . ':</strong><br>' .
            esc_html($value) .
            '</p>';
    }

    if ($order->get_meta('payment') === 'invoice') {
        echo '<hr>';

        $invoice_fields = [
            'organization' => 'Организация',
            'inn' => 'ИНН',
            'kpp' => 'КПП',
            'organization_address' => 'Юр. адрес',
        ];

        foreach ($invoice_fields as $key => $label) {
            $value = $order->get_meta($key);
            if ($value === '')
                continue;

            echo '<p><strong>' . esc_html($label) . ':</strong><br>' .
                esc_html($value) .
                '</p>';
        }
    }

    echo '</div>';
}

add_action('woocommerce_admin_order_data_after_billing_address', function ($order) {
    $order_id = $order->get_id();

    $fields = [
        'Имя'        => get_post_meta($order_id, '_customer_name', true),
        'Фамилия'    => get_post_meta($order_id, '_customer_surname', true),
        'Телефон'    => get_post_meta($order_id, '_customer_phone', true),
        'Город'      => get_post_meta($order_id, '_customer_city', true),
        'Адрес'      => get_post_meta($order_id, '_customer_address', true),
        'Дом'        => get_post_meta($order_id, '_customer_building', true),
        'Подъезд'    => get_post_meta($order_id, '_customer_entrance', true),
        'Этаж'       => get_post_meta($order_id, '_customer_floor', true),
        'Комментарий'=> get_post_meta($order_id, '_customer_note', true),
    ];

    if (!array_filter($fields)) {
        return;
    }

    echo '<div class="order_data_column">';
    echo '<h4>Данные покупателя (форма)</h4>';

    foreach ($fields as $label => $value) {
        if ($value !== '') {
            echo '<p><strong>' . esc_html($label) . ':</strong> ' . esc_html($value) . '</p>';
        }
    }

    echo '</div>';
});