<?php
class ClsOrdersFactory{
    
    private static $CLS_ORDERS = array();
    
    
    public static function instance( $orderId ) {
        if( !$orderId ) {
        	return false;
        }
        
    	if( array_key_exists( $orderId, self::$CLS_ORDERS ) ) {
    		return self::$CLS_ORDERS[$orderId];
    	}else{
    	    return  self::$CLS_ORDERS[$orderId] = new ClsOrders( $orderId );
    	}
    }
    
    public static function instByNumber( $no ){
        if( !$no ) {
        	return false;
        }
        
        $orders = Hqw::getApplication()->getModels( "orders" );
    	$orders = $orders->where( array( 'orders_number'=>$no ) );
    	$base = $orders->fetch();
    	if( $base ) {
    		return ClsOrdersFactory::instance( $base['orders_id'] );
    	}
    	
    	return false;
    }
}
?>