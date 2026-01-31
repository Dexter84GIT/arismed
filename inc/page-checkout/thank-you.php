<?php 
/** 
* @var WC_Order $order
*/
defined( 'ABSPATH' ) || exit;

$order_id = isset($_GET['order_id']) ? absint($_GET['order_id']) : 0;
$key      = isset($_GET['key']) ? wc_clean(wp_unslash($_GET['key'])) : '';

$order = $order_id ? wc_get_order($order_id) : false;

if ( ! $order || ! $key || $order->get_order_key() !== $key ) {
  echo '<section class="thankyou section">
    <div class="container">
      <h2 class="sectionTitle">Заказ не найден</h2>
    </div>
  </section>';
  return;
}
?>

<section class="thankyou section">
    <div class="container df fdc gap60">
        <div class="block df fdc gap20">
            <h2 class="sectionTitle">Спасибо! Заказ оформлен!</h2>
        </div>

        <div class="block details df fdc gap20">
          <h3 class="title">Детали заказа</h3>
          <div class="list df fdc gap5">
            <div class="row df aic gap30">
              <p class="legend">Номер заказа:</p>
              <p class="value"><?php echo esc_html( $order->get_order_number() ); ?></p>
            </div>

            <div class="row df aic gap30">
              <p class="legend">Дата заказа:</p>
              <p class="value"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></p>
            </div>

            <?php if ( is_user_logged_in() && (int) $order->get_user_id() === (int) get_current_user_id() && $order->get_billing_email() ) : ?>
              <div class="row df aic gap30">
                <p class="legend">Email:</p>
                <p class="value"><?php echo esc_html( $order->get_billing_email() ); ?></p>
              </div>
            <?php endif; ?>

            <?php
              $billing_name = trim($order->get_billing_first_name() . ' ' . $order->get_billing_last_name());
            ?>

            <?php if ( is_user_logged_in() && (int) $order->get_user_id() === (int) get_current_user_id() && $billing_name ) : ?>
              <div class="row df aic gap30">
                <p class="legend">ФИО:</p>
                <p class="value"><?php echo esc_html($billing_name); ?></p>
              </div>
            <?php endif; ?>
            
            <div class="row df aic gap30">
              <p class="legend">Сумма заказа:</p>
              <p class="value"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></p>
            </div>
            
            <?php if ( $order->get_payment_method_title() ) : ?>
              <div class="row df aic gap30">
                <p class="legend">Способ оплаты:</p>
                <p class="value"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></p>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="block controls df aic gap20">
            <?php if ( is_user_logged_in() ) : ?>
            <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="btn toAccount df aic gap10">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M6 6C5.175 6 4.46875 5.70625 3.88125 5.11875C3.29375 4.53125 3 3.825 3 3C3 2.175 3.29375 1.46875 3.88125 0.88125C4.46875 0.29375 5.175 0 6 0C6.825 0 7.53125 0.29375 8.11875 0.88125C8.70625 1.46875 9 2.175 9 3C9 3.825 8.70625 4.53125 8.11875 5.11875C7.53125 5.70625 6.825 6 6 6ZM0 12V9.9C0 9.475 0.109375 9.08438 0.328125 8.72813C0.546875 8.37188 0.8375 8.1 1.2 7.9125C1.975 7.525 2.7625 7.23438 3.5625 7.04063C4.3625 6.84688 5.175 6.75 6 6.75C6.825 6.75 7.6375 6.84688 8.4375 7.04063C9.2375 7.23438 10.025 7.525 10.8 7.9125C11.1625 8.1 11.4531 8.37188 11.6719 8.72813C11.8906 9.08438 12 9.475 12 9.9V12H0ZM1.5 10.5H10.5V9.9C10.5 9.7625 10.4656 9.6375 10.3969 9.525C10.3281 9.4125 10.2375 9.325 10.125 9.2625C9.45 8.925 8.76875 8.67188 8.08125 8.50313C7.39375 8.33438 6.7 8.25 6 8.25C5.3 8.25 4.60625 8.33438 3.91875 8.50313C3.23125 8.67188 2.55 8.925 1.875 9.2625C1.7625 9.325 1.67188 9.4125 1.60313 9.525C1.53438 9.6375 1.5 9.7625 1.5 9.9V10.5ZM6 4.5C6.4125 4.5 6.76563 4.35313 7.05938 4.05938C7.35313 3.76563 7.5 3.4125 7.5 3C7.5 2.5875 7.35313 2.23438 7.05938 1.94063C6.76563 1.64688 6.4125 1.5 6 1.5C5.5875 1.5 5.23438 1.64688 4.94063 1.94063C4.64688 2.23438 4.5 2.5875 4.5 3C4.5 3.4125 4.64688 3.76563 4.94063 4.05938C5.23438 4.35313 5.5875 4.5 6 4.5Z" />
                </svg>
                <span>Перейти в личный кабинет</span>
            </a>
            <?php endif; ?>
            <a href="/" class="btn toMain df aic gap10">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 0L9.41 1.41L3.83 7H16V9H3.83L9.41 14.59L8 16L0 8L8 0Z" fill="#2D3A4F" />
                </svg>
                <span>На главную</span>
            </a>
        </div>
    </div>
</section>