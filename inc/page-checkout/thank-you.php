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

$payment_type = $order->get_meta('payment') === 'invoice' ? 'invoice' : 'online';

?>

<?php if (in_array($status, ['failed', 'cancelled'], true)): ?>
  <div class="block df fdc gap20">
    <h2 class="sectionTitle">Оплата не прошла</h2>
    <p class="muted">
      Заказ создан, но платёж не был завершён.
      Вы можете попробовать оплатить его повторно или связаться с нами.
    </p>
  </div>

<?php else: ?>
  <?php
  $name = (string) $order->get_meta('name');
  $surname = (string) $order->get_meta('surname');
  $email = (string) $order->get_meta('email');
  $phone = (string) $order->get_meta('phone');
  $city = (string) $order->get_meta('city');

  if ($payment_type === 'invoice') {
    $organization = (string) $order->get_meta('organization');
    $inn = (string) $order->get_meta('inn');
    $kpp = (string) $order->get_meta('kpp');
    $organization_address = (string) $order->get_meta('organization_address');
  }

  $full_name = trim($name . ' ' . $surname);
  $total = $order->get_formatted_order_total();
  $date_created = $order->get_date_created();
  $date = $date_created ? wc_format_datetime($date_created, 'd.m.Y H:i') : '';
  ?>
  <div class="block df fdc gap60">
    <h2 class="sectionTitle">Спасибо! Заказ оформлен!</h2>

    <?php if ($payment_type === 'invoice'): ?>
      <p class="muted">Мы пришлем вам счет на E-mail <b><?= esc_html($email) ?></b> после проверки данных.</p>
    <?php endif; ?>

    <div class="block df fdc gap20">
      <h3>Детали заказа: </h3>
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

        <?php if ($payment_type === 'invoice'): ?>
          <div class="row df aic gap30">
            <span class="legend">Организация:</span>
            <span class="value"><?= esc_html($organization) ?></span>
          </div>
          <div class="row df aic gap30">
            <span class="legend">ИНН:</span>
            <span class="value"><?= esc_html($inn) ?></span>
          </div>
          <div class="row df aic gap30">
            <span class="legend">КПП:</span>
            <span class="value"><?= esc_html($kpp) ?></span>
          </div>
        <?php endif; ?>

      </div>
    </div>

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