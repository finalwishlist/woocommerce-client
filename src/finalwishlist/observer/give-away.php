<?php


function save_affiliation()
{
    if(isset($_REQUEST['fwaffiliation']) && !is_admin()){
        WC()->session->set( 'fwaffiliation' , $_REQUEST['fwaffiliation'] );
    }
    return true;

}
function bought_to_finalwishlist($orderid){
    if( $affiliationData = WC()->session->get( 'fwaffiliation')){
        $data = array();
        $decodedAffiliationData = unserialize(base64_decode($affiliationData));
        $order = new WC_Order( $orderid );
        if(isset($decodedAffiliationData['customerid']) && ($customer = get_user_by( 'id', $decodedAffiliationData['customerid'] ))){
            $data['who_gave_customerid'] = ($order->get_user_id()) ? $order->get_user_id() : true;
            $data['customerid'] = $customer->ID;
            $data['email'] = $customer->user_email;
            $data['item_id'] = $decodedAffiliationData['item_id'];
            $helper = Finalwishlist_Helper::getSingletonIstance();
            $data['product_name'] = $helper->getProductName($decodedAffiliationData['item_id']);
            $connection = Finalwishlist_Connection::getSingletonIstance();
            if($connection->boughtItem($data)){
                _e( sprintf('%s marked as gifted to Finalwishlist.com',$data['product_name']), 'woocommerce' );
                WC()->session->__unset( 'fwaffiliation');
            }else{
                _e('An error occurred while send boughtItem to Finalwishlist.com', 'woocommerce' );
            }
            return true;
        }
        return false;


    }
}

add_action( 'init', 'save_affiliation');
add_action('woocommerce_thankyou','bought_to_finalwishlist') ;
