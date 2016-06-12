<?php
class CheckoutController extends Controller{
    
    private $_layout = "standardlayout";
    
    public function getLayouts() {
    	return $this->_layout;
    }
    
    public function ActionIndex(){/*{{{*/
        $sc = ClsFactory::instance("ClsShoppingCart");
        $signin = ClsFactory::instance("ClsSignin");
        $items = $sc->getCheckoutItems();
        
        if( count( $items ) == 0 ){
            //cart is empty
            Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('ShoppingCart/index') );
        }
        
        $signin->checkUser();
        Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('checkout/shipping_address') );
        exit;
    }/*}}}*/
    
    public function addressParams(){/*{{{*/
        $postData = array();
        $errorTips = array();
        if( isset( $_POST['full_name'] ) && $_POST['full_name'] != '' && strlen( trim( $_POST['full_name'] ) ) >= 2 && strlen( trim( $_POST['full_name'] ) ) <= 30 ) {
            $postData['full_name'] = $_POST['full_name'];
        }else{
            $errorTips[] = USER_FULL_NAME_TIPS;
        }
        
        $postData['company'] = $_POST['company'];
        if( isset( $_POST['street_address'] ) && $_POST['street_address'] != '' && strlen( trim( $_POST['street_address'] ) ) >= 6 && strlen( trim( $_POST['street_address'] ) ) <= 200 ) {
            $postData['street_address'] = $_POST['street_address'];
        }else{
            $errorTips[] = USER_STREET_ADDRESS_TIPS;
        }
        $postData['address_line'] = $_POST['address_line'];
        
        if( isset( $_POST['city'] ) && $_POST['city'] != '' && strlen( trim( $_POST['city'] ) ) >= 2 && strlen( trim( $_POST['city'] ) ) <= 50 ) {
            $postData['city'] = $_POST['city'];
        }else{
            $errorTips[] = USER_CITY_TIPS;
        }
        
        if( isset( $_POST['country_id'] ) && $_POST['country_id'] != 0 ) {
            $postData['country_id'] = $_POST['country_id'];
        }else{
            $errorTips[] = USER_COUNTRY_TIPS;
        }
        
        if( isset( $_POST['state_id'] ) && $_POST['state_id'] != 0 ) {
            $postData['state_id'] = $_POST['state_id'];
        }
        
        if( isset( $_POST['state'] ) && $_POST['state'] != '' ) {
            $postData['state'] = $_POST['state'];
        }
        
        if( isset( $_POST['postcode'] ) && $_POST['postcode'] != '' && strlen( trim( $_POST['postcode'] ) ) >= 2 && strlen( trim( $_POST['postcode'] ) ) <= 20 ) {
            $postData['postcode'] = $_POST['postcode'];
        }else{
            $errorTips[] = USER_POST_CODE_TIPS;
        }
        
        if( isset( $_POST['phone_number'] ) && $_POST['phone_number'] != '' && strlen( trim( $_POST['phone_number'] ) ) >= 2 && strlen( trim( $_POST['phone_number'] ) ) <= 30 ) {
            $postData['phone_number'] = $_POST['phone_number'];
        }else{
            $errorTips[] = USER_PHONE_NUMBER_TIPS;
        }
        
        return array( $errorTips, $postData );
    }/*}}}*/
    
    public function ActionShippingAddress(){/*{{{*/
        $data = array();
        $signin = ClsFactory::instance("ClsSignin");
        $signin->checkUser();
        $currentUser = $signin->getUser();
        $data['processUpdate'] = Hqw::getApplication()->createUrl('checkout/shipping_address', array( 'type'=>'process','action'=>'update' ) );
        $data['titleOne'] = CHECKOUT_SELECT_SHIPPING_ADDRESS;
        $data['descOne'] = CHECKOUT_SHIPPING_ADDRESS_DESC;
        $data['submitButton'] = CHECKOUT_SHIP_ADDRESS;
        $data['submitUrl'] = Hqw::getApplication()->createUrl('checkout/shipping_method');
        $data['titleTwo'] = CHECKOUT_ENTER_NEW_SHIPPING_ADDRESS;
        $data['processAddition'] = Hqw::getApplication()->createUrl('checkout/shipping_address', array( 'type'=>'process','action'=>'addition' ) );
        $data['submitController'] = "checkout/shipping_address";
        
        if( Hqw::getApplication()->getRequest()->getIsPostRequest() && isset( $_GET['type'] ) && strtolower( trim( $_GET['type'] ) ) == "process" ) {
            $userAddressModels = Hqw::getApplication()->getModels( "user_address_book" );
            if( $signin->getUser() !== false ){
                if( $_GET['action'] == "update" || $_GET['action'] == "addition" ){
                    $postData = array();
                    $errorTips = array();
                    list($errorTips, $postData) = $this->addressParams();
                    if( !empty( $errorTips ) ) {
                    	$errorTips = implode( "<br />", $errorTips );
                    }else{
                        
                        $postData = array_map( "trim", array_map( "htmlspecialchars",$postData ) );
                        
                        $defaultBook = 0;
                        if( isset( $_POST['user_address_default'] ) &&  $_POST['user_address_default'] == "1" ) {
                        	$defaultBook = 1;
                        }
                        
                        
                        if( isset( $_POST['user_address_book_id'] ) && $_POST['user_address_book_id'] != "0" ) {
                            $_GET['address_book_id'] = (int)$_POST['user_address_book_id'];
                        	$currentUser->updateAddress( (int)$_POST['user_address_book_id'], $postData, $defaultBook );
                            //$successTips = USER_CHANGED_ADDRESS_SUCCESS;
                        }else{
                            $addressNumber = $currentUser->getAddressNumber();
                            // max address number
                            if( (int)$addressNumber < ClsSettings::$MAX_ADDRESS ) {
                            	$bookId = $currentUser->postAddress( $postData, $defaultBook );
                            	if( $bookId ) {
                            		Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('checkout/shipping_method', array( 'type'=>'new_shipping_address', 'book'=>$bookId )) );
                            	}
                            	//$successTips = USER_POST_ADDRESS_SUCCESS;
                            }else{
                                $errorTips = USER_MAX_ADDRESS_NUMBER;
                            }
                        }
                    }
                    
                    
                }else{
                    if( isset( $_POST['user_address_book_id'] ) && $_POST['user_address_book_id'] != "0" ){
                        if( $userAddressModels->delete( array( 'user_id'=>$signin->getUser()->getUserId(), 'user_address_book_id'=>(int)$_POST['user_address_book_id'] ) ) ){
                            //$successTips = USER_ADDRESS_DELETE_SUCCESS;
                        }
                    }
                }
            }
        }elseif( isset( $_GET['type'] ) && $_GET['type'] == "edit" && isset( $_GET['address_book_id'] ) && $_GET['address_book_id'] != "" ){
            $data['addressBooks'] = Hqw::getApplication()->getModels( "user_address_book" )->fetch( array( 'user_id'=>$signin->getUser()->getUserId(), 'user_address_book_id'=>(int)$_GET['address_book_id'] ) );
        }
        
        $addressBook = Hqw::getApplication()->getModels( "user_address_book" );
        $data['result'] = $addressBook->fetchAll( array( 'user_id'=>$currentUser->getUserId() ) );
        $data['profile'] = $currentUser->getBase();
        
        $data['errorTips'] = $errorTips;
        $this->render( "shippingaddress", $data );
    }/*}}}*/
    
    public function ActionShippingMethod(){/*{{{*/
        
        $signin = ClsFactory::instance("ClsSignin");
        $signin->checkUser();
        $currentUser = $signin->getUser();
        
        $sc = ClsFactory::instance("ClsShoppingCart");
        $items = $sc->getCheckoutItems();
        
        if( count( $items ) == 0 ){
            //cart is empty
            Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('ShoppingCart/index') );
        }
        
        $checkout = ClsCheckout::getCheckout();
        $errorTips = array();
        $data = array();
        if( ( Hqw::getApplication()->getRequest()->getIsPostRequest() && isset( $_POST['address_book_id'] ) && $_POST['address_book_id'] != "" && $_POST['address_book_id'] != "0" ) || ( isset($_GET['type']) && $_GET['type']=='new_shipping_address' && isset( $_GET['book'] ) && $_GET['book'] != "0" ) || ( $checkout->getShippingAddress() != null && Hqw::getApplication()->getRequest()->getIsPostRequest() == false ) ) {
            
            if( isset( $_POST['address_book_id'] ) ){
                $addressBookId = (int)trim( $_POST['address_book_id'] );
            }elseif( isset( $_GET['book'] ) ){
                $addressBookId = (int)trim( $_GET['book'] );
            }
            if( $addressBookId == 0 ) {
            	Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('checkout/shipping_address') );
            	exit;
            }
            
            
            $checkout->setShippingAddress( $addressBookId );
            
            if( $checkout->getBillingAddress() == null ){
                $checkout->setBillingAddress( $addressBookId );
            }
            ClsCheckout::setCheckout( $checkout );
            
            $this->render( "shippingmethod", $data );
        }
        
        if( Hqw::getApplication()->getRequest()->getIsPostRequest() && isset( $_GET['type'] ) && strtolower( trim( $_GET['type'] ) ) == 'process' && isset( $_GET['action'] ) && strtolower( trim( $_GET['action'] ) ) == 'update' ) {
            
            if( isset( $_POST['key'] ) && trim( $_POST['key'] ) ) {
                
                $key = trim( $_POST['key'] );
                $ce = ClsFactory::instance("ClsExpress");
                $methods = $ce->getShippingMethod();
                
                $incMethod = false;
                if( !empty( $methods ) ) {
                	foreach( $methods as $k => $v ) {
                		if( $v->getTitle() == $key ) {
                			$incMethod = true;
                			break;
                		}
                	}
                }
                if( $incMethod == true ) {
                	$checkout = ClsCheckout::getCheckout();
                    $checkout->setShippingMethod( $key );
                    ClsCheckout::setCheckout( $checkout );
                    Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('checkout/payment') );
                }
                $errorTips = CHECKOUT_SHIPPING_METHOD_KEY;
            }else{
                $errorTips = CHECKOUT_SHIPPING_METHOD_KEY;
            }
            $data['errorTips'] = $errorTips;
            $this->render( "shippingmethod", $data );
        }
    }/*}}}*/
    
    public function ActionPayment(){/*{{{*/
        $signin = ClsFactory::instance("ClsSignin");
        $signin->checkUser();
        $currentUser = $signin->getUser();
        
        $data = array();
        $checkout = ClsCheckout::getCheckout();
        $shippingAddressId = (int)$checkout->getBillingAddress();
        $data['addressBooks'] = Hqw::getApplication()->getModels( "user_address_book" )->fetch( array( 'user_id'=>$signin->getUser()->getUserId(), 'user_address_book_id'=>$shippingAddressId ) );
        
        $data['paymentMethod'] = $checkout->getPaymentMethod();
        
        if( Hqw::getApplication()->getRequest()->getIsPostRequest() && isset( $_GET['type'] ) && strtolower( trim( $_GET['type'] ) ) == 'process' && isset( $_GET['action'] ) && strtolower( trim( $_GET['action'] ) ) == 'update' ) {
            if( isset( $_POST['payment'] ) && $_POST['payment'] != '' ) {
                $checkout->setPaymentMethod( trim( $_POST['payment'] ) );
                ClsCheckout::setCheckout( $checkout );
                Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('checkout/confirmation') );
            }else{
                $errorTips = CHECKOUT_PAYMENT_METHOD_NOTICE;
            }
        }
        $data['errorTips'] = $errorTips;
        $this->render( "payment", $data );
    }/*}}}*/
    
    public function ActionPaymentAddress(){/*{{{*/
        $data = array();
        $signin = ClsFactory::instance("ClsSignin");
        $signin->checkUser();
        $currentUser = $signin->getUser();
        
        $data['processUpdate'] = Hqw::getApplication()->createUrl('checkout/payment_address', array( 'type'=>'process','action'=>'update' ) );
        $data['titleOne'] = CHECKOUT_SELECT_BILLING_ADDRESS;
        $data['descOne'] = CHECKOUT_BILLING_ADDRESS_DESC;
        $data['submitButton'] = CHECKOUT_USE_ADDRESS;
        $data['submitUrl'] = Hqw::getApplication()->createUrl('checkout/payment_address', array( 'type'=>'process','action'=>'next' ));
        $data['titleTwo'] = CHECKOUT_ENTER_NEW_BILLING_ADDRESS;
        $data['processAddition'] = Hqw::getApplication()->createUrl('checkout/payment_address', array( 'type'=>'process','action'=>'addition' ) );
        $data['submitController'] = "checkout/payment_address";
        
        if( Hqw::getApplication()->getRequest()->getIsPostRequest() && isset( $_GET['type'] ) && strtolower( trim( $_GET['type'] ) ) == "process" ) {
            $userAddressModels = Hqw::getApplication()->getModels( "user_address_book" );
            if( $_GET['action'] == "update" || $_GET['action'] == "addition" ){
                $postData = array();
                $errorTips = array();
                list($errorTips, $postData) = $this->addressParams();
                if( !empty( $errorTips ) ) {
                	$errorTips = implode( "<br />", $errorTips );
                }else{
                    
                    $postData = array_map( "trim", array_map( "htmlspecialchars",$postData ) );
                    
                    $defaultBook = 0;
                    if( isset( $_POST['user_address_default'] ) &&  $_POST['user_address_default'] == "1" ) {
                    	$defaultBook = 1;
                    }
                    
                    if( isset( $_POST['user_address_book_id'] ) && $_POST['user_address_book_id'] != "0" ) {
                        $_GET['address_book_id'] = (int)$_POST['user_address_book_id'];
                    	$currentUser->updateAddress( (int)$_POST['user_address_book_id'], $postData, $defaultBook );
                        //$successTips = USER_CHANGED_ADDRESS_SUCCESS;
                    }else{
                        $addressNumber = $currentUser->getAddressNumber();
                        // max address number
                        if( (int)$addressNumber < ClsSettings::$MAX_ADDRESS ) {
                        	$bookId = $currentUser->postAddress( $postData, $defaultBook );
                        	if( $bookId ) {
                        	    $checkout = ClsCheckout::getCheckout();
                                $checkout->setBillingAddress( $bookId );
                                ClsCheckout::setCheckout( $checkout );
                        		Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('checkout/payment') );
                        	}
                        	//$successTips = USER_POST_ADDRESS_SUCCESS;
                        }else{
                            $errorTips = USER_MAX_ADDRESS_NUMBER;
                        }
                    }
                    
                }
            }elseif( $_GET['action'] == "next" ){
                if( isset( $_POST['address_book_id'] ) ){
                    $addressBookId = (int)trim( $_POST['address_book_id'] );
                }elseif( isset( $_GET['book'] ) ){
                    $addressBookId = (int)trim( $_GET['book'] );
                }
                if( $addressBookId == 0 ) {
                	Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('checkout/shipping_address') );
                	exit;
                }
                
                $checkout = ClsCheckout::getCheckout();
                $checkout->setBillingAddress( $addressBookId );
                ClsCheckout::setCheckout( $checkout );
        		Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('checkout/payment') );
            }else{
                if( isset( $_POST['user_address_book_id'] ) && $_POST['user_address_book_id'] != "0" ){
                    if( $userAddressModels->delete( array( 'user_id'=>$signin->getUser()->getUserId(), 'user_address_book_id'=>(int)$_POST['user_address_book_id'] ) ) ){
                        //$successTips = USER_ADDRESS_DELETE_SUCCESS;
                    }
                }
            }
        }elseif( isset( $_GET['type'] ) && $_GET['type'] == "edit" && isset( $_GET['address_book_id'] ) && $_GET['address_book_id'] != "" ){
            $data['addressBooks'] = Hqw::getApplication()->getModels( "user_address_book" )->fetch( array( 'user_id'=>$signin->getUser()->getUserId(), 'user_address_book_id'=>(int)$_GET['address_book_id'] ) );
        }
        
        $addressBook = Hqw::getApplication()->getModels( "user_address_book" );
        $data['result'] = $addressBook->fetchAll( array( 'user_id'=>$currentUser->getUserId() ) );
        $data['profile'] = $currentUser->getBase();
        
        $data['errorTips'] = $errorTips;
        $this->render( "shippingaddress", $data );
    }/*}}}*/
    
    public function ActionConfirmation(){/*{{{*/
        $data = array();
        $checkout = ClsCheckout::getCheckout();
        $signin = ClsFactory::instance("ClsSignin");
        $signin->checkUser();
        
        if( Hqw::getApplication()->getRequest()->getIsPostRequest() && isset( $_GET['type'] ) && strtolower( trim( $_GET['type'] ) ) == "process" && isset( $_GET['action'] ) && strtolower( trim( $_GET['action'] ) ) == "update" ) {
            if( isset( $_POST['coupon_code'] ) && trim( $_POST['coupon_code'] ) != "" ) {
                $clsCoupon = ClsFactory::instance("ClsCoupon");
            	$coupon = $clsCoupon->getCoupon( trim( $_POST['coupon_code'] ) );
            	
            	$couponCode = "";
            	if( $coupon != null ){
            	    $couponCode = $coupon['coupon_code'];
            	    
            	    $checkout = ClsCheckout::getCheckout();
                    $checkout->setCouponCode( $couponCode );
            	}            	 
            	
            	if( $couponCode == "" ) {
            		$errorTips = CHECKOUT_CONFIRM_ORDER_COUPON_VALID;
            	}
            }
        }
        
        $sc = ClsFactory::instance("ClsShoppingCart");
        $items = $sc->getCheckoutItems();
        
        if( count( $items ) == 0 ){
            //cart is empty
            Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('ShoppingCart/index') );
        }
        
        $checkout = ClsCheckout::getCheckout();
        $checkout->setShoppingCart( $sc );
        $data['items'] = $items;
        
        if( $checkout->getShippingAddress() == null ) {
        	Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('checkout/shipping_address') );
        }
        
        $shippingAddressId = (int)$checkout->getShippingAddress();
        $data['shippingAddress'] = Hqw::getApplication()->getModels( "user_address_book" )->fetch( array( 'user_id'=>$signin->getUser()->getUserId(), 'user_address_book_id'=>$shippingAddressId ) );
        
        if( $checkout->getShippingMethod() == null ) {
        	Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('checkout/shipping_method') );
        }
        $data['shippingMethod'] = $checkout->getShippingMethod();
        
        if( $checkout->getBillingAddress() == null || $checkout->getPaymentMethod() == null ) {
        	Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('checkout/payment') );
        }
        
        $data['paymentMethod'] = $checkout->getPaymentMethod();
        
        $billingAddressId = (int)$checkout->getBillingAddress();
        $data['billingAddress'] = Hqw::getApplication()->getModels( "user_address_book" )->fetch( array( 'user_id'=>$signin->getUser()->getUserId(), 'user_address_book_id'=>$billingAddressId ) );
        
        $data['errorTips'] = $errorTips;
        
        $currency = ClsFactory::instance( "ClsCurrency" );
        $currency->getCurrency();
        $checkout->setCurrency( $currency );
        ClsCheckout::setCheckout( $checkout );
        $this->render( "confirmation", $data );
    }/*}}}*/
    
    public function ActionHandleOrders(){/*{{{*/
        
        $data = array();
        
        $checkout = ClsCheckout::getCheckout();
        $signin = ClsFactory::instance("ClsSignin");
        $signin->checkUser();
        
        $sc = ClsFactory::instance("ClsShoppingCart");
        $items = $sc->getCheckoutItems();
        if( count( $items ) == 0 ){
            //cart is empty
            Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('ShoppingCart/index') );
        }
        
        $userId = $shippingId = $billingId = 0;
        if( ( $userId = (int)$checkout->getUserId() ) == null ) {
        	Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('ShoppingCart/index') );
        }
        
        if( ( $shippingId = (int)$checkout->getShippingAddress() ) == null ) {
        	Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('checkout/shipping_address') );
        }
        
        if( ( $shippingMethod = $checkout->getShippingMethod() ) == null ) {
        	Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('checkout/shipping_method') );
        }
        
        if( ( $billingId = (int)$checkout->getBillingAddress() ) == null || ( $paymentMethod = $checkout->getPaymentMethod() ) == null ) {
        	Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('checkout/payment') );
        }
        //order rebuild
        /*
        error_log( "create order", 3,  "order.log");
        list( $orderId, $orderNumber ) = $checkout->createOrder();
        error_log( "create order end\r\n", 3,  "order.log");
        
        //shopping cart clean
        foreach( $items as $k => $v ) {
        	$v->updateOrder( $orderId );
        }
        
        error_log( "create order end\r\n", 3,  "order.log");
        
        //check clean
        ClsCheckout::cleanCheckout();
        
        $clsOrders = ClsOrdersFactory::instance( $orderId );
        */
        
        //payment method form, include order id
        //rebuild products
        $clsPayments = ClsFactory::instance( "ClsPayment" );
        $payment = $clsPayments->getPayment( $paymentMethod );
        
        if( Hqw::getApplication()->getModels( "orders_session" )->where( array( 'session_key'=> $signin->getSessionId() ) )->fetch() ) {
            
            $sessOrder = array();
            $sessOrder['expiry'] = Hqw::getApplication()->getComponent("Date")->cTime() + 43200;
            $sessOrder['value'] = serialize( $checkout );
            Hqw::getApplication()->getModels( "orders_session" )->where( array( 'session_key'=> $signin->getSessionId() ) )->update( $sessOrder );
        }else{
            $sessOrder = array();
            $sessOrder['session_key'] = $signin->getSessionId();
            $sessOrder['expiry'] = Hqw::getApplication()->getComponent("Date")->cTime() + 43200;
            $sessOrder['value'] = serialize( $checkout );
            Hqw::getApplication()->getModels( "orders_session" )->insert( $sessOrder );
        }
        
        if( ( $html = $payment->processSubmit() ) === false ){
            Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('checkout/success', array( 'ref'=> strtolower( $payment->getTitle() ) ) ) );
        }else{
            echo $html;
        }
        exit;
        
    }/*}}}*/
    
    public function ActionSuccess(){/*{{{*/
        $data = array();
        
        $this->_layout = "layout";
        $ref = ( isset( $_GET['ref'] ) && $_GET['ref'] != '' ) ? $_GET['ref'] : "";
        if( $ref != "" ) {
            $data['ref'] = $ref;
            $clsPayments = ClsFactory::instance( "ClsPayment" );
        	if( ( $payment = $clsPayments->getPayment( $ref ) ) != false ){
        	    $data['payment'] = $payment;
        	    list( $status, $order ) = $payment->afterSubmit();
        	    $data['status'] = $status;
        	    $data['order'] = $order;
        	}
        }
        
        $this->render( "success", $data );
    }/*}}}*/
    
    public function ActionCancel(){
        echo "cancel";
    }
    
    
}

?>
