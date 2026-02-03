<?php
$checkout = $data['checkout'] ?? [];

if (!empty($checkout['email'])) {
    $order->set_billing_email($checkout['email']);
}

if (!empty($checkout['phone'])) {
    $order->set_billing_phone($checkout['phone']);
}

if (!empty($checkout['name']) || !empty($checkout['surname'])) {
    $order->set_billing_first_name($checkout['name'] ?? '');
    $order->set_billing_last_name($checkout['surname'] ?? '');
}
?>

<div class="block details df fdc gap20">
  <h2 class="pageTitle">Заказ сформирован</h2>
  <div class="list df fdc gap5">
    <p>Ваш заказ <b>№<?php echo esc_html($order->get_order_number()); ?></b> успешно создан и находится в обработке.</p>
    <p>Вы можете следить за процессом выполнения в <a href="/account">личном кабинете.</a></p>
    <p>На направим счет на указанный вами E-mail <b><?php echo esc_html($order->get_billing_email()); ?></b> после
      проверки всех деталей заказа.</p>
  </div>
</div>