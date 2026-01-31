<?php
/* 
    Template Name: Страница оплаты
*/
defined('ABSPATH') || exit;

$order_id = isset($_GET['order_id']) ? absint($_GET['order_id']) : 0;
$key      = isset($_GET['key']) ? sanitize_text_field(wp_unslash($_GET['key'])) : '';
$order = wc_get_order($order_id);

get_header(); ?>

<section class="payment section">
    <div class="container df fdc gap20">
        <?php if (!$order_id || !$key) : ?>
            <h2 class="pageTitle">Некорректные параметры счета</h2>
        <?php elseif (!$order) : ?>
            <h2 class="pageTitle">Заказ не найден</h2>
        <?php elseif ($order->get_order_key() !== $key) : ?>  
            <h2 class="pageTitle">Доступ запрещен</h2>  
        <?php else : ?>
            <?php include get_template_directory() . '/inc/page-checkout/payment.php'; ?>    
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>