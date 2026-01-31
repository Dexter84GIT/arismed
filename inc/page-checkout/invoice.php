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

        <?php
        $_wpnonce = wp_create_nonce('generate_wpo_wcpdf');

        $pdf_url = add_query_arg([
            'action'        => 'generate_wpo_wcpdf',
            'document_type' => 'invoice',
            'order_ids'     => $order->get_id(),
            'order_key'     => $order->get_order_key(),
            'nonce' => $_wpnonce,
            '_wpnonce' => $_wpnonce,
            'arismed_invoice' => '1',
        ], admin_url('admin-ajax.php'));
        ?>
        <a href="<?php echo esc_url($pdf_url); ?>" target="_blank" class="druk submit" rel="noopener">
          Скачать PDF
        </a>