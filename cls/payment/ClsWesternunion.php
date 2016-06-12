<?php
class ClsWesternunion extends ClsPayment implements interfaceModule, interfacePayment {
    
    public function isEnabled(){/*{{{*/
        
        if( strtolower( $this->getValue( 'MODULE_PAYMENT_WESTERNUNION_STATUS' ) ) == "true" ){
            return true;
        }elseif( strtolower( $this->getValue( 'MODULE_PAYMENT_WESTERNUNION_STATUS' ) ) == "false" ){
            return false;
        }
        
        return "";
    }/*}}}*/
    
    public function install(){
        $data = array( 'config_settings_title'=>$this->getTitle(), 'config_settings_description'=>$this->getDescription(), 'sort_order'=>2, 'status'=>1 );
        if( Hqw::getApplication()->getModels( "config_settings" )->insert( $data ) ) {
    	    $configId = Hqw::getApplication()->getModels( "config_settings" )->lastInsertId();
    	    
    	    $t = Hqw::getApplication()->getComponent("Date")->cDate();
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'Enable Westernunion Module', 'config_settings_key_name'=>'MODULE_PAYMENT_WESTERNUNION_STATUS', 'config_settings_key_values'=>'True', "config_settings_key_description"=>'', 'sort_order'=>1, 'set_function'=>'eHtml::htmlRadioOption( "MODULE_PAYMENT_WESTERNUNION_STATUS", array( "True", "False" ) )', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'First Name', 'config_settings_key_name'=>'MODULE_PAYMENT_WESTERNUNION_FIRST_NAME', 'config_settings_key_values'=>'', "config_settings_key_description"=>'', 'sort_order'=>2, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'Last Name', 'config_settings_key_name'=>'MODULE_PAYMENT_WESTERNUNION_LAST_NAME', 'config_settings_key_values'=>'', "config_settings_key_description"=>'', 'sort_order'=>3, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'Address', 'config_settings_key_name'=>'MODULE_PAYMENT_WESTERNUNION_ADDRESS', 'config_settings_key_values'=>'', "config_settings_key_description"=>'', 'sort_order'=>5, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'Zip Code', 'config_settings_key_name'=>'MODULE_PAYMENT_WESTERNUNION_ZIP_CODE', 'config_settings_key_values'=>'0', "config_settings_key_description"=>'', 'sort_order'=>6, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'City', 'config_settings_key_name'=>'MODULE_PAYMENT_WESTERNUNION_CITY', 'config_settings_key_values'=>'', "config_settings_key_description"=>'', 'sort_order'=>7, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'Country', 'config_settings_key_name'=>'MODULE_PAYMENT_WESTERNUNION_COUNTRY', 'config_settings_key_values'=>'', "config_settings_key_description"=>'', 'sort_order'=>8, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'Phone', 'config_settings_key_name'=>'MODULE_PAYMENT_WESTERNUNION_PHONE', 'config_settings_key_values'=>'', "config_settings_key_description"=>'', 'sort_order'=>9, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'Sort Order', 'config_settings_key_name'=>'MODULE_PAYMENT_WESTERNUNION_SORT_ORDER', 'config_settings_key_values'=>'0', "config_settings_key_description"=>'<p>Sort order of display.</p>', 'sort_order'=>10, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'Description', 'config_settings_key_name'=>'MODULE_PAYMENT_WESTERNUNION_DESCRIPTION', 'config_settings_key_values'=>'', "config_settings_key_description"=>'', 'sort_order'=>11, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
        }
    }
    
    public function getTitle(){
        return MODULE_PAYMENT_WESTERNUNION;
    }
    
    public function getSortOrder(){
        if( $this->getValue( 'MODULE_PAYMENT_WESTERNUNION_SORT_ORDER' ) ){
            return $this->getValue( 'MODULE_PAYMENT_WESTERNUNION_SORT_ORDER' );
        }
        return 1;
    }
    
    public function getDescription(){
        if( $this->getValue( 'MODULE_PAYMENT_WESTERNUNION_DESCRIPTION' ) ){
            return $this->getValue( 'MODULE_PAYMENT_WESTERNUNION_DESCRIPTION' );
        }
        return "";
    }
    
    public function processSubmit(){
        return false;
    }
    
    public function afterSubmit(){
        $checkout = ClsCheckout::getCheckout();
        $currency = $checkout->getCurrency();
        
        if( ( $userId = (int)$checkout->getUserId() ) == null && ( $shippingId = (int)$checkout->getShippingAddress() ) == null && ( $shippingMethod = $checkout->getShippingMethod() ) == null && ( $billingId = (int)$checkout->getBillingAddress() ) == null && ( $paymentMethod = $checkout->getPaymentMethod() ) == null ) {
            error_log( "western union checkout \r\n", 3,  "westernunion.log");
            list( $orderId, $orderNumber ) = $checkout->createOrder();
            
            $sc = $checkout->getShoppingCart();
            $items = $sc->getCheckoutItems();
            if( count( $items ) > 0 ){
                //shopping cart clean
                foreach( $items as $k => $v ) {
                    $v->updateOrder( $orderId );
                }
            }
            
            ClsCheckout::cleanCheckout();
            return array( true, ClsOrdersFactory::instance( $orderId ));
        }
        
        return array( false, "" );
    }
}