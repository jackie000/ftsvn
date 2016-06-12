<?php
class ClsCartProducts{
    
    private $_cart = null;
    private $_options =  null;
    private $_attributes = null;
    private $_attributes_price = 0;
    
    public function __construct( $cart, $options ) {
    	$this->_cart = $cart;
    	$this->_options = $options;
    }
    
    
    public function getShoppingCartId() {
    	return $this->_cart['shopping_cart_id'];
    }
    
    
    public function getQuantity() {
    	return (int)$this->_cart['quantity'];
    }
    
    public function getSelected(){
        if( isset( $this->_cart['checkout_selected'] ) && strtoupper( $this->_cart['checkout_selected'] ) == 'Y' ) {
        	return true;
        }
        
        return false;
    }
    
    
    public function getProducts() {
        if( !isset( $this->_cart['products_id'] ) || $this->_cart['products_id'] == 0 || $this->_cart['products_id'] == '' ) {
    		return false;
    	}
    	$productsId = (int)$this->_cart['products_id'];
    	return ClsProductsFactory::instance( $productsId );
    }
    
    public function updateOrder( $orderId ){
        
        $stc = Hqw::getApplication()->getModels( "shopping_cart" );
        $stc->where( array( 'shopping_cart_id'=>$this->getShoppingCartId() ) );
        if( $stc->update( array( 'orders_id'=>(int)$orderId ) ) ){
            return true;
        }
        return false;
    }
    
    public function getProductsWeight(){
        if( ( $pts = $this->getProducts() ) !== false ){
            if( $pts->isFreeShipping() === false ) {
                return $pts->getWeigth() * (int)$this->_cart['quantity'];
            }
        }
        return 0;
    }
    
    public function getProductsSales(){
        if( !isset( $this->_cart['products_id'] ) || $this->_cart['products_id'] == 0 || $this->_cart['products_id'] == '' ) {
    		return 0;
    	}
    	$this->getProductsOptionsToValues();
    	$productsId = (int)$this->_cart['products_id'];
    	$pts = ClsProductsFactory::instance( $productsId );
    	return number_format( ( $pts->getSalesPrice() + $this->_attributes_price ) , 2, ".", "" );
    }
    
    
    public function getProductsTotal() {
    	if( !isset( $this->_cart['products_id'] ) || $this->_cart['products_id'] == 0 || $this->_cart['products_id'] == '' ) {
    		return 0;
    	}
    	if( !isset( $this->_cart['quantity'] ) || $this->_cart['quantity'] == 0 || $this->_cart['quantity'] == '' ) {
    		$this->_cart['quantity'] = 1;
    	}
    	
    	$productsId = (int)$this->_cart['products_id'];
    	return number_format( ( $this->getProductsSales() ) * (int)$this->_cart['quantity'], 2, ".", "" );
    }
    
    
    public function getProductsOptionsToValues() {
        if( $this->_attributes != null ) {
        	return $this->_attributes;
        }
        
    	if( !isset( $this->_cart['products_id'] ) || $this->_cart['products_id'] == 0 || $this->_cart['products_id'] == '' ) {
    		return false;
    	}
    	
    	if( $this->_options == null || $this->_options == '' || empty( $this->_options ) ) {
    		return false;
    	}
    	
    	$productsId = (int)$this->_cart['products_id'];
    	$pts = ClsProductsFactory::instance( $productsId );
    	$attributes = $pts->getProductsAttributesFormat();
    	$result = array();
    	foreach( $this->_options as $k => $v ) {
    	    $opid = $v['products_options_id'];
    	    $opvid = $v['products_options_values_id'];
    		if( array_key_exists( $opid, $attributes ) ) {
    			if( isset( $attributes[$opid]['type'] ) && $attributes[$opid]['type'] == "text" ) {
    			    $cu = current( $attributes[$opid]['values'] );
    			    $result[] = array(
    			                     'products_options_id'=>$opid, 
    			                     'products_options_name'=>$attributes[$opid]['products_options_name'], 
    			                     'products_options_values_id'=>$opvid, 
    			                     'products_options_values_name'=>$v['products_options_values_text'],
    			                     'products_options_values_price'=>$cu['products_options_values_price'], 
    			                     'price_prefix'=>$cu['price_prefix']
    			    );
    			}elseif( array_key_exists( $opvid, $attributes[$opid]['values'] ) ){
    			    if( $attributes[$opid]['values'][$opvid]['price_prefix'] != "" && $attributes[$opid]['values'][$opvid]['products_options_values_price'] != 0 ) {
    			        if( $attributes[$opid]['values'][$opvid]['price_prefix'] == "+" ) {
    			        	$this->_attributes_price += floatval($attributes[$opid]['values'][$opvid]['products_options_values_price']);
    			        }else{
    			            $this->_attributes_price -= floatval($attributes[$opid]['values'][$opvid]['products_options_values_price']);
    			        }
    			        
    			        $result[] = array(
    			                     'products_options_id'=>$opid, 
    			                     'products_options_name'=>$attributes[$opid]['products_options_name'], 
    			                     'products_options_values_id'=>$opvid, 
    			                     'products_options_values_name'=>$attributes[$opid]['values'][$opvid]['products_options_values_name'],
    			                     'products_options_values_price'=>$attributes[$opid]['values'][$opvid]['products_options_values_price'], 
    			                     'price_prefix'=>$attributes[$opid]['values'][$opvid]['price_prefix'],
    			                     'desc'=>" ( " . $attributes[$opid]['values'][$opvid]['price_prefix'] . number_format( $attributes[$opid]['values'][$opvid]['products_options_values_price'], 2, ".", "" ) . " )"
    			        );
    			    	
    			    }else{
    			        $result[] = array(
    			                     'products_options_id'=>$opid, 
    			                     'products_options_name'=>$attributes[$opid]['products_options_name'], 
    			                     'products_options_values_id'=>$opvid, 
    			                     'products_options_values_name'=>$attributes[$opid]['values'][$opvid]['products_options_values_name'],
    			                     'products_options_values_price'=>$attributes[$opid]['values'][$opvid]['products_options_values_price'], 
    			                     'price_prefix'=>$attributes[$opid]['values'][$opvid]['price_prefix']
    			        );
    			    }
    			}
    		}
    	}
    	return $this->_attributes = $result;
    }
    
    
}

?>