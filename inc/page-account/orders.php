    <div class="tabContent df fdc gap30 active" id="orders">
        <div class="top df fdc gap20">
            <h2 class="sectionTitle druk">Заказы</h2>
            <div class="searchBar">
                <input type="text" class="searchInput" placeholder="Поиск">
                <div class="btn">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M9.375 8.25H8.7825L8.5725 8.0475C9.3075 7.1925 9.75 6.0825 9.75 4.875C9.75 2.1825 7.5675 0 4.875 0C2.1825 0 0 2.1825 0 4.875C0 7.5675 2.1825 9.75 4.875 9.75C6.0825 9.75 7.1925 9.3075 8.0475 8.5725L8.25 8.7825V9.375L12 13.1175L13.1175 12L9.375 8.25ZM4.875 8.25C3.0075 8.25 1.5 6.7425 1.5 4.875C1.5 3.0075 3.0075 1.5 4.875 1.5C6.7425 1.5 8.25 3.0075 8.25 4.875C8.25 6.7425 6.7425 8.25 4.875 8.25Z" />
                    </svg>
                </div>
            </div>
            <div class="statusBar df aic gap10">
                <span class="statusBadge pending">В обработке</span>
                <span class="statusBadge complete">Выполнен</span>
                <span class="statusBadge canceled">Отменен</span>
                <span class="statusBadge hasPayment">Оплачен</span>
                <span class="statusBadge hasntPayment">Не оплачен</span>
                <span class="statusBadge reset df aic jcc">
                    <svg width="11" height="11" viewBox="0 0 11 11" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10.5 1.0575L9.4425 0L5.25 4.1925L1.0575 0L0 1.0575L4.1925 5.25L0 9.4425L1.0575 10.5L5.25 6.3075L9.4425 10.5L10.5 9.4425L6.3075 5.25L10.5 1.0575Z" />
                    </svg>
                </span>
            </div>
        </div>
        <div class="content df fdc gap20 accordeon">
        <?php
            if (!is_user_logged_in()) {
                echo '<p>Войдите, чтобы увидеть заказы.</p>';
            } else {
                $customer_id = get_current_user_id();
            
                $orders = wc_get_orders([
                    'customer_id' => $customer_id,
                    'limit'       => 20,
                    'orderby'     => 'date',
                    'order'       => 'DESC',
                    'status'      => array_keys(wc_get_order_statuses()),
                    'return'      => 'objects',
                ]);
            
                $status_to_badge = [
                    'wc-pending'    => 'pending',
                    'wc-processing' => 'pending',
                    'wc-on-hold'    => 'pending',
                    'wc-completed'  => 'complete',
                    'wc-cancelled'  => 'canceled',
                    'wc-refunded'   => 'canceled',
                    'wc-failed'     => 'canceled',
                ];
            
                if (!$orders) {
                    echo '<p>Заказов пока нет.</p>';
                } else {
                    foreach ($orders as $order) {
                        $order_id = $order->get_id();
                    
                        $created = $order->get_date_created();
                        $date_str = $created ? $created->date_i18n('d.m.y') : '';
                        $time_str = $created ? $created->date_i18n('H:i') : '';
                    
                        $status_slug = 'wc-' . $order->get_status();
                        $badge_class = $status_to_badge[$status_slug] ?? 'pending';
                    
                        $status_label = wc_get_order_status_name($order->get_status());
                    
                        $is_paid = $order->is_paid();
                        $pay_badge_class = $is_paid ? 'hasPayment' : 'hasntPayment';
                        $pay_badge_text = $is_paid ? 'Оплачен' : 'Не оплачен';
                    
                        $total = $order->get_total();
                        $currency = $order->get_currency();
                        $total_fmt = wc_price($total, ['currency' => $currency]);
                    
                        $items = $order->get_items();
                        ?>
                        <div class="item df fdc">
                            <div class="top df aic gap20">
                                <p class="number druk">Заказ №<?php echo esc_html($order_id); ?></p>
                                <p class="date df aic gap5">
                                    <span><?php echo esc_html($date_str); ?></span><span>/</span><span><?php echo esc_html($time_str); ?></span>
                                </p>
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.72665 11.06L8.77999 8L5.72665 4.94L6.66665 4L10.6667 8L6.66665 12L5.72665 11.06Z" />
                                </svg>
                            </div>
                    
                            <div class="bottom df fdc gap30">
                                <ul class="list">
                                    <?php foreach ($items as $item): ?>
                                        <?php
                                        $name = $item->get_name();
                                        $qty = (int) $item->get_quantity();
                                        ?>
                                        <li>
                                            <p><?php echo esc_html($name); ?></p>
                                            <?php if ($qty > 0): ?>
                                                <span><?php echo esc_html($qty); ?> шт.</span>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                            
                                <div class="block df aic jcsb gap40">
                                    <div class="statuses df aic gap10">
                                        <span class="statusBadge <?php echo esc_attr($badge_class); ?>">
                                            <?php echo esc_html($status_label); ?>
                                        </span>
                                        <span class="statusBadge <?php echo esc_attr($pay_badge_class); ?>">
                                            <?php echo esc_html($pay_badge_text); ?>
                                        </span>
                                    </div>
                                            
                                    <div class="block df aic gap60">
                                        <div class="overall df aic gap15">
                                            <p class="legend">Итого:</p>
                                            <p class="sum druk"><?php echo wp_kses_post($total_fmt); ?></p>
                                        </div>
                                            
                                        <button type="button"
                                                class="accountBtn df aic gap10"
                                                data-repeat-order="<?php echo esc_attr($order_id); ?>">
                                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M6 0L4.9425 1.0575L9.1275 5.25H0V6.75H9.1275L4.9425 10.9425L6 12L12 6L6 0Z" fill="white" />
                                            </svg>
                                            <p class="druk">Повторить заказ</p>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
            }
        ?>
    </div>
</div>