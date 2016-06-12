<?php
class ClsPaypal extends ClsPayment implements interfaceModule, interfacePayment {
    
    private $_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
    //private $_url = "https://www.paypal.com/cgi-bin/webscr";
    
    public function isEnabled(){
        
        if( strtolower( $this->getValue( 'MODULE_PAYMENT_PAYPAL_STATUS' ) ) == "true" ){
            return true;
        }elseif( strtolower( $this->getValue( 'MODULE_PAYMENT_PAYPAL_STATUS' ) ) == "false" ){
            return false;
        }
        
        return "";
    }
    
    public function install(){/*{{{*/
        $data = array( 'config_settings_title'=>$this->getTitle(), 'config_settings_description'=>$this->getDescription(), 'sort_order'=>1, 'status'=>1 );
        if( Hqw::getApplication()->getModels( "config_settings" )->insert( $data ) ) {
            $configId = Hqw::getApplication()->getModels( "config_settings" )->lastInsertId();
    	    
    	    $t = Hqw::getApplication()->getComponent("Date")->cDate();
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'Enable PayPal Module', 'config_settings_key_name'=>'MODULE_PAYMENT_PAYPAL_STATUS', 'config_settings_key_values'=>'True', "config_settings_key_description"=>'', 'sort_order'=>1, 'set_function'=>'eHtml::htmlRadioOption( "MODULE_PAYMENT_PAYPAL_STATUS", array( "True", "False" ) )', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'Business ID', 'config_settings_key_name'=>'MODULE_PAYMENT_PAYPAL_BUSINESS_ID', 'config_settings_key_values'=>'', "config_settings_key_description"=>'<p>Primary email address for your PayPal account.</p>', 'sort_order'=>2, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'PDT Token (Payment Data Transfer)', 'config_settings_key_name'=>'MODULE_PAYMENT_PAYPAL_PDT_TOKEN', 'config_settings_key_values'=>'', "config_settings_key_description"=>'<p>Enter your PDT Token value here in order to activate transactions immediately after processing (if they pass validation).</p>', 'sort_order'=>3, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'Mode for PayPal web services', 'config_settings_key_name'=>'MODULE_PAYMENT_PAYPAL_WEB_ADDRESS', 'config_settings_key_values'=>'', "config_settings_key_description"=>'<p>Default:<br/>www.paypal.com/cgi-bin/webscr<br/>or<br/>www.paypal.com/us/cgi-bin/webscr<br/>or for the UK,<br/>www.paypal.com/uk/cgi-bin/webscr<br/>Choose the URL for PayPal live processing</p>', 'sort_order'=>5, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'Sort Order', 'config_settings_key_name'=>'MODULE_PAYMENT_PAYPAL_SORT_ORDER', 'config_settings_key_values'=>'0', "config_settings_key_description"=>'<p>Sort order of display.</p>', 'sort_order'=>6, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
    	    $data = array( 'config_settings_id'=>$configId, 'config_settings_key_title'=>'Description', 'config_settings_key_name'=>'MODULE_PAYMENT_PAYPAL_DESCRIPTION', 'config_settings_key_values'=>'', "config_settings_key_description"=>'', 'sort_order'=>7, 'set_function'=>'', "create_date"=>$t );
    	    Hqw::getApplication()->getModels( "config_settings_key" )->insert( $data );
    	    
        }
        
        
        /*
        CREATE TABLE IF NOT EXISTS `paypal` (
          `paypal_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `custom` varchar(100) NOT NULL,
          `txn_id` varchar(20) NOT NULL,
          `payment_date` datetime NOT NULL,
          `payment_status` varchar(20) NOT NULL,
          `txn_type` varchar(40) NOT NULL,
          `payment_type` varchar(40) NOT NULL,
          `business` varchar(128) NOT NULL,
          `receiver_email` varchar(128) NOT NULL,
          `receiver_id` varchar(32) NOT NULL,
          `mc_currency` char(3) NOT NULL,
          `mc_gross` decimal(10,2) NOT NULL DEFAULT '0.00',
          `mc_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
          `payment_gross` decimal(10,2) NOT NULL DEFAULT '0.00',
          `payment_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
          `tax` decimal(10,2) NOT NULL DEFAULT '0.00',
          `shipping` decimal(10,2) NOT NULL DEFAULT '0.00',
          `handling_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
          `payer_status` varchar(10) NOT NULL,
          `payer_email` varchar(128) NOT NULL,
          `payer_id` varchar(32) NOT NULL,
          `first_name` varchar(32) NOT NULL,
          `last_name` varchar(32) NOT NULL,
          `notify_version` decimal(2,1) NOT NULL DEFAULT '0.0',
          `verify_sign` varchar(128) NOT NULL,
          `memo` varchar(255) NOT NULL,
          `odate` datetime NOT NULL,
          PRIMARY KEY (`paypal_id`),
          UNIQUE KEY `txn_id` (`txn_id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
        
        
        CREATE TABLE IF NOT EXISTS `paypal_log` (
          `paypal_log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `payment_status` varchar(20) NOT NULL,
          `txn_id` varchar(20) NOT NULL,
          `parent_txn_id` varchar(20) NOT NULL,
          `data` text NOT NULL,
          `odate` datetime NOT NULL,
          PRIMARY KEY (`paypal_log_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
        */
    }/*}}}*/
    
    public function getTitle(){
        return MODULE_PAYMENT_PAYPAL;
    }
    
    public function getDescription(){
        if( $this->getValue( 'MODULE_PAYMENT_PAYPAL_DESCRIPTION' ) ){
            return $this->getValue( 'MODULE_PAYMENT_PAYPAL_DESCRIPTION' );
        }
        return "";
    }
    
    public function getSortOrder(){
        if( $this->getValue( 'MODULE_PAYMENT_PAYPAL_SORT_ORDER' ) ){
            return $this->getValue( 'MODULE_PAYMENT_PAYPAL_SORT_ORDER' );
        }
        return 1;
    }
    
    public function processSubmit(){/*{{{*/
        if( $this->getValue( 'MODULE_PAYMENT_PAYPAL_BUSINESS_ID' ) ){
            $data = array();
            //$base = $order->getBase();
            $signin = ClsFactory::instance("ClsSignin");
            $currentUser = $signin->getUser();
            $userBase = $currentUser->getBase();
            
            $checkout = ClsCheckout::getCheckout();
            
            $shippingId = $checkout->getShippingAddress();
            $shippingAddress = $currentUser->getAddressById( $shippingId );
            
            $clsCommon = ClsFactory::instance( "ClsCommon" );
            $lc = $clsCommon->getCountriesCode2( $shippingAddress['country_id'] );
            
            $buss = $this->getValue( 'MODULE_PAYMENT_PAYPAL_BUSINESS_ID' );
            
            $address = $shippingAddress['street_address'];
            $address .= ( $shippingAddress['address_line'] != '' ) ?  " ," .$shippingAddress['address_line'] : "" ;
            
            $custom = date( "Ymd", Hqw::getApplication()->getComponent("Date")->cTime() ) . "-" . ip2long( Hqw::getApplication()->getRequest()->getUserHostAddress() ) . "-" . $signin->getSessionId();
            
            $url = $this->getValue( 'MODULE_PAYMENT_PAYPAL_WEB_ADDRESS' );
            
            $currency = ClsFactory::instance( "ClsCurrency" );
            $data = array( 'cmd'=>'_xclick',
                           'charset'=>CONFIGURATION_CHARSET,
                           'lc'=>$lc,
                           'custom'=>$custom,
                           'rm'=>2,
                           'bn'=>'',
                           'mrb'=>'',
                           'pal'=>'',
                           'business'=>$buss,
                           'return'=>Hqw::getApplication()->createUrl('checkout/success', array( 'ref'=>strtolower( MODULE_PAYMENT_PAYPAL ) ) ),
                           'cancel_return'=>Hqw::getApplication()->createUrl('checkout/cancel', array( 'ref'=>strtolower( MODULE_PAYMENT_PAYPAL ) ) ),
                           'shopping_url'=>Hqw::getApplication()->createUrl('ShoppingCart/index'),
                           'notify_url'=>Hqw::getApplication()->createUrl('paypal/ipn'),
                           
                           'amount'=>$currency->getCurrencyValues( $checkout->getCheckoutTotal() ),
                           'currency_code'=>$currency->getCurrency(),
                           'shipping'=>'0.00',
                           'item_name'=>MODULE_PAYMENT_PAYPAL_ITEM_NAME,
                           'item_number'=>MODULE_PAYMENT_PAYPAL_ITEM_NUMBER,
                           'tax_cart'=>'0.00',
                           'tax'=>'0.00',
                           
                           'first_name'=>$userBase['firstname'],
                           'last_name'=>$userBase['lastname'],
                           'address1'=>$address,
                           'city'=>$shippingAddress['city'],
                           'state'=>$shippingAddress['state'],
                           'zip'=>$shippingAddress['postcode'],
                           'country'=>$clsCommon->getCountries( $shippingAddress['country_id'] ),
                           'email'=>$userBase['user_email_address'],
                           'night_phone_b'=>$shippingAddress['phone_number'],
                           'day_phone_b'=>$shippingAddress['phone_number'],
                           'no_shipping'=>1,
                           );
        }
        
        if( !empty( $data ) ) {
            $html = '<body onLoad="document.paypal_form.submit();">';
            $html .= '<form method="post" name="paypal_form" action="'. $url .'">';
            
            foreach( $data as $k=>$v ) {
                error_log( "$k=>$v \r\n", 3,  "order.log");
                $html .= '<input type="hidden" name="'.$k.'" value="'.$v.'">';
            }
            
            $html .= '</form></body>';
            return $html;
        }
        
    }/*}}}*/
    
    public function curlPost( $vars ){/*{{{*/
        $web = parse_url($this->_url);
        if( function_exists( 'curl_init' ) ) {
            
            $curlOpts = array(CURLOPT_URL => $this->_url,
                          CURLOPT_POST => TRUE,
                          CURLOPT_POSTFIELDS => $vars,
                          CURLOPT_TIMEOUT => 45,
                          CURLOPT_CONNECTTIMEOUT => 30,
                          CURLOPT_VERBOSE => FALSE,
                          CURLOPT_HEADER => FALSE,
                          CURLOPT_FOLLOWLOCATION => FALSE,
                          CURLOPT_RETURNTRANSFER => TRUE,
                          CURLOPT_SSL_VERIFYPEER => FALSE,
                          CURLOPT_SSL_VERIFYHOST => 2,
                          CURLOPT_FORBID_REUSE => TRUE,
                          CURLOPT_FRESH_CONNECT => TRUE,
                          CURLOPT_SSLVERSION=>4,
                          CURLOPT_USERAGENT => 'ftmer.b2c - Paypal Postback'
            );
            $ch = curl_init();
            curl_setopt_array($ch, $curlOpts);
            $response = curl_exec($ch);
            $curlError = curl_error($ch);
            $curlErrno = curl_errno($ch);
            $curlInfo = @curl_getinfo($ch);
            curl_close($ch);
            error_log( "curlPost response : " . $response . "\r\n", 3,  "order.log");
            error_log( "curlPost error : " . $curlError . "\r\n", 3,  "order.log");
            error_log( "curlPost errno : " . $curlErrno . "\r\n", 3,  "order.log");
            error_log( "curlPost info : " . json_encode( $curlInfo ) . "\r\n", 3,  "order.log");
            $firstline = trim(substr($response, 0, 20));
            $status = '';
            if ($status == '' && substr($firstline, 0, 8) == 'VERIFIED') $status = 'VERIFIED';
            if ($status == '' && substr($firstline, 0, 7) == 'SUCCESS') $status = 'SUCCESS';
            if ($status == '' && substr($firstline, 0, 4) == 'FAIL') $status = 'FAIL';
            if ($status == '' && substr($firstline, 0, 7) == 'INVALID') $status = 'INVALID';
            if ($status == '' && substr($firstline, 0, 12) == 'UNDETERMINED') $status = 'UNDETERMINED';
            
            error_log( "scurlPost tatus : " . $status . "\r\n" . "info : " . $response . "\r\n", 3,  "order.log");
            error_log( "\r\n date : " . Hqw::getApplication()->getComponent("Date")->cDate() . " end\r\n\r\n\r\n\r\n", 3,  "order.log");
            return array( $status, $response );
        }
        
        return false;
    }/*}}}*/
    
    public function socketPost( $vars ){/*{{{*/
        $web = parse_url($this->_url);
        
        $errnum = 0;
        $errstr = '';
        $connectTime = 10;
        
        $header .= "POST " . $web['path'] . " HTTP/1.0\r\n";
        $header .= "Host: " . $web['host'] . "\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen ($vars) . "\r\n";
        $header .= "Connection: close\r\n\r\n";
        $header .= $vars;
        
        error_log( "socketPost header: " . $header . " \r\n", 3,  "order.log");
        $fp = fsockopen( 'ssl://' . $web['host'], 443, $errno, $errstr, 30 );
        error_log( "socketPost errno: " . $errno . " \r\n", 3,  "order.log");
        error_log( "socketPost errstr: " . $errstr . " \r\n", 3,  "order.log");
        
        fputs( $fp, $header );
        
        while( !feof( $fp ) ) {
            $line = @fgets( $fp, 1024 );
            error_log( "socketPost reponse: " . json_encode( $line ) . " \r\n", 3,  "order.log");
            if( strcmp( $line, "\r\n" ) == 0 ) {
                $headerdone = true;
                $headerData .= $line;
            } else if ($headerdone) {
                $info[] = $line;
            }
        }
        
        fclose( $fp );
        $info = implode("", $info);
        error_log( "socketPost reponse: " . $info . " \r\n", 3,  "order.log");
        $firstline = trim(substr($info, 0, 20));
        $status = '';
        if ($status == '' && substr($firstline, 0, 8) == 'VERIFIED') $status = 'VERIFIED';
        if ($status == '' && substr($firstline, 0, 7) == 'SUCCESS') $status = 'SUCCESS';
        if ($status == '' && substr($firstline, 0, 4) == 'FAIL') $status = 'FAIL';
        if ($status == '' && substr($firstline, 0, 7) == 'INVALID') $status = 'INVALID';
        if ($status == '' && substr($firstline, 0, 12) == 'UNDETERMINED') $status = 'UNDETERMINED';
    
        return array( $status, $info );
    }/*}}}*/
    
    public function post( $param ){/*{{{*/
        
        if( ( $result = $this->curlPost( $param ) ) !== false ) {
            return $result;
        }
        
        if( ( $result = $this->socketPost( http_build_query( $param ) ) ) !== false ) {
        	return $result;
        }
        
        return false;
    }/*}}}*/
    
    public function postPDT(){/*{{{*/
        $param = array();
        $param['cmd'] = "_notify-synch";
        $param['tx'] = $_GET['tx'];
        $param['at'] = $this->getValue( 'MODULE_PAYMENT_PAYPAL_PDT_TOKEN' );
        error_log( "\r\n\r\n date : " . Hqw::getApplication()->getComponent("Date")->cDate() . "\r\n", 3,  "order.log");
        error_log( "postPDT : " . json_encode( $param ) . "\r\n", 3,  "order.log");
        
        return $this->post( $param );
    }/*}}}*/
    
    public function postIPN(){/*{{{*/
        $_POST['cmd'] = "_notify-validate";
        error_log( "\r\n\r\n date : " . Hqw::getApplication()->getComponent("Date")->cDate() . "\r\n", 3,  "order.log");
        error_log( "postIPN : " . json_encode( $_POST ) . "\r\n", 3,  "order.log");
        if( ( $result = $this->post( $_POST ) ) ) {
        	list( $status, $response ) = $result;
        	$buss = $this->getValue( 'MODULE_PAYMENT_PAYPAL_BUSINESS_ID' );
        	error_log( "postIPN status: " . $status . "\r\n", 3,  "order.log");
        	error_log( "postIPN buss: " . $buss . "\r\n", 3,  "order.log");
        	error_log( "postIPN  payment_status: " . $_POST['payment_status'] . "\r\n", 3,  "order.log");
        	error_log( "postIPN  business: " . $_POST['business'] . "\r\n", 3,  "order.log");
        	error_log( "postIPN  receiver_email: " . $_POST['receiver_email'] . "\r\n", 3,  "order.log");
        	if( strtolower( $status ) == "verified" && strtolower( $_POST['payment_status'] ) == "completed" && $_POST['business'] == $buss && $_POST['receiver_email'] == $buss ) {
        	    
        	    error_log( "postIPN  create order: \r\n", 3,  "order.log");
        		//create order
        		$param = array( 'custom', 'txn_id', 'payment_date', 'payment_status', 'txn_type', 'payment_type', 'business', 'receiver_email', 'receiver_id',
        		                'mc_currency', 'mc_gross', 'mc_fee', 'payment_gross', 'payment_fee', 'tax', 'shipping', 'handling_amount', 'payer_status',
        		                'payer_email', 'payer_id', 'first_name', 'last_name', 'notify_version', 'verify_sign', 'memo'
        		);
        		
        		$data = array();
        		foreach( $param as $k ) {
        			if( isset( $_POST[$k] ) ) {
        			    if( $k == "payment_date" ) {
        			    	$data[$k] = date( 'Y-m-d H:i:s', strtotime( $_POST[$k] ) );
        			    }else{
        			        $data[$k] = $_POST[$k];
        			    }
        			}
        		}
        		
        		$data['odate'] = Hqw::getApplication()->getComponent("Date")->cDate();
        		error_log( "postIPN  data: " . json_encode( $data ) . "\r\n", 3,  "order.log");
        		
        		if( class_exists( "ClsCheckout" ) ) {
        			error_log( "postIPN  ClsCheckout exists \r\n", 3,  "order.log");
        		}
        
        		$cm = explode( "-", $data['custom'] );
        		if( count( $cm ) > 0 ) {
        			$sessionId = end( $cm );
        		}
        		
        		$checkout = ClsCheckout::getCheckout( $sessionId );
        		if( ( $userId = (int)$checkout->getUserId() ) == null && ( $shippingId = (int)$checkout->getShippingAddress() ) == null && ( $shippingMethod = $checkout->getShippingMethod() ) == null && ( $billingId = (int)$checkout->getBillingAddress() ) == null && ( $paymentMethod = $checkout->getPaymentMethod() ) == null ) {
        		    
        		    $checkout->setPaymentTranId( $_POST['txn_id'] );
        		    error_log( "postIPN  insert paypal \r\n", 3,  "order.log");
        		    
        		    error_log( "create order \r\n", 3,  "order.log");
                    list( $orderId, $orderNumber ) = $checkout->createOrder();
                    error_log( "create order end\r\n", 3,  "order.log");
                    
                    $sc = $checkout->getShoppingCart();
                    $items = $sc->getCheckoutItems();
                    if( count( $items ) > 0 ){
                        //shopping cart clean
                        foreach( $items as $k => $v ) {
                        	$v->updateOrder( $orderId );
                        }
                    }
                    
                    $data['orders_id'] = $orderId;
        		    error_log( "postIPN  create paypal: \r\n", 3,  "order.log");
            		if( Hqw::getApplication()->getModels( "paypal" )->insert( $data ) ){
                        //check clean
            		}else{
            		    error_log( "postIPN  create paypal losed: \r\n", 3,  "order.log");
            		}
            		
            		ClsCheckout::cleanCheckout();
            		
            		$log = array();
                    $log['payment_status'] = $_POST['payment_status'];
                    $log['txn_id'] = $_POST['txn_id'];
                    $log['parent_txn_id'] = $_POST['parent_txn_id'];
                    $log['data'] = json_encode( $_POST );
                    $log['odate'] = Hqw::getApplication()->getComponent("Date")->cDate();
                    Hqw::getApplication()->getModels( "paypal_log" )->insert( $log );
                    
        		    return true;
        		}else{
        		    error_log( "postIPN  session checkout error: \r\n", 3,  "order.log");
        		    return false;
        		}
        	}
        }
        return false;
    }/*}}}*/
    
    public function afterSubmit(){/*{{{*/
        if( ( $result = $this->postPDT() ) ) {
        	list( $status, $response ) = $result;
        	if( strtolower( $status ) == "success" ) {
        		//update order
        		$tx = trim( $_GET['tx'] );
        		if( Hqw::getApplication()->getModels( "paypal" )->where( array( 'txn_id'=> $tx ) )->fetch() ){
        		    $order = Hqw::getApplication()->getModels( "orders" )->where( array( 'tran_id'=> $tx ) )->fetch();
        		    if( isset( $order['orders_id'] ) ) {
        		    	return array( true, ClsOrdersFactory::instance( $order['orders_id'] ) );
        		    }
        		}
        	}
        }
        
        return array( false, "" ) ;
    }/*}}}*/
}
?>
