<?php
define('YITH_WCWL',1);
require_once WP_PLUGIN_DIR.'/yith-woocommerce-wishlist/includes/class.yith-wcwl.php';

class FW_YITH_WCWL extends YITH_WCWL {

    /**
     * AJAX: add to wishlist action
     *
     * @return void
     * @since 1.0.0
     */
    public function add_to_wishlist_ajax($response = null) {
        $return = property_exists($response,'success')?"true":"error";
        $message = '';
        $user_id = isset( $this->details['user_id'] ) ? $this->details['user_id'] : false;
        $wishlists = array();

        if( $return == 'true' ){
            $message = apply_filters( 'yith_wcwl_product_added_to_wishlist_message', get_option( 'yith_wcwl_product_added_text' ) );
        }
        elseif( $return == 'exists' ){
            $message = apply_filters( 'yith_wcwl_product_already_in_wishlist_message', get_option( 'yith_wcwl_already_in_wishlist_text' ) );
        }
        elseif( count( $this->errors ) > 0 ){
            $message = apply_filters( 'yith_wcwl_error_adding_to_wishlist_message', $this->get_errors() );
        }

        if( $user_id != false ){
            $wishlists = $this->get_wishlists( array( 'user_id' => $user_id ) );
        }

        wp_send_json(
            array(
                'result' => $return,
                'message' => $message,
                'user_wishlists' => $wishlists,
                'final_wishlist_url' => $response->finalwishlist_url,
                'wishlist_url' => $this->get_wishlist_url( 'view' . ( isset( $this->last_operation_token ) ? ( '/' . $this->last_operation_token ) : false ) ),
            )
        );
    }
}