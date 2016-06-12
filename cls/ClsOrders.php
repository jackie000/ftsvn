<?php
class ClsOrders {
    
    private $_orderId;
    
    private $_orderNumber = null;
    
    private $_orderBase = null;
    
    private $_currency = null;
    
    public function __construct( $orderId ){
        $this->_orderId = intval( trim( $orderId ) );
    }
    
    public function getId(){
        return $this->_orderId;
    }
    
    public function setId( $value ){
        return $this->_orderId = $value;
    }
    
    public function getNumber(){
        $base = $this->getBase();
        return $base['orders_number'];
    }
    
    public function getBase(){
        if( $this->_orderBase != null ) {
        	return $this->_orderBase;
        }
        
        $orderId = $this->getId();
        if( !$orderId ) {
    		return false;
    	}
    	
    	$orders = Hqw::getApplication()->getModels( "orders" );
    	$table = $orders->getTable();
    	
    	$orders = $orders->where( array( 'orders_id'=>$orderId ) );
    	return $this->_orderBase = $orders->fetch();
    }
    
    public function setBase( $value ){
        return $this->_orderBase = $value;
    }
    
    public function getCurrency(){
        if( $this->_currency != null ) {
        	return $this->_currency;
        }
        
        $base = $this->getBase();
        
        $cur = Hqw::getApplication()->getModels( "currencies" );
        $result = $cur->fetch( array( 'code'=>$base['currency'] ) );
        if( $result ) {
        	$result['value'] = $base['currency_value'];
        }else{
            $result = array();
            $result['value'] = $base['currency_value'];
            $result['code'] = $base['currency'];
            $result['sign'] = "$";
        }
        
        $cr = new ClsCurrency();
        $cr->setCurrencyData( $result );
        return $cr;
    }
    
    public function getProducts(){
        
        $ordersProducts = Hqw::getApplication()->getModels( "orders_products" );
        
        $opTable = $ordersProducts->getTable();
    	$ordersProducts = $ordersProducts->join( Hqw::getApplication()->getModels( "orders_products_attributes" ), array( 'on'=>'orders_products_id' ) );
    	$ordersProducts = $ordersProducts->where( array( 'orders_id'=>$this->getId() ) );
    	$data = $ordersProducts->fetchAll();
    	
    	$cr = $this->getCurrency();
    	$result = array();
    	if( $data ) {
    		foreach( $data as $v ) {
		        $atts = array( 'orders_products_options_id'=>$v['orders_products_options_id'], 'orders_products_options_name'=>$v['orders_products_options_name'], 
		        'orders_products_options_values_id'=>$v['orders_products_options_values_id'], 'orders_products_options_values_name'=>$v['orders_products_options_values_name'],
		        'orders_products_options_values_price'=>$v['orders_products_options_values_price'], 'price_prefix'=>$v['price_prefix']
		         );
		         if( $v['price_prefix'] != "" && $v['orders_products_options_values_price'] != 0 ) {
		             $atts['desc'] = " ( " . $v['price_prefix'] . number_format( $cr->getCurrencyValues( $v['orders_products_options_values_price'] ), 2, ".", "" ) . " )";
		         }
		         
    		    if( array_key_exists( $v['orders_products_id'], $result ) ) {
    		    	$result[$v['orders_products_id']]['attributes'][] = $atts;
    		    }else{
    		        $tmp = array();
    		        $tmp[] = $atts;
    		        $result[$v['orders_products_id']] = array( 'orders_id'=>$v['orders_id'], 'products_id'=>$v['products_id'], 'products_name'=>$v['products_name'], 'products_code'=>$v['products_code'],
    		          'products_price'=>$v['products_price'], 'products_quantity'=>$v['products_quantity'], 'final_price'=>$v['final_price'], 'products_is_free'=>$v['products_is_free'],
    		          'attributes'=> $tmp
    		         );
    		    }
    		}
    	}
    	return $result;
    }
    
    
}
?>