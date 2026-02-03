<?php
/*
Template Name: Страница оплаты
*/
defined('ABSPATH') || exit;

get_header();

$intent = isset($_GET['intent']) ? sanitize_text_field($_GET['intent']) : '';
$data = $intent ? WC()->session->get("arismed_intent_$intent") : null;

$is_valid =
    $intent &&
    is_array($data) &&
    ($data['mode'] ?? null) === 'online' &&
    ($data['expires'] ?? 0) >= time();
?>

<section class="payment section" id="yookassa-payment">
    <div class="container df fdc gap20">
        <?php if (!$is_valid): ?>
            <h2 class="pageTitle">Сессия истекла</h2>
        <?php else: ?>
            <?php require get_template_directory() . '/inc/page-checkout/payment.php'; ?>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>