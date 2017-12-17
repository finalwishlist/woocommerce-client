<?php
include_once(plugin_dir_path(__FILE__) . '../includes/class.finalwishlist.wcwl.php');

function add_to_finalwishlist($prod_id, $wishlist_id, $user_id )
{
    if(is_user_logged_in()&& $_POST['finalwishlist'] == 1){
        $data = array();
        $helper = Finalwishlist_Helper::getSingletonIstance();
        $data['customerid'] = $user_id;
        $data['customer_name'] = $helper->getUserName($user_id);
        $data['email'] = $helper->getUserEmail($user_id);
        $data['item_id'] = $prod_id;
        $data['product_name'] = $helper->getProductName($prod_id);
        $data['product_link'] = $helper->getProductLink($prod_id);
        $data['image_link'] = $helper->getProductImageLink($prod_id);

        $connection = Finalwishlist_Connection::getSingletonIstance();
        $responseJson = $connection->addItem($data);
        $response = json_decode($responseJson['body']);
        $finalWishlistYith = new FW_YITH_WCWL();
        $finalWishlistYith->add_to_wishlist_ajax($response,$user_id);
        die();
    }
}
add_action( 'yith_wcwl_added_to_wishlist', 'add_to_finalwishlist',10,3);
 
