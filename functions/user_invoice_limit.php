<?php
add_action('show_user_profile', 'arismed_user_invoice_limit_field');
add_action('edit_user_profile', 'arismed_user_invoice_limit_field');

function arismed_can_create_invoice(int $user_id): bool
{
    $today = date('Y-m-d');

    $count = (int) get_user_meta($user_id, 'arismed_invoice_count', true);
    $date  = get_user_meta($user_id, 'arismed_invoice_date', true);
    $limit = arismed_get_invoice_limit($user_id);

    if ($limit === 0) {
        return false;
    }

    if ($date !== $today) {
        update_user_meta($user_id, 'arismed_invoice_count', 0);
        update_user_meta($user_id, 'arismed_invoice_date', $today);
        $count = 0;
    }

    return $count < $limit;
}

function arismed_get_invoice_limit(int $user_id): int
{
    $limit = (int) get_user_meta($user_id, 'arismed_invoice_limit', true);
    if ($limit === 0) {
        return 0;
    }
    return $limit > 0 ? $limit : 3;
}

function arismed_inc_invoice_counter(int $user_id): void
{
    $today = date('Y-m-d');

    $count = (int) get_user_meta($user_id, 'arismed_invoice_count', true);
    $date = get_user_meta($user_id, 'arismed_invoice_date', true);

    if ($date !== $today) {
        update_user_meta($user_id, 'arismed_invoice_count', 0);
        update_user_meta($user_id, 'arismed_invoice_date', $today);
    }

    update_user_meta($user_id, 'arismed_invoice_count', $count + 1);
}
function arismed_user_invoice_limit_field($user)
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $limit = get_user_meta($user->ID, 'arismed_invoice_limit', true);
    if ($limit === '') {
        $limit = 3;
    }
    ?>
    <h3>Счета</h3>
    <table class="form-table">
        <tr>
            <th><label for="arismed_invoice_limit">Лимит счетов в день</label></th>
            <td>
                <input type="number" min="0" max="100" name="arismed_invoice_limit" id="arismed_invoice_limit"
                    value="<?php echo esc_attr($limit); ?>" min="0" step="1" class="regular-text" />
                <p class="description">
                    Количество счетов, которые пользователь может сформировать за день.
                    0 — запретить полностью.
                </p>
            </td>
        </tr>
    </table>
    <?php
}

add_action('personal_options_update', 'arismed_save_user_invoice_limit');
add_action('edit_user_profile_update', 'arismed_save_user_invoice_limit');

function arismed_save_user_invoice_limit($user_id)
{
    if (!current_user_can('manage_options')) {
        return;
    }

    if (!isset($_POST['arismed_invoice_limit'])) {
        return;
    }

    $limit = max(0, (int) $_POST['arismed_invoice_limit']);
    update_user_meta($user_id, 'arismed_invoice_limit', $limit);
}

