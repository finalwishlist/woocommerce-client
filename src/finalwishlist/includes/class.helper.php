<?php
if ( ! defined( 'Finalwishlist_Helper' ) ) {
    exit;
} // Exit if accessed directly

if ( ! class_exists( 'Finalwishlist_Helper' ) ) {

    class Finalwishlist_Helper
    {
        private static $instance = null;

        private $_product;
        private $_user;

        public static function getSingletonIstance()
        {
            if (self::$instance == null) {
                $c = __CLASS__;
                self::$instance = new $c;
            }

            return self::$instance;
        }

        private function _getProductById($productId){
            if(!isset($this->_product)){
                $_pf = new WC_Product_Factory();
                $this->_product = $_pf->get_product($productId);
            }
            return $this->_product;
        }

        private function _getUserById($userId){
            if(!isset($this->_user)){
                $this->_user = get_userdata($userId);
            }
            return $this->_user;
        }

        public function getUserName($userId){
            $user = $this->_getUserById($userId);
            return $user->user_firstname." ".$user->user_lastname;
        }

        public function getProductName($productId){
            $product = $this->_getProductById($productId);
            return $product->get_title();

        }

        public function getProductLink($productId){
            $product = $this->_getProductById($productId);
            return $product->get_permalink();

        }

        public function getProductImageLink($productId){
            $product = $this->_getProductById($productId);
            return $product->get_image();
        }

        public function getUserEmail($userId){
            $user = $this->_getUserById($userId);
            return $user->user_email();
        }

    }
}