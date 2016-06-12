<?php
class ClsCheckout {
    
    private $_USER_ID = null;
    
    private $_SHIPPING_ADDRESS_ID = null;
    
    private $_SHIPPING_METHOD_NAME = null;
    
    private $_PAYMENT_BILLING_ADDRESS_ID = null;
    
    private $_PAYMENT_METHOD_NAME = null;
    
    private $_ORDER_COUPON_CODE = null;
    
    private $_SHOPPING_CART = null;
    
    private $_CURRENT_CURRENCY = null;
    
    private $_PAYMENT_TRAN_ID = null;
    
    public static $_checkout_session_var = "checkout";
    
    public static function setCheckout( $checkout ){
        $var = ClsCheckOut::$_checkout_session_var;
        $_SESSION[$var] = serialize( $checkout );
    }
    
    public static function getCheckout( $sessionId = null ){
        $var = ClsCheckOut::$_checkout_session_var;
        if( isset( $_SESSION[$var] ) && $_SESSION[$var] != "" ) {
        	$checkout = unserialize( $_SESSION[$var] );
        	if( $checkout instanceof ClsCheckOut  ) {
        		return $checkout;
        	}
        }elseif( $sessionId != null ){
            
            $result = Hqw::getApplication()->getModels( "orders_session" )->where( array( 'session_key'=>$sessionId ) )->fetch();
            if( $result && isset( $result['value'] ) && (int)$result['expiry'] > Hqw::getApplication()->getComponent("Date")->cTime() ) {
                return unserialize( $result['value'] );
            }
        }
        
        $signin = ClsFactory::instance("ClsSignin");
        $signin->checkUser();
        $userId = $signin->getUser()->getUserId();
        $checkout = new ClsCheckOut();
        $checkout->setUserId( $userId );
        ClsCheckOut::setCheckout( $checkout );
        return $checkout;
    }
    
    public static function cleanCheckout(){
        $var = ClsCheckOut::$_checkout_session_var;
        $_SESSION[$var] = "";
    }
    
    public function setUserId( $userId ){
        $this->_USER_ID = $userId;
    }
    
    public function getUserId(){
        return $this->_USER_ID;
    }
    
    public function setShippingAddress( $addressId ){
        $this->_SHIPPING_ADDRESS_ID = $addressId;
    }
    
    public function getShippingAddress(){
        return $this->_SHIPPING_ADDRESS_ID;
    }
    
    public function setShippingMethod( $method ){
        $this->_SHIPPING_METHOD_NAME = $method;
    }
    
    public function getShippingMethod(){
        return $this->_SHIPPING_METHOD_NAME;
    }
    
    public function setBillingAddress( $addressId ){
        $this->_PAYMENT_BILLING_ADDRESS_ID = $addressId;
    }
    
    public function getBillingAddress(){
        return $this->_PAYMENT_BILLING_ADDRESS_ID;
    }
    
    public function setPaymentMethod( $method ){
        $this->_PAYMENT_METHOD_NAME = $method;
    }
    
    public function getPaymentMethod(){
        return $this->_PAYMENT_METHOD_NAME;
    }
    
    public function getCouponCode(){
        return $this->_ORDER_COUPON_CODE;
    }
    
    public function setCouponCode( $code ){
        $this->_ORDER_COUPON_CODE = $code;
    }
    
    public function getShoppingCart(){
        return $this->_SHOPPING_CART;
    }
    
    public function setShoppingCart( $value ){
        $this->_SHOPPING_CART = $value;
    }
    
    public function getCurrency(){
        return $this->_CURRENT_CURRENCY;
    }
    
    public function setCurrency( $value ){
        $this->_CURRENT_CURRENCY = $value;
    }
    
    public function getPaymentTranId(){
        return $this->_PAYMENT_TRAN_ID;
    }
    
    public function setPaymentTranId( $value ){
        $this->_PAYMENT_TRAN_ID = $value;
    }
    
    public function getCouponValue(){
        if( $this->getCouponCode() != null ) {
        	$clsCoupon = ClsFactory::instance("ClsCoupon");
        	$coupon = $clsCoupon->getCoupon( $this->getCouponCode() );
        	if( $coupon['free_shipping'] == "N" ) {
        		$sc = $this->getShoppingCart();
        		$total = $sc->getCheckoutSubtotal();
        		if( $total > floatval( $coupon['min_order'] ) &&  floatval( $coupon['amount'] ) > 0 ) {
        			return round( $total * ( floatval( $coupon['amount'] ) / 100 ), 2 );
        		}
        	}
        }
        return false;
    }
    
    public function getShippingFee(){
        if( $this->getShippingMethod() != null ) {
        	$ce = ClsFactory::instance("ClsExpress");
        	$methods = $ce->getShippingMethod();
        	if( count($methods) > 0 ) {
        	    foreach( $methods as $k => $v ) {
        	        if( $v->isEnabled() === false ) {
                    	continue;
                    }
                    
                    if( $v->getTitle() == $this->getShippingMethod() ) {
                        $sc = $this->getShoppingCart();
                        $weight = $sc->getCheckoutWeight();
                    	return $v->getShippingCost( $weight );
                    }
        	    }
        	}
        }
        
        return false;
    }
    
    public function getCheckoutTotal(){
        $sc = $this->getShoppingCart();
        return $sc->getCheckoutSubtotal() + $this->getShippingFee() - $this->getCouponValue();
    }
    
    public function getEstimatedTime( $t=false ){
        if( $this->getShippingMethod() != null ) {
        	$ce = ClsFactory::instance("ClsExpress");
        	$methods = $ce->getShippingMethod();
        	if( count($methods) > 0 ) {
        	    foreach( $methods as $k => $v ) {
        	        if( $v->isEnabled() === false ) {
                    	continue;
                    }
                    
                    if( $v->getTitle() == $this->getShippingMethod() ) {
                    	$deliveryDay = $v->getDeliveryDay();
                    	if( $deliveryDay ) {
                    		if( ( $pos = strpos( $deliveryDay, "-" ) ) !== false ) {
                    		    $dC = Hqw::getApplication()->getComponent("Date");
                    			return date("D m/d/Y", $dC->businessDays( $t, substr( $deliveryDay, 0, $pos ) ) ) . "- " . date("D m/d/Y", $dC->businessDays( $t, substr( $deliveryDay, $pos+1 ) ) );
                    		}
                    	}
                    }
        	    }
        	}
        }
        
        return false;
    }
    
    
    private function getOrdersNumber(){
        $t = Hqw::getApplication()->getComponent("Date")->cTime();
        return date( 'Ymd', $t ) . substr( microtime(), 2, 5 );
    }
    
    
    public function createOrder(){
        //table orders, orders_products, orders_products_attributes, orders_status_comments
        $orderNumber = $this->getOrdersNumber();
        error_log( "order number :" . $orderNumber, 3,  "order.log");
        $data = $this->dataOrder( $orderNumber );
        error_log( "insert order data : " . json_encode( $data ), 3,  "order.log");
        if( Hqw::getApplication()->getModels( "orders" )->insert( $data ) ){
            error_log( "insert order success", 3,  "order.log");
            $orderId = Hqw::getApplication()->getModels( "orders" )->lastInsertId();
            error_log( "insert order id : " . $orderId, 3,  "order.log");
            $this->handleOrderProducts( $orderId );
            
            $comment = array();
            $comment['orders_id'] = $orderId;
            $comment['comments'] = $this->getPaymentMethod() . ' - order NO: ' . $orderNumber . ' - total: ' . $this->getCheckoutTotal();
            $comment['status_added_date'] = Hqw::getApplication()->getComponent("Date")->cDate( "s" );
            Hqw::getApplication()->getModels( "orders_status_comments" )->insert( $comment );
        }
        
        return array( $orderId, $orderNumber );
    }
    
    
    private function dataOrder( $orderNumber ){
        $common = ClsFactory::instance( "ClsCommon" );
        
        $currency = $this->getCurrency();
        $sc = $this->getShoppingCart();
        error_log( "insert order data currency : " . serialize( $currency ), 3,  "order.log");
        error_log( "insert order data shopping cart: " . serialize( $sc ), 3,  "order.log");
        
        $data = array();
        $data['orders_number'] = $orderNumber;
        $data['tran_id'] = $this->getPaymentTranId();
        $data['currency'] = $currency->getCurrency();
        $data['currency_value'] = $currency->getCurrencyExchange();
        $data['orders_subtotal'] = $sc->getCheckoutSubtotal();
        $data['payment_method'] = $this->getPaymentMethod();
        $data['shipping_method'] = $this->getShippingMethod();
        $data['shipping_total'] = $this->getShippingFee();
        
        error_log( "1 insert order data : " . json_encode( $data ), 3,  "order.log");
        
        if( $this->getCouponCode() != null ) {
        	$data['coupon_code'] = $this->getCouponCode();
        	$data['coupon_total'] = $this->getCouponValue();
        }
        
        $data['orders_total'] = $this->getCheckoutTotal();
        $data['user_id'] = $this->getUserId();
        
        $currentUser = ClsUserFactory::instance( $this->getUserId() );
        
        $shippingId = $this->getShippingAddress();
        $shippingAddress = $currentUser->getAddressById( $shippingId );
        if( !empty( $shippingAddress ) ) {
        	$data['shipping_name'] = $shippingAddress['full_name'];
        	$data['shipping_company'] = $shippingAddress['company'];
        	$data['shipping_street_address'] = $shippingAddress['street_address'];
        	$data['shipping_address_line'] = $shippingAddress['address_line'];
        	$data['shipping_city'] = $shippingAddress['city'];
        	
        	$data['shipping_state'] = isset( $shippingAddress['state'] ) && $shippingAddress['state'] != '' ? $shippingAddress['state'] : $common->getZones( $shippingAddress['state_id'] );
        	$data['shipping_country'] = $common->getCountries( $shippingAddress['country_id'] );
        	
        	$data['shipping_postcode'] = $shippingAddress['postcode'];
        	$data['shipping_phone_number'] = $shippingAddress['phone_number'];
        }
        error_log( "2 insert order data : " . json_encode( $data ), 3,  "order.log");
        
        $base = $currentUser->getBase();
        if( !empty( $base ) ) {
        	$data['email_address'] = $base['user_email_address'];
        }
        
        
        $billingId = $this->getBillingAddress();
        $billingAddress = $currentUser->getAddressById( $billingId );
        
        if( !empty( $billingAddress ) ) {
            $data['billing_name'] = $billingAddress['full_name'];
        	$data['billing_company'] = $billingAddress['company'];
        	$data['billing_street_address'] = $billingAddress['street_address'];
        	$data['billing_address_line'] = $billingAddress['address_line'];
        	$data['billing_city'] = $billingAddress['city'];
        	
        	$data['billing_state'] = isset( $billingAddress['state'] ) && $billingAddress['state'] != '' ? $billingAddress['state'] : $common->getZones( $billingAddress['state_id'] );
        	$data['billing_country'] = $common->getCountries( $billingAddress['country_id'] );
        	
        	$data['billing_postcode'] = $billingAddress['postcode'];
        }
        error_log( "3 insert order data : " . json_encode( $data ), 3,  "order.log");
        $data['ip_address'] = ip2long( Hqw::getApplication()->getRequest()->getUserHostAddress() );
        $data['purchased_date'] = Hqw::getApplication()->getComponent("Date")->cDate( "s" );
        $data['create_orders_date'] = $common->getDatetime();
        
        return $data;
    }
    
    private function handleOrderProducts( $orderId ){
        
        $sc = $this->getShoppingCart();
        $items = $sc->getCheckoutItems();
        
        $result = array();
        foreach( $items as $k => $v ) {
            $itemProducts = $v->getProducts();
            $baseProduct = $itemProducts->getBase();
            $item = array();
            $item['orders_id'] = $orderId;
            $item['products_id'] = $itemProducts->getProductsId();
            $item['products_name'] = $baseProduct['products_name'];
            $item['products_code'] = $baseProduct['products_code'];
            $item['products_price'] = $itemProducts->getSalesPrice();
            $item['products_quantity'] = $v->getQuantity();
            $item['final_price'] = $v->getProductsSales();
            $item['products_is_free'] = $itemProducts->isFree() == false ? 0 : 1;
            
            if( Hqw::getApplication()->getModels( "orders_products" )->insert( $item ) ){
                $orderProductsId = Hqw::getApplication()->getModels( "orders_products" )->lastInsertId();
                
                if( ( $attrs = $v->getProductsOptionsToValues() ) ){
                    if( !empty( $attrs ) ) {
                    	foreach( $attrs as $m => $n ) {
                    		$attr = array();
                            $attr['orders_id'] = $orderId;
                            $attr['orders_products_id'] = $orderProductsId;
                            $attr['orders_products_options_id'] = $n['products_options_id'];
                            $attr['orders_products_options_name'] = $n['products_options_name'];
                            $attr['orders_products_options_values_id'] = $n['products_options_values_id'];
                            $attr['orders_products_options_values_name'] = $n['products_options_values_name'];
                            $attr['orders_products_options_values_price'] = $n['products_options_values_price'];
                            $attr['price_prefix'] = $n['price_prefix'];
                            
                            Hqw::getApplication()->getModels( "orders_products_attributes" )->insert( $attr );
                    	}
                    }
                    
                }
            }
        }
    }
}
?>
