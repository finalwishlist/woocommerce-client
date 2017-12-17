<?php

/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */

/**
 * custom option and settings
 */
function finalwishlist_settings_init() {
 // register a new setting for "finalwishlist" page
 register_setting( 'finalwishlist', 'finalwishlist_sandboxmode' );
 register_setting( 'finalwishlist', 'finalwishlist_api_id' );
 register_setting( 'finalwishlist', 'finalwishlist_api_key' );

 // register a new section in the "finalwishlist" page
 add_settings_section(
 'finalwishlist_section_developers',
 __( 'Final wishlist settings', 'finalwishlist' ),
 'finalwishlist_section_developers_cb',
 'finalwishlist'
 );

 // register a new field in the "finalwishlist_section_developers" section, inside the "finalwishlist" page
 add_settings_field(
 'finalwishlist_field_sandbox', // as of WP 4.6 this value is used only internally
 // use $args' label_for to populate the id inside the callback
 __( 'Sandbox', 'finalwishlist' ),
 'finalwishlist_field_api_keys',
 'finalwishlist',
 'finalwishlist_section_developers',
 [
 'label_for' => 'finalwishlist_field_sandbox',
 'class' => 'finalwishlist_row',
 'finalwishlist_custom_data' => 'custom',
 ]
 );
}

/**
 * register our finalwishlist_settings_init to the admin_init action hook
 */
add_action( 'admin_init', 'finalwishlist_settings_init' );

/**
 * custom option and settings:
 * callback functions
 */

// developers section cb

// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function finalwishlist_section_developers_cb( $args ) {
 ?>
 <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'If you still haven\'t it register on Finalwishlist site, then back here and fill with API ID and API KEY', 'finalwishlist' ); ?></p>
 <?php
}

function finalwishlist_field_api_keys( $args ) {
 // get the value of the setting we've registered with register_setting()
 $options = get_option( 'finalwishlist_sandboxmode' );
 // output the field
 ?>
 <select id="<?php echo esc_attr( $args['label_for'] ); ?>"
 data-custom="<?php echo esc_attr( $args['finalwishlist_custom_data'] ); ?>"
 name="finalwishlist_sandboxmode[<?php echo esc_attr( $args['label_for'] ); ?>]"
 >
 <option value="1" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], '1', false ) ) : ( '' ); ?>>
 <?php esc_html_e( 'Yes', 'finalwishlist' ); ?>
 </option>
 <option value="0" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], '0', false ) ) : ( '' ); ?>>
 <?php esc_html_e( 'No', 'finalwishlist' ); ?>
 </option>
 </select>
 <p class="description">
 <?php esc_html_e( 'API ID', 'finalwishlist' ); ?>
 </p>
    <?php  $api_id = get_option( 'finalwishlist_api_id' ); ?>
<input name="finalwishlist_api_id" type="password" value="<?php echo $api_id ?>" />
<p class="description">
<?php esc_html_e( 'API KEY', 'finalwishlist' ); ?>
</p>
    <?php  $api_key = get_option( 'finalwishlist_api_key' ); ?>

<input name="finalwishlist_api_key" type="password" value="<?php echo $api_key ?>" />
 <?php
}

/**
 * top level menu
 */
function finalwishlist_apis_page() {
 // add top level menu page
 add_menu_page(
 'FinalWishlist',
 'Final Wishlist',
 'manage_options',
 'finalwishlist',
 'finalwishlist_apis_page_html'
 );
}

/**
 * register our finalwishlist_apis_page to the admin_menu action hook
 */
add_action( 'admin_menu', 'finalwishlist_apis_page' );

/**
 * top level menu:
 * callback functions
 */
function finalwishlist_apis_page_html() {
 // check user capabilities
 if ( ! current_user_can( 'manage_options' ) ) {
 return;
 }

 // add error/update messages

 // check if the user have submitted the settings
 // wordpress will add the "settings-updated" $_GET parameter to the url
 if ( isset( $_GET['settings-updated'] ) ) {
 // add settings saved message with the class of "updated"
 add_settings_error( 'finalwishlist_messages', 'finalwishlist_message', __( 'Settings Saved', 'finalwishlist' ), 'updated' );
 }

 // show error/update messages
 settings_errors( 'finalwishlist_messages' );
 ?>
 <div class="wrap">
 <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
 <form action="options.php" method="post">
 <?php
 // output security fields for the registered setting "finalwishlist"
 settings_fields( 'finalwishlist' );
 // output setting sections and their fields
 // (sections are registered for "finalwishlist", each field is registered to a specific section)
 do_settings_sections( 'finalwishlist' );
 // output save settings button
 submit_button( 'Save Settings' );
 ?>
 </form>
 </div>
 <?php
}