<section class="account section">
    <div class="container">
        <?php if (function_exists('wc_print_notices')) wc_print_notices(); ?>

        <?php
        if (is_user_logged_in()) {
            wp_safe_redirect(home_url('/account/'));
            exit;
        }
        ?>

        <form method="post" id="loginForm" class="checkoutForm df fdc gap30">
            <div class="block seller df fdc gap30">
                <h2 class="sectionTitle druk">Авторизация</h2>
                <p class="disclaimer">Авторизуйтесь, чтобы получить доступ к функциям личного кабинета</p>

                <div class="row row2 df aic gap20">
                    <div class="field">
                        <input type="email"
                               class="textInput"
                               placeholder="E-mail"
                               name="login_email"
                               autocomplete="email"
                               value="<?php echo isset($_POST['login_email']) ? esc_attr(wp_unslash($_POST['login_email'])) : ''; ?>"
                               required>
                    </div>

                    <div class="field passwordField">
                        <input type="password"
                               class="textInput"
                               placeholder="Пароль"
                               name="login_password"
                               autocomplete="current-password"
                               required>
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.99984 4.00033C10.5265 4.00033 12.7798 5.42033 13.8798 7.66699C12.7798 9.91366 10.5265 11.3337 7.99984 11.3337C5.47317 11.3337 3.21984 9.91366 2.11984 7.66699C3.21984 5.42033 5.47317 4.00033 7.99984 4.00033ZM7.99984 2.66699C4.6665 2.66699 1.81984 4.74033 0.666504 7.66699C1.81984 10.5937 4.6665 12.667 7.99984 12.667C11.3332 12.667 14.1798 10.5937 15.3332 7.66699C14.1798 4.74033 11.3332 2.66699 7.99984 2.66699Z"
                                  fill="#2D3A4F" fill-opacity="0.5"/>
                        </svg>
                    </div>
                </div>
            </div>

            <?php wp_nonce_field('custom_login', 'custom_login_nonce'); ?>

            <button type="submit"
                    class="druk submit df aife gap10"
                    name="do_login"
                    value="1">
                Войти
            </button>
        </form>
    </div>
</section>
