<?php
class ClsPayment extends ClsModule {
    
    //install module run
    public function init(){
        $this->install();
        return true;
    }
    
    public function getModuleName(){
        return MODULE_PAYMENT;
    }
    
    public function getPaymentMethod(){
        
        $paymentPath = Hqw::getApplication()->getBasePath() . DIRECTORY_SEPARATOR . "cls" . DIRECTORY_SEPARATOR . "payment";
        $fileHandle = Hqw::getApplication()->getComponent("File");
        $fileList = $fileHandle->GetFiles( $paymentPath );
        
        $methods = array();
        if( !empty( $fileList ) ) {
        	foreach( $fileList as $k => $v ) {
        	    if( pathinfo( $v, PATHINFO_EXTENSION ) == "php" ) {
        	    	include_once $paymentPath . DIRECTORY_SEPARATOR . $v;
        	    	$clsName = pathinfo( $v, PATHINFO_FILENAME );
        	    	$obj = new ReflectionClass( $clsName );
        	    	$paymentClass = $obj->newInstance();
        	    	$methods[] = $paymentClass;
        	    }
        	}
        }
        
        if( count( $methods ) > 0 ) {
            $cc = count( $methods );
        	for( $i = 0; $i < $cc; $i++ ){
        	    for( $j = $cc-1; $j > $i; $j-- ){
        	        if( $methods[$j]->getSortOrder() < $methods[$j-1]->getSortOrder() ) {
        	        	$tmp = $methods[$j];
        	        	$methods[$j] = $methods[$j-1];
        	        	$methods[$j-1] = $tmp;
        	        }
        	    }
        	}
        }
        return $methods;
    }
    
    
    public function getPayment( $name ){
        $methods = $this->getPaymentMethod();
        if( !empty( $methods ) ) {
        	foreach( $methods as $k => $v ) {
        		if( strtolower( $v->getTitle() ) == strtolower( $name ) ) {
        			return $v;
        		}
        	}
        }
        
        return false;
    }
}

interface interfacePayment {
    
    public function isEnabled();
    
    public function processSubmit();
    
    public function afterSubmit();
    
}
?>