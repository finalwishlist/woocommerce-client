<?php
/*
Plugin Name: Final wishlist
URI: Url del plugin
Description: plugin to have a main wishlist other than in Woocommerce
Author: Finalwishlist.com Version: 0.1.0
*/
function wp_add_yith_extended_js()
{
    wp_register_style( 'jquery-modal-style', plugins_url( 'assets/css/jquery.modal.min.css', __FILE__ ), array());
    wp_enqueue_style('jquery-modal-style');
    if(is_user_logged_in()){
        // Register the script like this for a plugin:
        wp_register_style( 'alert-style', plugins_url( 'assets/css/alert-style.css', __FILE__ ), array());
        wp_register_script( 'jquery-modal-js', plugins_url( 'assets/js/jquery.modal.min.js', __FILE__ ), array('jquery'));
        wp_register_script('yith-extended', plugins_url('assets/js/yith-extended.js', __FILE__), array('jquery','jquery-yith-wcwl'));

        wp_enqueue_script('jquery-modal-js');
        wp_enqueue_script('yith-extended');
        wp_enqueue_style( 'alert-style' );
    }

}

add_action('wp_enqueue_scripts', 'wp_add_yith_extended_js');
define('Finalwishlist_Helper', true);
define('Finalwishlist_Connection', true);

include_once(plugin_dir_path(__FILE__) . 'plugin-options/api-credentials.php');
include_once(plugin_dir_path(__FILE__) . 'includes/class.helper.php');
include_once(plugin_dir_path(__FILE__) . 'includes/class.connection.php');
include_once(plugin_dir_path(__FILE__) . 'observer/added.php');
include_once(plugin_dir_path(__FILE__) . 'observer/removed.php');
include_once(plugin_dir_path(__FILE__) . 'observer/give-away.php');
include_once(plugin_dir_path(__FILE__) . 'templates/finalwishlist-alert.php');

