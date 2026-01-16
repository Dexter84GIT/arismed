<?php
defined('ABSPATH') || exit;

$user_id = get_current_user_id();
$user = $user_id ? get_userdata($user_id) : null;

$account_first_name = $user ? $user->first_name : '';
$account_last_name = $user ? $user->last_name : '';
$account_email = $user ? $user->user_email : '';
$billing_phone = $user_id ? (string) get_user_meta($user_id, 'billing_phone', true) : '';
?>

<div class="tabContent df fdc gap30" id="data">
    <div class="top df fdc gap20">
        <h2 class="sectionTitle druk">Личные данные</h2>
        <form class="woocommerce-EditAccountForm edit-account checkoutForm content df fdc gap60"
              action=""
              method="post"
              <?php do_action('woocommerce_edit_account_form_tag'); ?>
        >
            <?php do_action('woocommerce_edit_account_form_start'); ?>
        
            <div class="block df fdc gap20">
                <div class="row row2 df aic gap20">
                    <div class="field">
                        <input type="text"
                               class="textInput"
                               name="account_first_name"
                               autocomplete="given-name"
                               placeholder="Имя"
                               value="<?php echo esc_attr($account_first_name); ?>">
                    </div>
                    <div class="field">
                        <input type="text"
                               class="textInput"
                               name="account_last_name"
                               autocomplete="family-name"
                               placeholder="Фамилия"
                               value="<?php echo esc_attr($account_last_name); ?>">
                    </div>
                </div>
        
                <div class="row row2 df aic gap20">
                    <div class="field">
                        <input type="email"
                               class="textInput"
                               name="account_email"
                               autocomplete="email"
                               placeholder="E-mail"
                               value="<?php echo esc_attr($account_email); ?>"
                               required>
                    </div>
                    <div class="field">
                        <input type="tel"
                               class="textInput"
                               name="billing_phone"
                               autocomplete="tel"
                               placeholder="Телефон"
                               value="<?php echo esc_attr($billing_phone); ?>">
                    </div>
                </div>
            </div>
        
            <div class="block df fdc gap30">
                <h3 class="druk">Смена пароля</h3>
        
                <div class="block changePassword df fdc gap20">
                    <div class="row row2 df aic gap20">
                        <div class="field">
                            <input type="password"
                                   class="textInput"
                                   name="password_current"
                                   autocomplete="current-password"
                                   placeholder="Старый пароль">
                        </div>
                    </div>
        
                    <div class="row row2 df aic gap20">
                        <div class="field">
                            <input type="password"
                                   class="textInput"
                                   name="password_1"
                                   autocomplete="new-password"
                                   placeholder="Пароль">
                        </div>
                        <div class="field">
                            <input type="password"
                                   class="textInput"
                                   name="password_2"
                                   autocomplete="new-password"
                                   placeholder="Подтверждение пароля">
                        </div>
                    </div>
                </div>
            </div>
        
            <?php wp_nonce_field('save_account_details', 'save-account-details-nonce'); ?>
            <input type="hidden" name="action" value="save_account_details">
        
            <?php do_action('woocommerce_edit_account_form'); ?>
        
            <button type="submit" class="accountBtn df aic gap10" name="save_account_details" value="1">
                <div class="img df aic jcc">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.75 2.25H3.75C2.9175 2.25 2.25 2.925 2.25 3.75V14.25C2.25 15.075 2.9175 15.75 3.75 15.75H14.25C15.075 15.75 15.75 15.075 15.75 14.25V5.25L12.75 2.25ZM14.25 14.25H3.75V3.75H12.1275L14.25 5.8725V14.25ZM9 9C7.755 9 6.75 10.005 6.75 11.25C6.75 12.495 7.755 13.5 9 13.5C10.245 13.5 11.25 12.495 11.25 11.25C11.25 10.005 10.245 9 9 9ZM4.5 4.5H11.25V7.5H4.5V4.5Z" fill="white"/>
                    </svg>
                </div>
                <p class="druk">Сохранить изменения</p>
            </button>
        
            <?php do_action('woocommerce_edit_account_form_end'); ?>
        </form>
    </div>
    <div class="content df fdc gap20"></div>
</div>