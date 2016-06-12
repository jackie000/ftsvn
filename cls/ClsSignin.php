<?php
class ClsSignin {
    
    public $_history_cookie_var = 'history';
    
    public $_cookie_user_sign = 'user_profile';
    
    //30 day
    public $_cookie_30_expire = 2592000;
    
    public $_cookie_7_expire = 604800;
    
    public $_signin_attempt_time_var= "signin_attempt_time";
    
    public $_signin_attempt_count_var = "signin_attempt_count";
    
    //1 minute
    public $_signin_attempt_time = 60;
    
    //attempt count 3
    public $_signin_attempt_count = 3;
    
    public function checkCookieUser(){
        $cookieVar = $this->_cookie_user_sign;
        $cookieManager = Hqw::getApplication()->getRequest()->getCookie();
        $host = Hqw::getApplication()->getRequest()->getHostInfo();
        
        $sideword = crc32( $host );
        $sideword = sprintf("%u",$sideword);
        $value = '';
        
        $value = $cookieManager->getCookie( $cookieVar );
        
        $userId = $keep = 0;
        if( $value ) {
            //decrypt
            $value = Hqw::getApplication()->getSecurityManager()->decrypt( $value, $sideword );
            
            if( ( $pos = strpos( $value, ',' ) ) !== false ) {
    			$userId = (int)substr( $value, 0, $pos );
    			$keep = (int)substr( $value, $pos + 1 );
    		}
        }
        
        if( $this->getUser() ) {
        	$userId = $this->getUser()->getUserId();
        }
        
        if( $userId == 0 ) {
            $param = $_GET;
        	$from = Hqw::getApplication()->createUrl( Hqw::getApplication()->getController()->getId() . '/' . Hqw::getApplication()->getController()->getActions()->getAction(), $param );
            $from = base64_encode( $from );
            $url = Hqw::getApplication()->createUrl('UserActivities/signin',array('from'=> $from  ) );
        	Hqw::getApplication()->getComponent("Request")->redirect( $url );
        }
    }
    
    public function getCookieUser() {
        
        $cookieVar = $this->_cookie_user_sign;
        $cookieManager = Hqw::getApplication()->getRequest()->getCookie();
        $host = Hqw::getApplication()->getRequest()->getHostInfo();
        
        $sideword = crc32( $host );
        $sideword = sprintf("%u",$sideword);
        $value = '';
        
        $value = $cookieManager->getCookie( $cookieVar );
        
        $userId = $keep = 0;
        if( $value ) {
            //decrypt
            $value = Hqw::getApplication()->getSecurityManager()->decrypt( $value, $sideword );
            
            if( ( $pos = strpos( $value, ',' ) ) !== false ) {
    			$userId = (int)substr( $value, 0, $pos );
    			$keep = (int)substr( $value, $pos + 1 );
    		}
        }
        
        if( $this->getUser() ) {
        	$userId = $this->getUser()->getUserId();
        }
        //***online status***/
        $this->setVisitLog( $userId );
        if( $userId != 0  ) {
            static $resettingCookie = 0;
            if( $resettingCookie == 0 ) {
            	$this->setCookieUser( $userId, $keep );
            	$resettingCookie++;
            }
        	return ClsUserFactory::instance( $userId );
        }
        
        return false;
    }
    
    public function setVisitLog( $userId ){
        static $KEEP_RECORD = 0;
        if( $KEEP_RECORD == 1 ){
            return false;
        }else{
            $KEEP_RECORD = 1;
        }
        
        $data = array();
        $data['user_id'] = $userId;
        if( $userId ) {
        	$user = ClsUserFactory::instance( $userId );
        	$data['full_name'] = $user->getName();
        }else{
            $data['full_name'] = "Guest";
            $data['user_id'] = 0;
        }
        $data['session_id'] = $this->getSessionId();
        if( empty( $data['session_id'] ) ) {
        	$data['full_name'] = "Spider";
        }
        $data['ip_address'] = Hqw::getApplication()->getRequest()->getUserHostAddress();
        $data['user_agent'] = Hqw::getApplication()->getRequest()->getUserAgent();
        $data['last_page_url'] = Hqw::getApplication()->getRequest()->getRequestUri();
        $data['time_visit'] = Hqw::getApplication()->getComponent("Date")->cDate();
        Hqw::getApplication()->getModels( "user_visit_log" )->insert( $data );
        
    }
    
    public function checkUser(){
        if( $this->getUser() === false ) {
            $param = $_GET;
            $from = Hqw::getApplication()->createUrl( Hqw::getApplication()->getController()->getId() . '/' . Hqw::getApplication()->getController()->getActions()->getAction(), $param );
            $from = base64_encode( $from );
            $url = Hqw::getApplication()->createUrl('UserActivities/signin',array('from'=> $from  ) );
        	Hqw::getApplication()->getComponent("Request")->redirect( $url );
        }
    }
    
    public function getUser() {
        
        if( isset( $_SESSION['user_id'] ) ) {
        	$userId = $_SESSION['user_id'];
        }else{
            return false;
        }
        
    	return ClsUserFactory::instance( $userId );
    }
    
    public function setCookieUser( $userId, $keep=0 ){
        
        $host = Hqw::getApplication()->getRequest()->getHostInfo();
        $sideword = crc32( $host );
        $sideword = sprintf("%u",$sideword);
        
        $cookieVar = $this->_cookie_user_sign;
        $cookieManager = Hqw::getApplication()->getRequest()->getCookie();
        
        //encrypt
        $cookieValue = Hqw::getApplication()->getSecurityManager()->encrypt( $userId . ",$keep", $sideword );
        $cookie = new Cookie( $cookieVar, $cookieValue );
        $cookie->domain = $this->getCookieDomain();
        if( $keep ) {
        	$cookie->expire = Hqw::getApplication()->getComponent("Date")->cTime() + $this->_cookie_30_expire;
        }else{
            $cookie->expire = Hqw::getApplication()->getComponent("Date")->cTime() + $this->_cookie_7_expire;
        }
        $cookieManager->add( $cookieVar, $cookie );
        
    }
    
    public function setSessionUser( $userId ){
        
        $saveSession = $_SESSION;
        $oldSessionId = $this->getSessionId();
        session_regenerate_id();
        $newSessionId = $this->getSessionId();
        $this->getSessionId( $oldSessionId );
        $this->getSessionId( $newSessionId );
        $_SESSION = $saveSession;
        
    	$_SESSION['user_id'] = $userId;
    	return true;
    }
    
    public function getSessionId( $session = '' ) {
        if( !empty( $session ) ) {
        	return session_id( $session );
        }else{
            return session_id();
        }
    	
    }
    
    /**
     * setting attempt for signin 
     *
     */
    public function setAttemptSignin(){
        $t = Hqw::getApplication()->getComponent("Date")->cTime();
        if( isset($_SESSION[$this->_signin_attempt_time_var]) && isset( $_SESSION[$this->_signin_attempt_count_var] ) && ( $_SESSION[$this->_signin_attempt_time_var] + $this->_signin_attempt_time ) >= $t ) {
    		$_SESSION[$this->_signin_attempt_count_var] += 1;
        }else{
            $_SESSION[$this->_signin_attempt_time_var] = $t;
            $_SESSION[$this->_signin_attempt_count_var] = 1;
        }
    }
    
    /**
     * 3 attempt for signin
     *
     * @return boolean
     */
    public function getAttemptSignin(){
        if( isset( $_SESSION[$this->_signin_attempt_count_var] ) && $_SESSION[$this->_signin_attempt_count_var] > $this->_signin_attempt_count  ) {
        	return true;
        }
        return false;
    }
    
    public function getSecretKey(){
        return Hqw::getApplication()->getComponent("Request")->getHostInfo(true);
    }
    
    public function validatePassword( $password, $user_password ){
        
        return true;
        if( $user_password == $this->getPassword( $password ) ) {
        	return true;
        }
        
        return false;
    }
    
    public function getPassword( $password ){
        return md5( $this->getSecretKey() . md5( trim( $password ) ) );
    }
    
    public function getUserByEmail( $email ){
        if( $email ) {
        	$user = Hqw::getApplication()->getModels( "user" );
            $user = $user->where( array( 'status'=>1, 'user_email_address'=>$email ) );
            return $user->fetch();
        }
        return false;
    }
    
    public function getCookieDomain() {
        $url = Hqw::getApplication()->getRequest()->getHostInfo();
        $p = "/http:\/\/[a-z]+\.([\.a-z-]+)/";
        preg_match($p, $url, $match);
        if( isset( $match[1] ) && $match[1] != '' ) {
            return "." . $match[1];
        }
        return '';
    }
    
    
    /**
     * brown history
     *
     * @param int $limit
     * @return array
     */
    public function getHistory( $limit = 6, $productsId=0 ){
        
        $values = array();
        if( $this->getCookieUser() ) {
        	$history = $this->getCookieUser()->getHistory( $limit );
        	if( $history ) {
        		foreach( $history as $k => $v ) {
        		    if( $productsId==0 || $productsId != $v['products_id'] ) {
        		    	$values[] = $v['products_id'];
        		    }
        		}
        	}
        }
        if( count( $values ) < $limit) {
            $cookieHistory = $this->getCookieHistory();
            if( $cookieHistory ) {
            	foreach( $cookieHistory as $k => $v ) {
            		if( !in_array($v, $values) && $v != 0 && $v != '' ) {
            		    if( $productsId==0 || $productsId != $v ) {
            		        $values[] = $v;
            		    }
            		}
            		
            		if( count( $values ) == $limit ) {
            			return $values;
            		}
            	}
            }
        }
        return $values;
    }
    
    /**
     * cookie brown history
     *
     * @return array
     */
    public function getCookieHistory() {
        
    	$cookieVar = $this->_history_cookie_var;
        $cookieManager = Hqw::getApplication()->getRequest()->getCookie();
        $host = Hqw::getApplication()->getRequest()->getHostInfo();
        
        $sideword = crc32( $host );
        $sideword = sprintf("%u",$sideword);
        $value = '';
        
        $value = $cookieManager->getCookie( $cookieVar );
        if( $value ) {
            //decrypt
            $value = Hqw::getApplication()->getSecurityManager()->decrypt( $value, $sideword );
        }else{
            return false;
        }
        return explode( "," ,$value );
    }
    
    /**
     * post history
     *
     * @param int $productsId
     * @return bool
     */
    public function postHistory( $productsId=0 ){
        
        if( !$productsId ) {
        	return false;
        }
        $products = $this->getCookieHistory();
        if( $products !== false ) {
        	if( !in_array( $productsId, $products ) ) {
            	$products[] = $productsId;
            }
        }else{
            $products = array();
            $products[] = $productsId;
        }
        
        if( count( $products ) > 20 ) {
        	array_pop( $products );
        }
        
        $host = Hqw::getApplication()->getRequest()->getHostInfo();
        $sideword = crc32( $host );
        $sideword = sprintf("%u",$sideword);
        
        $cookieVar = $this->_history_cookie_var;
        $cookieManager = Hqw::getApplication()->getRequest()->getCookie();
        
        //encrypt
        $cookieValue = Hqw::getApplication()->getSecurityManager()->encrypt( implode( ",", $products ), $sideword );
        $cookie = new Cookie( $cookieVar, $cookieValue );
        $cookie->domain = $this->getCookieDomain();
        $cookie->expire = Hqw::getApplication()->getComponent("Date")->cTime() + $this->_cookie_30_expire;
        $cookieManager->add( $cookieVar, $cookie );
        
        if( $this->getCookieUser() ) {
        	$user = $this->getCookieUser()->postHistory( $productsId );
        }
    }
    
    public function postSigninLog( $userId ){
        if( !$userId ) {
        	return false;
        }
        $data = array();
        $data['user_id'] = (int)$userId;
    	$data['signin_time'] = ClsFactory::instance( "ClsCommon" )->getDatetime();
        return Hqw::getApplication()->getModels( "user_signin_log" )->insert( $data );
        
    }
}
?>