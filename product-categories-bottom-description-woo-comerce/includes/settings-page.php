<?php

/**
 * Register plugin settings page under WooCommerce menu.
 */
add_action('admin_menu', function () {
    add_submenu_page(
        'woocommerce',
        'Bottom Description Settings',
        'Bottom Description',
        'manage_options',
        'pcbdw-settings',
        'pcbdw_render_settings_page'
    );
});

/**
 * Register settings to store CSS options.
 */
add_action('admin_init', function () {
    register_setting('pcbdw_settings_group', 'pcbdw_margin');
    register_setting('pcbdw_settings_group', 'pcbdw_padding');
});

/**
 * Render settings page HTML.
 */
function pcbdw_render_settings_page()
{
    ?>
    <div class="wrap">
        <h1>Bottom Description Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('pcbdw_settings_group'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="pcbdw_margin">Margin</label></th>
                    <td><input type="text" name="pcbdw_margin" id="pcbdw_margin" value="<?php echo esc_attr(get_option('pcbdw_margin', '2em 0')); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="pcbdw_padding">Padding</label></th>
                    <td><input type="text" name="pcbdw_padding" id="pcbdw_padding" value="<?php echo esc_attr(get_option('pcbdw_padding', '0')); ?>" class="regular-text" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
