<?php
class ClsCurrency{
    
    public $_cookie_currency_sign = 'user_currency';
    
    private $_currency = array();
    
    public $_cookie_30_expire = 2592000;
    
    public function setCurrencyData( $value ){
        return $this->_currency = $value;
    }
    
    public function getCurrencyData(){
        if( !empty( $this->_currency ) ) {
        	return $this->_currency;
        }
        
        $cur = Hqw::getApplication()->getModels( "currencies" );
        if( isset( $_SESSION['currency_code'] ) ) {
        	$result = $cur->fetch( array( 'code'=>$_SESSION['currency_code'] ) );
        	if( $result ) {
        		return $this->_currency = $result;
        	}
        }
        
        $cookieVar = $this->_cookie_currency_sign;
        $cookieManager = Hqw::getApplication()->getRequest()->getCookie();
        $value = $cookieManager->getCookie( $cookieVar );
        if( $value ) {
            $result = $cur->fetch( array( 'code'=>$value ) );
        	if( $result ) {
        		return $this->_currency = $result;
        	}
        }
        
        $result = $cur->fetch();
        if( $result ) {
    		return $this->_currency = $result;
    	}
    	
    	return array();
    }
    
    public function setCurrency( $code ){
        
        if( $code ) {
        	$cur = Hqw::getApplication()->getModels( "currencies" );
        	$result = $cur->fetch( array( 'code'=> $code ) );
        	if( $result ) {
        		
        	    $_SESSION['currency_code'] = $result['code'];
        	    
        	    $cookieVar = $this->_cookie_user_sign;
                $cookieManager = Hqw::getApplication()->getRequest()->getCookie();
                
                $cookie = new Cookie( $cookieVar, $result['code'] );
                $cookie->domain = ClsFactory::instance("ClsSignin")->getCookieDomain();
                $cookie->expire = Hqw::getApplication()->getComponent("Date")->cTime() + $this->_cookie_7_expire;
                $cookieManager->add( $cookieVar, $cookie );
                
                return $this->_currency = $result;
        	}
        }
        
        return false;
    }
    
    public function getCurrency() {
        $data = $this->getCurrencyData();
        if( isset( $data['code'] ) ) {
        	return strtoupper( $data['code'] );
        }
    }
    
    
    public function getCurrencySign() {
    	$data = $this->getCurrencyData();
        if( isset( $data['sign'] ) ) {
        	return $data['sign'];
        }
    }
    
    
    public function getCurrencyExchange() {
    	$data = $this->getCurrencyData();
        if( isset( $data['value'] ) ) {
        	return $data['value'];
        }
    }
    
    
    public function getCurrencyValues( $value ) {
        $value = floatval( $value * $this->getCurrencyExchange() );
    	return number_format( round( $value, 2 ), 2, ".", "" );
    }
}
?>