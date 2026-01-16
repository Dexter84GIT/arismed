<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

<div class="u-columns col2-set" id="customer_login">

	<div class="u-column1 col-1">

<?php endif; ?>

		<h2><?php esc_html_e( 'Login', 'woocommerce' ); ?></h2>

		<form class="woocommerce-form woocommerce-form-login login" method="post" novalidate>

			<?php do_action( 'woocommerce_login_form_start' ); ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="username"><?php esc_html_e( 'Username or email address', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
			</p>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
				<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true" />
			</p>

			<?php do_action( 'woocommerce_login_form' ); ?>

			<p class="form-row">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
				</label>
				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
				<button type="submit" class="woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><?php esc_html_e( 'Log in', 'woocommerce' ); ?></button>
			</p>
			<p class="woocommerce-LostPassword lost_password">
				<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a>
			</p>

			<?php do_action( 'woocommerce_login_form_end' ); ?>

		</form>

<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

	</div>

	<div class="u-column2 col-2">

		<h2><?php esc_html_e( 'Register', 'woocommerce' ); ?></h2>

<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

<form method="post" class="woocommerce-form woocommerce-form-register register checkoutForm df fdc gap30" <?php do_action( 'woocommerce_register_form_tag' ); ?>>

    <div class="block seller df fdc gap30">
        <h2 class="sectionTitle druk">Регистрация</h2>
        <p class="disclaimer">Зарегистрируйтесь, чтобы использовать все возможности личного кабинета: настройку подписки,
            связи с социальными сетями и другие. Мы никогда и ни при каких условиях не разглашаем личные
            данные клиентов. Контактная информация будет использована только для оформления заказов и
            более удобной работы с сайтом.</p>

        <div class="block df fdc gap20">
            <div class="row">
                <div class="field">
                    <input type="text" class="textInput" placeholder="ФИО" name="account_full_name" autocomplete="name" value="<?php echo isset($_POST['account_full_name']) ? esc_attr(wp_unslash($_POST['account_full_name'])) : ''; ?>" required>
                </div>
            </div>

            <div class="row row2 df aic gap20">
                <div class="field">
                    <input type="email" class="textInput" placeholder="E-mail" name="email" autocomplete="email" value="<?php echo isset($_POST['email']) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" required>
                </div>
                <div class="field">
                    <input type="tel" class="textInput" placeholder="Телефон" name="billing_phone" autocomplete="tel" value="<?php echo isset($_POST['billing_phone']) ? esc_attr(wp_unslash($_POST['billing_phone'])) : ''; ?>">
                </div>
            </div>

            <div class="row row2 df aic gap20">
                <div class="field passwordField">
                    <input type="password" class="textInput" placeholder="Пароль" name="password" autocomplete="new-password" required>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.99984 4.00033C10.5265 4.00033 12.7798 5.42033 13.8798 7.66699C12.7798 9.91366 10.5265 11.3337 7.99984 11.3337C5.47317 11.3337 3.21984 9.91366 2.11984 7.66699C3.21984 5.42033 5.47317 4.00033 7.99984 4.00033ZM7.99984 2.66699C4.6665 2.66699 1.81984 4.74033 0.666504 7.66699C1.81984 10.5937 4.6665 12.667 7.99984 12.667C11.3332 12.667 14.1798 10.5937 15.3332 7.66699C14.1798 4.74033 11.3332 2.66699 7.99984 2.66699ZM7.99984 6.00033C8.91984 6.00033 9.6665 6.74699 9.6665 7.66699C9.6665 8.58699 8.91984 9.33366 7.99984 9.33366C7.07984 9.33366 6.33317 8.58699 6.33317 7.66699C6.33317 6.74699 7.07984 6.00033 7.99984 6.00033ZM7.99984 4.66699C6.3465 4.66699 4.99984 6.01366 4.99984 7.66699C4.99984 9.32033 6.3465 10.667 7.99984 10.667C9.65317 10.667 10.9998 9.32033 10.9998 7.66699C10.9998 6.01366 9.65317 4.66699 7.99984 4.66699Z" fill="#2D3A4F" fill-opacity="0.5"/>
                    </svg>
                </div>

                <div class="field passwordField">
                    <input type="password" class="textInput" placeholder="Подтверждение пароля" name="password2" autocomplete="new-password" required>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.99984 4.00033C10.5265 4.00033 12.7798 5.42033 13.8798 7.66699C12.7798 9.91366 10.5265 11.3337 7.99984 11.3337C5.47317 11.3337 3.21984 9.91366 2.11984 7.66699C3.21984 5.42033 5.47317 4.00033 7.99984 4.00033ZM7.99984 2.66699C4.6665 2.66699 1.81984 4.74033 0.666504 7.66699C1.81984 10.5937 4.6665 12.667 7.99984 12.667C11.3332 12.667 14.1798 10.5937 15.3332 7.66699C14.1798 4.74033 11.3332 2.66699 7.99984 2.66699ZM7.99984 6.00033C8.91984 6.00033 9.6665 6.74699 9.6665 7.66699C9.6665 8.58699 8.91984 9.33366 7.99984 9.33366C7.07984 9.33366 6.33317 8.58699 6.33317 7.66699C6.33317 6.74699 7.07984 6.00033 7.99984 6.00033ZM7.99984 4.66699C6.3465 4.66699 4.99984 6.01366 4.99984 7.66699C4.99984 9.32033 6.3465 10.667 7.99984 10.667C9.65317 10.667 10.9998 9.32033 10.9998 7.66699C10.9998 6.01366 9.65317 4.66699 7.99984 4.66699Z" fill="#2D3A4F" fill-opacity="0.5"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="block agree">
        <label for="agree" class="df aic gap10 agreeCheck">
            <input type="checkbox" id="agree" name="agree" value="1" <?php checked(!empty($_POST['agree'])); ?> required>
            <p class="df aic gap5">Я согласен на обработку <a href="#"> персональных данных</a></p>
        </label>
    </div>

    <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>

    <button type="submit" class="druk submit df aife gap10" name="register" value="1">
        Зарегистрироваться
    </button>

</form>

<?php endif; ?>


	</div>

</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
