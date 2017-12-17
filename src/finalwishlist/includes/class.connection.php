<?php
if (!defined('Finalwishlist_Connection')) {
    exit;
} // Exit if accessed directly

if (!class_exists('Finalwishlist_Connection')) {

    class Finalwishlist_Connection
    {
        private static $instance = null;

        protected $_client;
        private $_credentials;
        private $_currentToken;

        const SANDBOX_WISHLIST_URL = 'http://sandbox.finalwishlist.com';
        const PRODUCTION_WISHLIST_URL = 'https://finalwishlist.com';

        public function __construct()
        {
            try {
                $this->_credentials = array();
                $this->_credentials['api_id'] = get_option('finalwishlist_api_id');
                $this->_credentials['api_key'] = get_option('finalwishlist_api_key');
                if (!$this->_client) {

                    $this->_client = array(
                        'method' => 'POST',
                        'timeout' => 45,
                        'maxredirects' => 0,
                        'headers' => array("Content-type" => "application/json;charset=UTF-8")
                    );
                }
                if ((WC()->session == null ) || !($this->_currentToken = WC()->session->get('finalwishlist_token'))) {
                    $this->_login();
                }
            } catch (Mage_Core_Exception $e) {
                $this->log(__METHOD__, $e->getMessage());
            }

        }

        public static function getSingletonIstance()
        {
            if (self::$instance == null) {
                $c = __CLASS__;
                self::$instance = new $c;
            }

            return self::$instance;
        }

        /**
         * @param $data
         * @return bool|mixed
         */
        private function _login()
        {
            $response = $this->_callAPI($this->_getFinalWishlistSite() . '/api/login', $this->_credentials);
            if($response instanceof WP_Error){
                $this->log(__METHOD__, 'Errore nell\'login ' .  $response->get_error_message());
                return $response;
            }
            if (isset($response['response']) &&
                isset($response['response']['code']) &&
                $response['response']['code'] == 200 &&
                property_exists(json_decode($response['body']), 'token')
            ) {
                if(!WC()->session){
                    WC()->init();
                }
                WC()->session->set('finalwishlist_token', json_decode($response['body'])->token);
                $this->_currentToken = WC()->session->get('finalwishlist_token');
                $this->_client['headers'] = array('Authorization' => 'Bearer ' . $this->_currentToken);
                return true;
            }
            throw new Exception('Wrong API credentials');
        }

        /**
         * @param mixed|false $data data to send
         * @return bool|mixed
         */
        private function _callAPI($url, $data = false)
        {
            if (!isset($this->_client)) {
                return false;

            }
            $client = $this->_client;
            $client['body'] = json_encode($data);
            return wp_remote_post($url, $client);
        }

        protected function log($method, $message)
        {
            error_log($method . "|" . $message);
        }

        public function addItem($data)
        {

            $response = $this->_callAPI($this->_getFinalWishlistSite() . '/api/wishlist/add', $data, 'additem');
            if($response instanceof WP_Error){
                $this->log(__METHOD__, 'Error occurs in insert item' .  $response->get_error_message());
                return $response;
            }
            if (isset($response['response']) &&
                isset($response['response']['code']) &&
                $response['response']['code'] == 200
            ) {
                return $response;
            }
            $this->_login();
            $response = $this->_callAPI($this->_getFinalWishlistSite() . '/api/wishlist/add', $data, 'additem');
            if($response instanceof WP_Error){
                $this->log(__METHOD__, 'Error occurs in insert item' .  $response->get_error_message());
                return $response;
            }
            if (isset($response['response']) &&
                isset($response['response']['code']) &&
                $response['response']['code'] == 200
            ) {
                return $response;
            }
            $this->log(__METHOD__, 'Error occurs in insert item' . json_decode($response['body']));
            return false;
        }

        public function removeItem($data)
        {
            $response = $this->_callAPI($this->_getFinalWishlistSite() . '/api/wishlist/remove', $data, 'removeitem');
            if($response instanceof WP_Error){
                 $this->log(__METHOD__, 'Error occurs in remove item' .  $response->get_error_message());
                 return $response;
            }


            if (isset($response['response']) &&
                isset($response['response']['code']) &&
                $response['response']['code'] == 200
            ) {
                return $response;
            }
            $this->_login();
            $response = $this->_callAPI($this->_getFinalWishlistSite() . '/api/wishlist/remove', $data, 'removeitem');

            if($response instanceof WP_Error){
                 $this->log(__METHOD__, 'Error occurs in remove item' .  $response->get_error_message());
                 return $response;
            }

            if (isset($response['response']) &&
                isset($response['response']['code']) &&
                $response['response']['code'] == 200
            ) {
                return $response;
            }
            $this->log(__METHOD__, 'Error occurs in remove item' . json_decode($response['body']));
            return false;
        }

        public function boughtItem($data)
        {
            $response = $this->_callAPI($this->_getFinalWishlistSite() . '/api/wishlist/bought', $data, 'boughtitem');
            if($response instanceof WP_Error){
                 $this->log(__METHOD__, 'Error occurs in purchase item' .  $response->get_error_message());
                 return $response;
            }
            if (isset($response['response']) &&
                isset($response['response']['code']) &&
                $response['response']['code'] == 200
            ) {
                return $response;
            }
            $this->_login();
            $response = $this->_callAPI($this->_getFinalWishlistSite() . '/api/wishlist/bought', $data, 'boughtitem');
            if($response instanceof WP_Error){
                 $this->log(__METHOD__, 'Error occurs in purchase item' .  $response->get_error_message());
                 return $response;
            }

            if (isset($response['response']) &&
                isset($response['response']['code']) &&
                $response['response']['code'] == 200
            ) {
                return $response;
            }

            $this->log(__METHOD__, 'Error occurs in purchase item' . json_decode($response['body']));
            return false;
        }

        private function _getFinalWishlistSite()
        {
            if (get_option('finalwishlist_sandboxmode')) {
                return self::SANDBOX_WISHLIST_URL;
            } else {
                return self::PRODUCTION_WISHLIST_URL;
            }
        }
    }
}