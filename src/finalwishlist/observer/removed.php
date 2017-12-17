<?php


function remove_from_finalwishlist()
{
    if(!isset($_REQUEST['remove_from_wishlist']) && !isset( $_REQUEST['remove_from_wishlist_after_add_to_cart']))
        return true;

    if(!yith_getcookie('remove_from_finalwishlist')){
        yith_setcookie('remove_from_finalwishlist',true);
        $yith_wcwl = YITH_WCWL();
    }else{
        yith_destroycookie('remove_from_finalwishlist');
        return true;
    }

    $connection = Finalwishlist_Connection::getSingletonIstance();


    if($_REQUEST['remove_from_wishlist']){
        $prod_id = ( isset( $yith_wcwl->details['remove_from_wishlist'] ) && is_numeric( $yith_wcwl->details['remove_from_wishlist'] ) ) ? $yith_wcwl->details['remove_from_wishlist'] : false;
        $user_id = ( ! empty( $yith_wcwl->details['user_id'] ) ) ? $yith_wcwl->details['user_id'] : get_current_user_id();

        $response = $connection->removeItem(array('customerid'=> $user_id,'item_id' =>$prod_id ));
        return true;
    }

    if( get_option( 'yith_wcwl_remove_after_add_to_cart' ) == 'yes' ) {
        if (isset($_REQUEST['remove_from_wishlist_after_add_to_cart'])) {

            $yith_wcwl->details['remove_from_wishlist'] = $_REQUEST['remove_from_wishlist_after_add_to_cart'];

            if (isset($_REQUEST['wishlist_id'])) {
                $yith_wcwl->details['wishlist_id'] = $_REQUEST['wishlist_id'];
            }
        } elseif (yith_wcwl_is_wishlist()) {
            $yith_wcwl->details['remove_from_wishlist'] = $_REQUEST['add-to-cart'];

            if (isset($_REQUEST['wishlist_id'])) {
                $yith_wcwl->details['wishlist_id'] = $_REQUEST['wishlist_id'];
            }
        }

        $prod_id = (isset($yith_wcwl->details['remove_from_wishlist']) && is_numeric($yith_wcwl->details['remove_from_wishlist'])) ? $yith_wcwl->details['remove_from_wishlist'] : false;
        $user_id = (!empty($yith_wcwl->details['user_id'])) ? $yith_wcwl->details['user_id'] : false;

        $connection->removeItem(array('customerid' => $user_id, 'item_id' => $prod_id));
    }

}

add_action( 'yith_wcwl_loaded', 'remove_from_finalwishlist');
 
