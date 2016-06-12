<?php
class UserActivitiesController extends Controller{
    
    private $_layout = "layout";
    
    public function getLayouts() {
    	return $this->_layout;
    }
    
    public function getCookieUser() {
        return ClsFactory::instance("ClsSignin")->getCookieUser();
    }
    
    public function ActionStates(){
        if( isset($_GET['country_id']) && (int)$_GET['country_id'] != 0 ) {
        	$zones = Hqw::getApplication()->getModels( " zones" )->fetchAll( array('zone_country_id'=>(int)$_GET['country_id']) );
        	if( count( $zones ) > 0 ) {
        		echo json_encode( $zones );
        		exit;
        	}
        	
        }
        echo 0;
        exit;
        
    }
    
    public function ActionPostHelpful() {
        $data = array();
        
        if( !$_POST['products_reviews_id'] || !$_POST['helpful'] ) {
        	echo 0;
        	exit;
        }else{
            $data['products_reviews_id']  = (int)$_POST['products_reviews_id'];
            $data['helpful']  = $this->quote( $_POST['helpful'] );
        }
        
        if( ( $user = $this->getCookieUser() ) === false){
            //no login
            echo 10;
            exit;
        }
        
        if( $user->postProductsReviewsHelpful( $data ) ) {
        	echo 100;
        	exit;
        }
        echo 0;
        exit;
    }
    
    public function ActionPostFavorites() {
        $data = array();
        
        if( !$_POST['products_id'] ){
            echo 0;
            exit;
        }else{
            $data['products_id'] = (int)$_POST['products_id'];
        }
        
        if( ( $user = $this->getCookieUser() ) === false){
            //no login
            echo 10;
            exit;
        }
        
        if( $user->postFavorites( $data ) ) {
        	echo 100;
        	exit;
        }
        
        echo 0;
        exit;
    }
    
    public function ActionCancelFavorites() {
        
        if( !$_POST['products_id'] ){
            echo 0;
            exit;
        }
        
        if( ( $user = $this->getCookieUser() ) === false){
            //no login
            echo 10;
            exit;
        }
        
        if( $user->cancelFavorites( (int)$_POST['products_id'] ) ) {
        	echo 100;
        	exit;
        }
        
        echo 0;
        exit;
    }
    
    public function ActionSignin() {
        $this->_layout = "standardlayout";
        $data = array();
        $errorTips = "";
        $signin = ClsFactory::instance("ClsSignin");
        // had signed in
        if( $signin->getUser() !== false ){
            Hqw::getApplication()->getComponent("Request")->redirect( "/" );
        }
        
        $from = "";
        if( isset( $_GET['from'] ) && trim( $_GET['from'] ) != "" ) {
        	$from = $_GET['from'];
        }
        
        if( Hqw::getApplication()->getRequest()->getIsPostRequest() && isset( $_GET['type'] ) && strtolower( trim( $_GET['type'] ) ) == "process" ) {
        	if( ( $signin->getAttemptSignin() === false ) || ( $signin->getAttemptSignin() && isset( $_POST['verify_code'] ) && isset( $_SESSION['captcha'] ) && strtolower( trim( $_POST['verify_code'] ) ) == strtolower( trim( $_SESSION['captcha'] ) ) ) ) {
        	    if( isset( $_POST['email'] ) && $_POST['email'] != '' && Hqw::getApplication()->getComponent("Validation")->isEmail( trim( $_POST['email'] ) ) ) {
        	    	if( isset( $_POST['password'] ) && $_POST['password'] != '' && strlen( trim( $_POST['password'] ) ) >= 6 && strlen( trim( $_POST['password'] ) ) <= 30 ) {
        	    	    if( ( $signinUser = $signin->getUserByEmail( trim( $_POST['email'] ) ) ) && isset( $signinUser['user_id'] ) && $signinUser['user_id'] != "" ) {
        	    	        
        	    	        if( $signin->validatePassword( $_POST['password'], $signinUser['user_password'] ) ) {
        	    	            
        	    	            if( $_POST['keep'] ){
        	    	                $signin->setCookieUser( $signinUser['user_id'], 1 );
        	    	            }else{
        	    	                $signin->setCookieUser( $signinUser['user_id'], 0 );
        	    	            }
        	    	            
        	    	            $signin->setSessionUser( $signinUser['user_id'] );
        	    	        	
        	    	        	//***login log***/
        	    	        	$signin->postSigninLog( $signinUser['user_id'] );
								if( isset( $_GET['from'] ) ){	
									if( ($from = base64_decode( $_GET['from'] ) ) === false ){
										$from = "";
									}
								}
								
								//combine shopping cart item
        	    	        	//*** checkout login update had exists cart products checkout_selected 'N' ***//
        	    	        	$sc = ClsFactory::instance("ClsShoppingCart");
        	    	        	if( strpos( strtolower( $from ), "checkout" ) === false ){
        	    	        	    $sc->SigninCombine();
        	    	        	}else{
        	    	        	    $sc->SigninCombine("N");
        	    	        	}
                            	
        	    	        	if( $from != "" ) {
        	    	        	    $urlDict = parse_url( $from );
        	    	        	    if( strstr( $urlDict['host'], Hqw::getApplication()->getComponent("Request")->getHostInfo(true) ) !== false ) {
        	    	        	    	Hqw::getApplication()->getComponent("Request")->redirect( $from );
        	    	        	    }else{
        	    	        	        Hqw::getApplication()->getComponent("Request")->redirect( "/" );
        	    	        	    }
        	    	        	}else{
        	    	        	    Hqw::getApplication()->getComponent("Request")->redirect( "/" );
        	    	        	}
        	    	            
        	    	        }else{
        	    	            $errorTips = USER_EMAIL_PASSWORD_INCORRECT;
        	    	        }
        	    	    }else{
        	    	        $errorTips = USER_EMAIL_PASSWORD_INCORRECT;
        	    	    }
        	    	    
        	    	}else{
        	    	    $errorTips = USER_PASSWORD_INCORRECT;
        	    	}
        	        
        	    }else{
        	        $errorTips = USER_EMAIL_INVALID;
        	    }
        	}else{
        	    $errorTips = USER_VERIFY_NOT_MATCH;
        	}
        	//grand attempt signin
        	$signin->setAttemptSignin();
        }
        $data['errorTips'] = $errorTips;
        $data['from'] = $from;
    	$this->render( "signin", $data );
    }
    
    public function ActionRegister() {
        $this->_layout = "standardlayout";
        $data = array();
        $errorTips = "";
        $signin = ClsFactory::instance("ClsSignin");
        $from = "";
        if( isset( $_GET['from'] ) && trim( $_GET['from'] ) != "" ) {
        	$from = urldecode( $_GET['from'] );
        }
        
        if( Hqw::getApplication()->getRequest()->getIsPostRequest() && isset( $_GET['type'] ) && strtolower( trim( $_GET['type'] ) ) == "process" ) {
            if( ( $signin->getAttemptSignin() === false ) || ( $signin->getAttemptSignin() && isset( $_POST['verify_code'] ) && isset( $_SESSION['captcha'] ) && strtolower( trim( $_POST['verify_code'] ) ) == strtolower( trim( $_SESSION['captcha'] ) ) ) ) {
                if( isset( $_POST['email'] ) && $_POST['email'] != '' && Hqw::getApplication()->getComponent("Validation")->isEmail( trim( $_POST['email'] ) ) ) {
                    if( isset( $_POST['password'] ) && $_POST['password'] != '' && strlen( trim( $_POST['password'] ) ) >= 6 && strlen( trim( $_POST['password'] ) ) <= 30 ) {
                        if( isset( $_POST['password'] ) && $_POST['password'] != '' && $_POST['password'] == $_POST['confirm_password'] ) {
                        	//register
                        	$data = array();
                            $data['user_password'] = $signin->getPassword( $_POST['password'] );
                            $data['user_email_address'] = $_POST['email'];
                            
                            //*** register ***//
                            if( Hqw::getApplication()->getModels( "user" )->insert( $data ) ){
                                $userId = Hqw::getApplication()->getModels( "user" )->lastInsertId();
                                
                                
                                $signin->setCookieUser( $userId );
        	    	            $signin->setSessionUser( $userId );
                                
                                //send mail, welcome to register website.
                                //***send mail***/
                                
                                //*** jump complete profile or index ***/
                                if( isset( $_GET['from'] ) ){	
									if( ($from = base64_decode( $_GET['from'] ) ) === false ){
										$from = "";
									}
								}
								
								//combine shopping cart item
        	    	        	//*** checkout register update had exists cart products checkout_selected 'N' ***//
        	    	        	$sc = ClsFactory::instance("ClsShoppingCart");
								if( strpos( strtolower( $from ), "checkout" ) === false ){
        	    	        	    $sc->SigninCombine();
        	    	        	}else{
        	    	        	    $sc->SigninCombine("N");
        	    	        	}
                            	
        	    	        	if( $from != "" ) {
        	    	        	    $urlDict = parse_url( $from );
        	    	        	    if( strstr( $urlDict['host'], Hqw::getApplication()->getComponent("Request")->getHostInfo(true) ) !== false ) {
        	    	        	    	Hqw::getApplication()->getComponent("Request")->redirect( $from );
        	    	        	    }else{
        	    	        	        Hqw::getApplication()->getComponent("Request")->redirect( "/" );
        	    	        	    }
        	    	        	}else{
        	    	        	    Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('UserActivities/registerAnnex') );
        	    	        	}
                            }
                        }else{
                            $errorTips = USER_PASSWORD_NOT_MATCH;
                        }
                    }else{
                        $errorTips = USER_PASSWORD_INCORRECT;
                    }
                }else{
                    $errorTips = USER_EMAIL_INVALID;
                }
            }else{
                $errorTips = USER_VERIFY_NOT_MATCH;
            }
            $signin->setAttemptSignin();
        }
        
        $data['errorTips'] = $errorTips;
        $data['from'] = $from;
    	$this->render( "register", $data );
    }
    
    public function ActionRegisterAnnex(){
        
    }
    
    public function ActionCaptcha(){
        $captcha = Hqw::getApplication()->getComponent('CaptchaImage');
        $captcha->CreateImage();
    }
    
    public function ActionIndex(){
        $data = array();
        $this->render( "index", $data );
    }
    
    public function ActionAccountSetting(){
        $data = array();
        $errorTips = $successTips = "";
        
        $signin = ClsFactory::instance("ClsSignin");
        if( Hqw::getApplication()->getRequest()->getIsPostRequest() && isset( $_GET['type'] ) && strtolower( trim( $_GET['type'] ) ) == "process" ) {
            $signin->checkUser();
        	if( isset( $_POST['user_email_address'] ) && $_POST['user_email_address'] != '' && Hqw::getApplication()->getComponent("Validation")->isEmail( trim( $_POST['user_email_address'] ) ) ) {
            	$postData = array();
            	
            	$postData['user_email_address'] = $_POST['user_email_address'];
            	if( isset( $_POST['firstname'] ) && $_POST['firstname'] != '' && strlen( trim( $_POST['firstname'] ) ) >= 2 && strlen( trim( $_POST['firstname'] ) ) <= 30 ) {
            	    $postData['firstname'] = $_POST['firstname'];
            	}
            	
            	if( isset( $_POST['lastname'] ) && $_POST['lastname'] != '' && strlen( trim( $_POST['lastname'] ) ) >= 2 && strlen( trim( $_POST['lastname'] ) ) <= 30 ) {
            	    $postData['lastname'] = $_POST['lastname'];
            	}
            	
            	if( isset( $_POST['telephone'] ) && $_POST['telephone'] != '' && strlen( trim( $_POST['telephone'] ) ) >= 8 && strlen( trim( $_POST['telephone'] ) ) <= 50 ) {
            	    $postData['telephone'] = $_POST['telephone'];
            	}
            	
            	$postData['fax'] = $_POST['fax'];
            	$postData['gender'] = $_POST['gender'];
            	$postData = array_map( "trim", array_map( "htmlspecialchars",$postData ) );
            	
            	$currentUser = $signin->getUser();
            	$currentUser->updateBase( $postData );
            	//update success
            	$successTips = USER_ACCOUNT_CHANGED_SUCCESS;
            }else{
                $errorTips = USER_EMAIL_INVALID;
            }
        }
        
        $signin->checkCookieUser();
        $user = $signin->getCookieUser();
        $data['profile'] = array_map("stripslashes", $user->getBase() );
        $data['errorTips'] = $errorTips;
        $data['successTips'] = $successTips;
        $this->render( "account_setting", $data );
    }
    
    public function ActionPasswordModification(){
        $data = array();
        $errorTips = $successTips = "";
        $signin = ClsFactory::instance("ClsSignin");
        $signin->checkUser();
        if( Hqw::getApplication()->getRequest()->getIsPostRequest() && isset( $_GET['type'] ) && strtolower( trim( $_GET['type'] ) ) == "process" ) {
            $currentUser = $signin->getUser();
            $baseUser = $currentUser->getBase();
            
            if( $baseUser['user_email_address'] == trim( $_POST['user_email_address'] ) ) {
                if( $baseUser['user_password'] == $signin->getPassword( $_POST['password'] ) ) {
                    
                    if( isset( $_POST['renew_password'] ) && $_POST['renew_password'] != '' && strlen( trim( $_POST['renew_password'] ) ) >= 6 && strlen( trim( $_POST['renew_password'] ) ) <= 30 ) {
                        if( isset( $_POST['renew_password'] ) && $_POST['renew_password'] != '' && $_POST['renew_password'] == $_POST['confirm_password'] ) {
                            
                            $postData = array();
                            $postData['user_password'] = $signin->getPassword( $_POST['renew_password'] );
                            $currentUser->updateBase( $postData );
                            //update success
                            $successTips = USER_PASSWORD_CHANGED_SUCCESS;
                            
                        }else{
                            $errorTips = USER_PASSWORD_NOT_MATCH;
                        }
                    }else{
                        $errorTips = USER_PASSWORD_INCORRECT;
                    }
                }else{
                    $errorTips = USER_POST_PASSWORD_INCORRECT;
                }
            }else{
                $errorTips = USER_POST_EMAIL_INCORRECT;
            }
        }
        
        $data['errorTips'] = $errorTips;
        $data['successTips'] = $successTips;
        $this->render( "password_modify", $data );
    }
    
    public function ActionAddressBook(){
        $data = array();
        $errorTips = $successTips = "";
        $signin = ClsFactory::instance("ClsSignin");
        $signin->checkCookieUser();
        $currentUser = $signin->getCookieUser();
        
        if( Hqw::getApplication()->getRequest()->getIsPostRequest() && isset( $_GET['type'] ) && strtolower( trim( $_GET['type'] ) ) == "process" ) {
            if( $signin->getUser() !== false && isset( $_POST['address_book_id'] ) && $_POST['address_book_id'] != "0" ){
                $userAddressModels = Hqw::getApplication()->getModels( "user_address_book" );
                if( $userAddressModels->delete( array( 'user_id'=>$signin->getUser()->getUserId(), 'user_address_book_id'=>(int)$_POST['address_book_id'] ) ) ){
                    $successTips = USER_ADDRESS_DELETE_SUCCESS;
                }
            }
        }
        
        $addressBook = Hqw::getApplication()->getModels( "user_address_book" );
        $data['result'] = $addressBook->fetchAll( array( 'user_id'=>$currentUser->getUserId() ) );
        
        $data['profile'] = $currentUser->getBase();
        $data['errorTips'] = $errorTips;
        $data['successTips'] = $successTips;
        $this->render( "address_book", $data );
    }
    
    public function ActionNewAddress(){
        $data = array();
        $errorTips = $successTips = "";
        $signin = ClsFactory::instance("ClsSignin");
        
        if( Hqw::getApplication()->getRequest()->getIsPostRequest() && isset( $_GET['type'] ) && strtolower( trim( $_GET['type'] ) ) == "process" ) {
            $signin->checkCookieUser();
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
            
            if( !empty( $errorTips ) ) {
            	$errorTips = implode( "<br />", $errorTips );
            }else{
                
                $postData = array_map( "trim", array_map( "htmlspecialchars",$postData ) );
                $signin->checkUser();
                $currentUser = $signin->getUser();
                
                $defaultBook = 0;
                if( isset( $_POST['user_address_default'] ) &&  $_POST['user_address_default'] == "1" ) {
                	$defaultBook = 1;
                }
                
                
                if( isset( $_POST['user_address_book_id'] ) && $_POST['user_address_book_id'] != "0" ) {
                    $_GET['address_book_id'] = (int)$_POST['user_address_book_id'];
                	$currentUser->updateAddress( (int)$_POST['user_address_book_id'], $postData, $defaultBook );
                    $successTips = USER_CHANGED_ADDRESS_SUCCESS;
                }else{
                    $addressNumber = $currentUser->getAddressNumber();
                    // max address number
                    if( (int)$addressNumber < ClsSettings::$MAX_ADDRESS ) {
                    	$currentUser->postAddress( $postData, $defaultBook );
                    	$successTips = USER_POST_ADDRESS_SUCCESS;
                    }else{
                        $errorTips = USER_MAX_ADDRESS_NUMBER;
                    }
                }
            }
            
        }
        
        $signin->checkCookieUser();
        
        $userId = $signin->getCookieUser()->getUserId();
        if( isset( $_GET['address_book_id'] ) && trim( $_GET['address_book_id'] ) != "" ) {
        	$data['addressBooks'] = Hqw::getApplication()->getModels( "user_address_book" )->fetch( array( 'user_id'=>(int)$userId, 'user_address_book_id'=>(int)$_GET['address_book_id'] ) );
        	$data['profile'] = $signin->getCookieUser()->getBase();
        }
        $data['errorTips'] = $errorTips;
        $data['successTips'] = $successTips;
        $this->render( "address", $data );
    }
    
    public function ActionMyFavorites(){
        $data = array();
        
        $errorTips = $successTips = "";
        $signin = ClsFactory::instance("ClsSignin");
        $signin->checkCookieUser();
        $currentUser = $signin->getCookieUser();
        
        $favorites = Hqw::getApplication()->getModels( "user_favorites" );
        
        if( Hqw::getApplication()->getRequest()->getIsPostRequest() && isset( $_GET['type'] ) && strtolower( trim( $_GET['type'] ) ) == "process" ) {
            if( isset( $_POST['check'] ) && is_array( $_POST['check'] ) && !empty( $_POST['check'] ) ) {
            	$deleteIds = implode( ",", array_map( "addslashes", array_keys( $_POST['check'] ) ) );
            	$favorites = $favorites->condition( " products_id IN (". $deleteIds .")" );
            	$favorites->delete( array( "user_id"=>$currentUser->getUserId() ) );
            	$successTips = USER_FAVORITES_DELETED_SUCCESS;
            }else{
                $errorTips = USER_ACT_REQUIRE_SELECT;
            }
        }
                
        $language = ClsFactory::instance( "ClsLanguage" );
    	$languageId = $language->getLanguage();
        
    	
    	$favorites = $favorites->join( Hqw::getApplication()->getModels( "products" ), array( 'on'=>'products_id' ) );
        $favorites = $favorites->join( Hqw::getApplication()->getModels( "products_description" ), array( 'on'=>'products_id' ) );
    	$favorites = $favorites->order( "favorites_date_added DESC" );
    	$favorites = $favorites->where( array('products_status'=>1), "AND", Hqw::getApplication()->getModels( "products" ) );
    	
    	$query = array( 'user_id'=>$currentUser->getUserId() );
        $data['pd'] = new DbDataProvider( $favorites, array( 'query'=>$query, 'pagination'=>array( 'pagesize'=>6 ) ) );
    	
        $data['errorTips'] = $errorTips;
        $data['successTips'] = $successTips;
        $this->render( "my_favorites", $data );
    }
    
    public function ActionRecentlyViewed(){
        $data = array();
        
        $signin = ClsFactory::instance("ClsSignin");
        $signin->checkCookieUser();
        $currentUser = $signin->getCookieUser();
        
        $data['history'] = $signin->getHistory( 8 );
        //print_r($data['history']);
        
        $data['errorTips'] = $errorTips;
        $data['successTips'] = $successTips;
        $this->render( "recently_viewed", $data );
    }
    
    public function ActionOrder(){
        $data = array();
        $signin = ClsFactory::instance("ClsSignin");
        $signin->checkUser();
        if( isset( $_GET['no'] ) && $_GET['no'] != "" ){
            $order = ClsOrdersFactory::instByNumber( $_GET['no'] );
            if( $order !== false ) {
            	$data['order'] = $order;
            	$base = $order->getBase();
            	$userId = $signin->getUser()->getUserId();
            	if( $userId != $base['user_id']  ) {
            		Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('UserActivities/index') );
            	}
            	
            }else{
                Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('UserActivities/index') );
            }
        }else{
            Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('UserActivities/index') );
        }
        
        $this->render( "order", $data );
    }
    
    public function ActionMyOrders(){
        $data = array();
        $signin = ClsFactory::instance("ClsSignin");
        $signin->checkUser();
        
        $this->render( "my_orders", $data );
    }
    
    
    public function ActionReturnExchange(){
        $data = array();
        $signin = ClsFactory::instance("ClsSignin");
        $signin->checkUser();
        
        $this->render( "return_exchange", $data );
    }
    
    
    public function ActionReturnItem(){
        $data  = array();
        $signin = ClsFactory::instance("ClsSignin");
        $signin->checkUser();
        
        if( isset( $_GET['no'] ) && $_GET['no'] != "" ){
            $order = ClsOrdersFactory::instByNumber( $_GET['no'] );
            if( $order !== false ) {
            	$data['order'] = $order;
            	$base = $order->getBase();
            	$data['base'] = $base;
            	$userId = $signin->getUser()->getUserId();
            	if( $userId != $base['user_id']  ) {
            		Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('UserActivities/index') );
            	}
            	
            }else{
                Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('UserActivities/index') );
            }
        }else{
            Hqw::getApplication()->getComponent("Request")->redirect( Hqw::getApplication()->createUrl('UserActivities/index') );
        }
        
        $this->render( "return_item", $data );
    }
    
}
?>
