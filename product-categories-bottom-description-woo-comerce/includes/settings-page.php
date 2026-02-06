<?php

/**
 * Register plugin settings page under WooCommerce menu.
 */
add_action('admin_menu', function () {
    add_submenu_page(
        'woocommerce',
        __( 'Bottom Description Settings', 'pcbdw' ),
        __( 'Bottom Description', 'pcbdw' ),
        'manage_options',
        'pcbdw-settings',
        'pcbdw_render_settings_page'
    );
});



/**
 * Register settings to store CSS options.
 */
add_action('admin_init', function () {
    $sides = ['top', 'right', 'bottom', 'left'];
    foreach (['margin', 'padding'] as $type) {
        foreach ($sides as $side) {
            register_setting('pcbdw_settings_group', "pcbdw_{$type}_{$side}_value");
            register_setting('pcbdw_settings_group', "pcbdw_{$type}_{$side}_unit");
        }
    }

    register_setting('pcbdw_settings_group', 'pcbdw_max_width_value');
    register_setting('pcbdw_settings_group', 'pcbdw_max_width_unit');
    register_setting('pcbdw_settings_group', 'pcbdw_background_color');

    register_setting('pcbdw_settings_group', 'pcbdw_border_width');
    register_setting('pcbdw_settings_group', 'pcbdw_border_color');

    register_setting('pcbdw_settings_group', 'pcbdw_border_radius_value');
    register_setting('pcbdw_settings_group', 'pcbdw_border_radius_unit');
});



/**
 * Render plugin settings page with individual margin/padding controls and unit selectors,
 * plus max-width, background color, border and border-radius settings.
 */
function pcbdw_render_settings_page()
{
    $fields = [
        'margin'  => ['Top', 'Right', 'Bottom', 'Left'],
        'padding' => ['Top', 'Right', 'Bottom', 'Left'],
    ];
    $units = ['px', 'em', 'rem', '%'];
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Bottom Description Settings', 'pcbdw' ); ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields('pcbdw_settings_group'); ?>
            <table class="form-table">
                <?php foreach ($fields as $type => $sides): ?>
                    <tr><th colspan="2"><h2 style="margin:0;"><?php echo esc_html( ucfirst( translate( $type, 'pcbdw' ) ) ); ?></h2></th></tr>
                    <?php foreach ($sides as $side): 
                        $key = "pcbdw_{$type}_" . strtolower($side);
                        $val = get_option($key . '_value', '');
                        $unit = get_option($key . '_unit', 'px');
                    ?>
                        <tr>
                            <th scope="row"><?php echo esc_html( ucfirst( translate( $type, 'pcbdw' ) . ' ' . translate( $side, 'pcbdw' ) ) ); ?></th>
                            <td>
                                <input type="number" step="any" name="<?php echo $key . '_value'; ?>" value="<?php echo esc_attr($val); ?>" style="width:80px;" />
                                <select name="<?php echo $key . '_unit'; ?>">
                                    <?php foreach ($units as $u): ?>
                                        <option value="<?php echo $u; ?>" <?php selected($unit, $u); ?>><?php echo $u; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>

                <tr><th colspan="2"><h2 style="margin:0;"><?php esc_html_e( 'Other styles', 'pcbdw' ); ?></h2></th></tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Max width', 'pcbdw' ); ?></th>
                    <td>
                        <?php
                        $max_width_value = get_option('pcbdw_max_width_value', '');
                        $max_width_unit = get_option('pcbdw_max_width_unit', 'px');
                        ?>
                        <input type="number" step="any" name="pcbdw_max_width_value" value="<?php echo esc_attr($max_width_value); ?>" style="width:80px;" />
                        <select name="pcbdw_max_width_unit">
                            <?php foreach (['px', '%', 'em', 'rem'] as $unit): ?>
                                <option value="<?php echo $unit; ?>" <?php selected($max_width_unit, $unit); ?>><?php echo $unit; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Background color', 'pcbdw' ); ?></th>
                    <td>
                        <?php $bg_color = get_option('pcbdw_background_color', '#ffffff'); ?>
                        <input type="color" name="pcbdw_background_color" value="<?php echo esc_attr($bg_color); ?>" />
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Border', 'pcbdw' ); ?></th>
                    <td>
                        <?php
                        $border_width = get_option('pcbdw_border_width', '');
                        $border_color = get_option('pcbdw_border_color', '#000000');
                        ?>
                        <input type="number" name="pcbdw_border_width" value="<?php echo esc_attr($border_width); ?>" style="width:80px;" /> px
                        &nbsp;&nbsp;&nbsp;
                        <input type="color" name="pcbdw_border_color" value="<?php echo esc_attr($border_color); ?>" />
                        <span style="margin-left:10px;"><?php esc_html_e( '(solid)', 'pcbdw' ); ?></span>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Border radius', 'pcbdw' ); ?></th>
                    <td>
                        <?php
                        $radius_val = get_option('pcbdw_border_radius_value', '');
                        $radius_unit = get_option('pcbdw_border_radius_unit', 'px');
                        ?>
                        <input type="number" step="any" name="pcbdw_border_radius_value" value="<?php echo esc_attr($radius_val); ?>" style="width:80px;" />
                        <select name="pcbdw_border_radius_unit">
                            <?php foreach (['px', '%', 'em', 'rem'] as $unit): ?>
                                <option value="<?php echo $unit; ?>" <?php selected($radius_unit, $unit); ?>><?php echo $unit; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>

            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
