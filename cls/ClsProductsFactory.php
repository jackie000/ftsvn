<?php
class ClsProductsFactory{
    
    private static $CLS_PRODUCTS = array();
    
    
    public static function instance( $productsId ) {
        if( !$productsId ) {
        	return false;
        }
        
    	if( array_key_exists( $productsId, self::$CLS_PRODUCTS ) ) {
    		return self::$CLS_PRODUCTS[$productsId];
    	}else{
    	    return  self::$CLS_PRODUCTS[$productsId] = new ClsProducts( $productsId );
    	}
    }
    
}
?>