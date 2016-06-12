<?php
class ClsExpress extends ClsModule {
    
    public function getModuleName(){
        return MODULE_SHIPPING;
    }
    
    public function getShippingMethod(){
        
        $shippingPath = Hqw::getApplication()->getBasePath() . DIRECTORY_SEPARATOR . "cls" . DIRECTORY_SEPARATOR . "express";
        $fileHandle = Hqw::getApplication()->getComponent("File");
        $fileList = $fileHandle->GetFiles( $shippingPath );
        
        $methods = array();
        if( !empty( $fileList ) ) {
        	foreach( $fileList as $k => $v ) {
        	    if( pathinfo( $v, PATHINFO_EXTENSION ) == "php" ) {
        	    	include_once $shippingPath . DIRECTORY_SEPARATOR . $v;
        	    	$clsName = pathinfo( $v, PATHINFO_FILENAME );
        	    	$obj = new ReflectionClass( $clsName );
        	    	$shippingClass = $obj->newInstance();
        	    	$methods[$shippingClass->getSortOrder()] = $shippingClass;
        	    }
        	}
        }
        ksort( $methods );
        return $methods;
    }
    
}

interface interfaceShipping {
    
    public function getShippingCost( $weight = 0 );
    
    public function getDeliveryDay();
}
?>