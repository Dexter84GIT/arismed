<?php
add_filter('woocommerce_payment_gateways', function ($methods) {
    $methods[] = 'WC_Gateway_Arismed_Dummy';
    return $methods;
});

add_action('plugins_loaded', function () {
    if (!class_exists('WC_Payment_Gateway')) {
        return;
    }

    class WC_Gateway_Arismed_Dummy extends WC_Payment_Gateway {
        public function __construct() {
            $this->id = 'arismed_dummy';
            $this->has_fields = false;
            $this->method_title = 'ARISMED Dummy';
            $this->method_description = '';
            $this->enabled = 'yes';
            $this->title = 'Оплата (тест)';

            add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);
        }

        public function process_payment($order_id) {
            $order = wc_get_order($order_id);
            if (!$order) {
                return ['result' => 'fail'];
            }

            WC()->cart->empty_cart();

            $url = home_url('/checkout-pay/?order_id=' . (int) $order_id . '&key=' . rawurlencode($order->get_order_key()));
            return [
                'result' => 'success',
                'redirect' => $url,
            ];
        }
    }
});
