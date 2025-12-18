<div class="row breadcrumbs df aic gap10">
<?php
if (function_exists('woocommerce_breadcrumb')) {
    woocommerce_breadcrumb([
        'delimiter'   => '<span>/</span>',
        'wrap_before' => '',
        'wrap_after'  => '',
        'before'      => '',
        'after'       => '',
        'home'        => 'Главная',
    ]);
}
?>
</div>