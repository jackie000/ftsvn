<?php
class ShoppingCartController extends Controller {
    
    public function getLayouts() {
    	return "layout";
    }
    
    public function ActionIndex() {
        $data = array();
    	$sc = ClsFactory::instance("ClsShoppingCart");
    	$data['sc'] = $sc;
    	$data['cart'] = $sc->getItems();
    	//$data['cart'] = '';
    	$this->render( "index", $data );
    }
    
    public function ActionHandleBuy() {
        $re = Hqw::getApplication()->getRequest();
        if( $re->getIsPostRequest() ){
            
            if( !isset( $_POST['id'] ) || $_POST['id'] == '' ) {
            	printScreen::tips( array( 'msg'=>CART_DONT_PRODUCTS_ID ) );
            }else{
                $_POST['id'] = intval( $_POST['id'] );
            }
            
            if( !isset( $_POST['quality'] ) || $_POST['quality'] == "" ) {
            	$_POST['quality'] = 1;
            }else{
                $_POST['quality'] = intval( $_POST['quality'] );
            }
            
            if( $_POST['options'] ) {
                $flag = $_POST['options'];
                $values = array_values( $_POST['options'] );
            	$pts = ClsProductsFactory::instance( $_POST['id'] );
            	if( $pts->getBase() ) {
            		$attributes = $pts->getProductsAttributes();
            		foreach( $attributes as $k => $v ) {
            		    if( $v['show_order'] == 1 ) {
            		        $optionsKey = $v['products_options_id'];
            		        if( $v['type'] == 'text' ) {
            		        	$optionsKey = 'txt_' . $optionsKey;
            		        }
            		        
            		        if( array_key_exists( $optionsKey, $_POST['options'] ) && in_array( $v['products_options_values_id'], $values ) ) {
            		        	unset( $flag[$optionsKey] );
            		        }elseif( array_key_exists( $optionsKey, $_POST['options'] ) && $v['type'] == 'text' ){
            		            unset( $flag[$optionsKey] );
            		        }
            		    }
            		}
            		if( !empty( $flag ) ) {
            			printScreen::tips( array( 'msg'=>CART_ATTRIBUTES_NOT_MATCH ) );
            		}
            		
            	}else{
            	    printScreen::tips( array( 'msg'=>CART_PRODUCTS_NOT_FOUND ) );
            	}
            }
            //additions cart
            if( ClsFactory::instance("ClsShoppingCart")->addItems( $_POST ) !== false ) {
                if( $_POST['options'] ) {
                    $pop = http_build_query( $_POST['options'], "", "#" );
                    $b64 = base64_encode( $pop );
                }
            	printScreen::redirect( Hqw::getApplication()->createUrl('ShoppingCart/Success', array( 'id'=>$_POST['id'], 'op'=> $b64 )) );
            }else{
                printScreen::tips( array( 'msg'=>CART_PRODUCTS_NOT_ADDED ) );
            }
            
        }else{
            printScreen::back();
        }
    }
    
    public function ActionSuccess() {
        $data = array();
        if( !isset($_GET['id']) || $_GET['id'] == '' ) {
        	printScreen::tips( array( 'msg'=>CART_DONT_PRODUCTS_ID ) );
        }
        
        $productsId = $_GET['id'];
        if( isset( $_GET['op'] ) && $_GET['op'] != '' ) {
            $d64 = urldecode( base64_decode( $_GET['op'] ) );
            $options = explode( "#", $d64 );
            $cartOptions = array();
            foreach( $options as $v ) {
            	if( ( $pos = strpos( $v, "=" ) ) !== false ) {
            	    $item = array();
            	    $key = substr( $v, 0, $pos );
            	    if( ( $kpos = strpos( $key, "txt_" ) ) !== false ) {
            	        $item['products_options_id'] = (int)str_replace( "txt_", "", $key );
            	        $item['products_options_values_id'] = 0;
            	        $item['products_options_values_text'] = substr( $v, $pos+1 );
            	    }else{
            	        $item['products_options_id'] = (int)$key;
            	        $item['products_options_values_id'] = (int)substr( $v, $pos+1 );;
            	    }
            	    $cartOptions[] = $item;
            	}
            }
            $data['oProducts'] = new ClsCartProducts( array('products_id'=>$productsId), $cartOptions );
        }
        $pts = ClsProductsFactory::instance( $productsId );
        $data['productBase'] = $pts->getBase();
        $data['pts'] = $pts;
    	$this->render( "success", $data );
    }
    
    public function ActionUpdateQuantity() {
        if( !isset( $_POST['cartId'] ) || $_POST['cartId'] == '' ) {
        	echo 0;
        	exit;
        }
        
        if( !isset( $_POST['quantity']) || $_POST['quantity'] == "" || $_POST['quantity'] == 0 ) {
        	$_POST['quantity'] = 1;
        }
        
        $sc = ClsFactory::instance("ClsShoppingCart");
    	if( $sc->updateItemsQuantity( $_POST['cartId'], $_POST['quantity'] ) ){
    	    $currency = ClsFactory::instance( "ClsCurrency" );
    	    echo $currency->getCurrency() . " " . $currency->getCurrencySign() . $currency->getCurrencyValues( $sc->getCheckoutSubtotal() ) . "###" . $sc->getCheckoutCount();
    	    exit;
    	}else{
    	    echo 0;
        	exit;
    	}
    }
    
    public function ActionUpdateCheckoutSelected(){
        if( !isset( $_POST['checkout_select'] ) || empty( $_POST['checkout_select'] ) || !is_array( $_POST['checkout_select'] ) ) {
            echo 0;
            exit;
        }
        
        $sc = ClsFactory::instance("ClsShoppingCart");
        foreach( $_POST['checkout_select'] as $k=>$v ){
            $sc->updateItemsCheckout( $k, $v );
        }
        
        $currency = ClsFactory::instance( "ClsCurrency" );
        echo $currency->getCurrency() . " " . $currency->getCurrencySign() . $currency->getCurrencyValues( $sc->getCheckoutSubtotal() ) . "###" . $sc->getCheckoutCount();
        exit;
    }
    
    public function ActionHandleRemove() {
        
        if( !isset( $_POST['cartId'] ) || $_POST['cartId'] == '' ) {
        	echo 0;
        	exit;
        }
        
        $sc = ClsFactory::instance("ClsShoppingCart");
		if( $sc->removeItems( $_POST['cartId'] ) ){
			$items = $sc->getItems();
    	    if( !empty( $items ) ){
    	        $currency = ClsFactory::instance( "ClsCurrency" );
        	    echo $currency->getCurrency() . " " . $currency->getCurrencySign() . $currency->getCurrencyValues( $sc->getCheckoutSubtotal() ) . "###" . $sc->getCheckoutCount();
        	    exit;
    	    }else{
    	        echo "empty";
    	        exit;
    	    }
    	}else{
    	    echo 0;
        	exit;
    	}
    }
    
}
?>
