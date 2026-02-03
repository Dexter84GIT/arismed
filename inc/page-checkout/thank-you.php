<?php
/**
 * @var WC_Order $order
 */
defined('ABSPATH') || exit;

$order = false;

$order_id = isset($_GET['order_id']) ? absint($_GET['order_id']) : 0;
$key = isset($_GET['key']) ? wc_clean(wp_unslash($_GET['key'])) : '';

if ($order_id) {
    $order = wc_get_order($order_id);
}

if (!$order && $key) {
    $order_id = wc_get_order_id_by_order_key($key);
    if ($order_id) {
        $order = wc_get_order($order_id);
    }
}

if (!$order) {
    echo '<h2 class="sectionTitle">Заказ не найден</h2>';
    return;
}

if (WC()->cart) {
    WC()->cart->empty_cart();
}

$status = $order->get_status();

// Получаем метод оплаты
$payment_method = $order->get_payment_method(); // пример: 'cod', 'bacs', 'stripe'
$payment_type = in_array($payment_method, ['bacs', 'cheque'], true) ? 'invoice' : 'online';
?>

<?php if (in_array($status, ['failed', 'cancelled'], true)): ?>
    <div class="block df fdc gap20">
        <h2 class="sectionTitle">Оплата не прошла</h2>
        <p class="muted">
            Заказ создан, но платёж не был завершён.
            Вы можете попробовать оплатить его повторно или связаться с нами.
        </p>
    </div>
<?php elseif ($status === 'pending'): ?>
    <div class="block df fdc gap20">
        <h2 class="sectionTitle">Ожидание оплаты</h2>
        <p class="muted">
            Заказ создан и ожидает подтверждения оплаты.
        </p>
        <?php if ($payment_type === 'invoice'): ?>
            <p class="muted">Вы выбрали оплату по счёту. Наш менеджер свяжется с вами для выставления счёта.</p>
        <?php else: ?>
            <p class="muted">Вы выбрали онлайн-оплату. После подтверждения платежа заказ будет обработан.</p>
        <?php endif; ?>
    </div>
<?php else: ?>
    <?php
    $name = (string) $order->get_meta('name');
    $surname = (string) $order->get_meta('surname');
    $email = (string) $order->get_meta('email');
    $phone = (string) $order->get_meta('phone');
    $city = (string) $order->get_meta('city');

    $full_name = trim($name . ' ' . $surname);
    $total = $order->get_formatted_order_total();
    $date_created = $order->get_date_created();
    $date = $date_created ? wc_format_datetime($date_created, 'd.m.Y H:i') : '';
    ?>
    <div class="block df fdc gap60">
        <h2 class="sectionTitle">Спасибо! Заказ оформлен!</h2>

        <div class="list df fdc gap5">
            <div class="row df aic gap30">
                <span class="legend">ФИО:</span>
                <span class="value"><?= esc_html($full_name) ?></span>
            </div>

            <div class="row df aic gap30">
                <span class="legend">Почта:</span>
                <span class="value"><?= esc_html($email) ?></span>
            </div>

            <div class="row df aic gap30">
                <span class="legend">Телефон:</span>
                <span class="value"><?= esc_html($phone) ?></span>
            </div>

            <div class="row df aic gap30">
                <span class="legend">Город:</span>
                <span class="value"><?= esc_html($city) ?></span>
            </div>

            <div class="row df aic gap30">
                <span class="legend">Сумма заказа:</span>
                <span class="value"><?= wp_kses_post($total) ?></span>
            </div>

            <div class="row df aic gap30">
                <span class="legend">Дата заказа:</span>
                <span class="value"><?= esc_html($date) ?></span>
            </div>

            <div class="row df aic gap30">
                <span class="legend">Метод оплаты:</span>
                <span class="value">
                    <?= $payment_type === 'invoice' ? 'Оплата по счёту' : 'Онлайн-оплата' ?>
                </span>
            </div>
        </div>

        <?php if ($payment_type === 'invoice'): ?>
            <p class="muted">Менеджер свяжется с вами для выставления счёта.</p>
        <?php else: ?>
            <p class="muted">Ваш платёж подтверждён. Мы начинаем обработку заказа.</p>
        <?php endif; ?>

        <div class="block df aic gap20 controls">
            <a class="btn toAccount df aic gap10" href="/account">
                <span>Перейти в личный кабинет</span>
            </a>
            <a class="btn toMain df aic gap10" href="/">
                <span>На главную</span>
            </a>
        </div>
    </div>
<?php endif; ?>
