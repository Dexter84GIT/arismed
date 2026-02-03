<?php
$show_saved_addresses = false;
$is_logged_in = is_user_logged_in();
if ($is_logged_in) {
    $uid = get_current_user_id();
    $addresses = get_user_meta($uid, 'arismed_saved_addresses', true);
    $show_saved_addresses = is_array($addresses) && !empty($addresses);
}

?>

<section class="checkout section">
    <div class="container">
        <?php if (function_exists('wc_print_notices'))
            wc_print_notices(); ?>

        <form action="" method="post" id="checkoutForm" class="checkoutForm df fdc gap60">

            <div class="block top df fdc gap30">
                <h2 class="sectionTitle druk">Оформление заказа</h2>
                <h3 class="druk">Контактная информация</h3>

                <div class="row df fdc gap30">
                    <p class="label">На этот E-mail будут отправлены сведения о заказе</p>
                    <div class="field">
                        <input type="email" maxlength="30" class="textInput" name="email" placeholder="E-mail">
                    </div>
                </div>
            </div>

            <div class="block shipping df fdc gap30">
                <h3 class="druk">Адрес доставки</h3>
                <div class="block df fdc gap20">

                    <p class="label">Введите адрес, на который нужно будет отправить заказ</p>

                    <?php if ($show_saved_addresses): ?>
                        <div class="row df fdc gap10" id="savedAddressWrap">
                            <p class="label">Выберите сохранённый адрес</p>
                            <div class="field">
                                <select class="textInput" id="savedAddressSelect">
                                    <option value="">— Не выбирать —</option>
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="row row2 df aic gap20">
                        <div class="field">
                            <input type="text" maxlength="20" class="textInput" name="name" placeholder="Имя">
                        </div>
                        <div class="field">
                            <input type="text" maxlength="20" class="textInput" name="surname" placeholder="Фамилия">
                        </div>
                    </div>

                    <div class="row">
                        <div class="field">
                            <input type="text" maxlength="50" class="textInput" name="adress" placeholder="Адрес">
                        </div>
                    </div>

                    <div class="row row3 df aic gap20">
                        <div class="field">
                            <input type="text" maxlength="4" class="textInput" name="building" placeholder="Корпус">
                        </div>
                        <div class="field">
                            <input type="text" maxlength="4" class="textInput" name="entrance" placeholder="Подъезд">
                        </div>
                        <div class="field">
                            <input type="text" maxlength="4" class="textInput" name="floor" placeholder="Этаж">
                        </div>
                    </div>

                    <div class="row row2 df aic gap20">
                        <div class="field">
                            <input type="text" maxlength="30" class="textInput" name="city"
                                placeholder="Населенный пункт">
                        </div>
                        <div class="field">
                            <input type="text" maxlength="30" class="textInput" name="state"
                                placeholder="Область, район">
                        </div>
                    </div>

                    <div class="row row2 df aic gap20">
                        <div class="field">
                            <input type="text" maxlength="6" name="post" class="textInput"
                                placeholder="Почтовый индекс">
                        </div>
                        <div class="field">
                            <input type="tel" name="phone" maxlength="14" id="phone" class="textInput"
                                placeholder="Телефон">
                        </div>
                    </div>
                </div>
            </div>

            <div class="block payment df fdc gap30">
                <h3 class="druk">Способы оплаты</h3>

                <div class="block df fdc gap10">
                    <div class="row df aifs gap30">
                        <label for="online" class="df aifs gap30">
                            <div class="fieldRadio">
                                <input type="radio" name="payment" id="online" value="online" checked>
                            </div>
                            <div class="field df fdc gap15">
                                <h3 class="druk">Оплата онлайн</h3>
                                <span>Оплата через ЮКасса</span>
                            </div>
                        </label>
                    </div>

                    <div class="row df aifs gap30 <?php echo !$is_logged_in ? 'disabled' : ''; ?>">
                        <label for="invoice" class="df aifs gap30">
                            <div class="fieldRadio">
                                <input type="radio" name="payment" id="invoice" value="invoice" <?php echo !$is_logged_in ? 'disabled' : ''; ?>>
                            </div>

                            <div class="field df fdc gap15">
                                <h3 class="druk">Сформировать счёт</h3>

                                <?php if ($is_logged_in): ?>
                                    <span>Счёт для безналичной оплаты</span>
                                <?php else: ?>
                                    <span class="muted">
                                        Запрос счёта доступен только зарегистрированным пользователям.
                                        <a href="/login">Войти</a>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="block description df fdc gap30">
                <h3 class="druk">Примечание к заказу</h3>
                <div class="field">
                    <textarea name="description" rows="3" placeholder="Ваш комментарий"></textarea>
                </div>
            </div>

            <p class="disclaimer">
                Продолжая покупку, вы принимаете
                <a href="/policy-privacy-data" target="_blank">политику обработки персональных данных</a>
                и соглашаетесь с
                <a href="/publichnaya-oferta" target="_blank">публичной офертой</a>
            </p>

            <?php if (function_exists('wc_terms_and_conditions_checkbox_enabled') ? wc_terms_and_conditions_checkbox_enabled() : true): ?>
                <label class="df aic gap10">
                    <input type="checkbox" name="terms" value="1" required>
                    <span>Я принимаю правила и условия</span>
                </label>
            <?php endif; ?>

            <input type="hidden" name="nonce" value="<?php echo esc_attr(
                wp_create_nonce('arismed_checkout_prepare')
            ); ?>">



            <div class="block df aic gap60 jcfe submitRow">
                <p class="notice" id="errorNotice"></p>
                <button type="submit" class="druk submit df aic gap10">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                        <path d="M6 0L4.9425 1.0575L9.1275 5.25H0V6.75H9.1275L4.9425 10.9425L6 12L12 6L6 0Z" />
                    </svg>
                    <p>Продолжить</p>
                </button>
            </div>

        </form>
    </div>
</section>