<?php
/* 
    Template Name: Страница оплаты
*/
defined('ABSPATH') || exit;

$intent = sanitize_text_field($_GET['intent'] ?? '');

$data = WC()->session->get("arismed_intent_$intent");

get_header(); ?>

<section class="payment section">
    <div class="container df fdc gap20">
        <?php if (
            !$intent ||
            !$data ||
            $data['mode'] !== 'online' ||
            $data['expires'] < time() ||
            $data['cart_hash'] !== WC()->cart->get_cart_hash()
        ): ?>
            <h2 class="pageTitle">Сессия истекла</h2>
        <?php else: ?>
            <?php include get_template_directory() . '/inc/page-checkout/payment.php'; ?>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>