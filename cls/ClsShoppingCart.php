<?php
class ClsShoppingCart {
    
    public $_items = null;
    
    public $_count = null;
    
    public $_subtotal = null;
    
    public $_cart_cookie_var = 'cart';
    
    public $_cart_cookie_expire = 999999999;
    
    public function getProductsCartId( $productsId ) {
        if( !$productsId ) {
        	return false;
        }
        $otime = Hqw::getApplication()->getComponent("Date")->cTime();
        
        $h = date( "H", $otime );
        $i = date( "i", $otime );
        $s = date( "s", $otime );
        $his = $h * 3600 + $i * 60 + $s;
        return sprintf( "%03d", date( 'z', $otime ) ) . "-" . $productsId . "-" . $his;
    }
    
    public function getSideword() {
    	$host = Hqw::getApplication()->getRequest()->getHostInfo();
        $sideword = crc32( $host );
        $sideword = sprintf("%u",$sideword);
        return $sideword;
    }
    
    public function getCookieCart() {
    	$cookieVar = $this->_cart_cookie_var;
    	
    	return isset( $_SESSION[$cookieVar] ) ? $_SESSION[$cookieVar] : array();
    	
    	/*
        $cookieManager = Hqw::getApplication()->getRequest()->getCookie();
        $cookieValue = '';
        $cookieValue = $cookieManager->getCookie( $cookieVar );
        
        $items = array();
        if( $cookieValue !== array() && $cookieValue != '' ) {
            $cookieValue = base64_decode( $cookieValue );
        	$decr = Hqw::getApplication()->getSecurityManager()->decrypt( $cookieValue, $this->getSideword() );
        	$items = json_decode( $decr, true );
        }
        
        return $items;
        */
    }
    
    public function setCookieCart( $items ) {
        $cookieVar = $this->_cart_cookie_var;
        $_SESSION[$cookieVar] = $items;
        return true;
        
        /*
        $sn = ClsFactory::instance("ClsSignin");
        $cookieManager = Hqw::getApplication()->getRequest()->getCookie();
        
        $cookieValue = Hqw::getApplication()->getSecurityManager()->encrypt( json_encode( $items ), $this->getSideword() );
        $cookieValue = base64_encode( $cookieValue );
        
        $cookie = new Cookie( $cookieVar, $cookieValue );
        
        $cookie->expire = Hqw::getApplication()->getComponent("Date")->cTime() + $this->_cart_cookie_expire;
        $cookie->domain = $sn->getCookieDomain();
        $cookieManager->add( $cookieVar, $cookie );
        */
    }
    
    public function clear(){
        $this->_items = null;
        $this->_count = null;
        $this->_subtotal = null;
    }
    
    public function addItems( $params ) {
        if( !isset( $params['id'] ) || $params['id'] == 0 || $params['id'] == '' ) {
        	return false;
        }
        
        if( !isset( $params['quantity'] ) || $params['quantity'] == 0 || $params['quantity'] == '' ) {
        	$params['quantity'] = 1;
        }
        
        $sn = ClsFactory::instance("ClsSignin");
    	if( $sn->getCookieUser() ) {
    		$userId = $sn->getCookieUser()->getUserId();
    		$stc = Hqw::getApplication()->getModels( "shopping_cart" );
    		$data = array();
    		$data['session_id'] = $sn->getSessionId();
    		$data['user_id'] = $userId;
    		$data['checkout_selected'] = 'Y';
    		$data['products_id'] = $params['id'];
    		$data['quantity'] = $params['quantity'];
    		$data['odate'] = Hqw::getApplication()->getComponent("Date")->cDate();
    		if( $stc->insert($data) ){
    		    $cartId = $stc->lastInsertId();
    		    if( $params['options'] ) {
    		    	foreach( $params['options'] as $k => $v ) {
    		    	    $items = array();
    		    	    $items['shopping_cart_id'] = (int)$cartId;
    		    		if( ( $pos = strpos( $k, "txt_" ) ) !== false ) {
    		    		    $items['products_options_id'] = (int)str_replace( "txt_", "", $k);
    		    			$items['products_options_values_id'] = 0;
    		    			$items['products_options_values_text'] = (string)$v;
    		    		}else{
    		    		    $items['products_options_id'] = (int)$k;
    		    		    $items['products_options_values_id'] = (int)$v;
    		    		}
    		    		Hqw::getApplication()->getModels( "shopping_cart_attributes" )->insert( $items );
    		    	}
    		    }
    		    $this->clear();
    		    return $cartId;
    		}
    	}else{
    	    
    	    $items = $this->getCookieCart();
    	    
            $cart = array();
            $cart['shopping_cart_id'] = $this->getProductsCartId( $params['id'] );
            $cart['session_id'] = $sn->getSessionId();
            $cart['products_id'] = $params['id'];
            $cart['checkout_selected'] = 'Y';
            $cart['quantity'] = $params['quantity'];
            $cart['odate'] = Hqw::getApplication()->getComponent("Date")->cDate();
            
            $options = array();
            if( $params['options'] ) {
                foreach( $params['options'] as $k => $v ) {
                	$item = array();
                	$item['shopping_cart_id'] = $cart['shopping_cart_id'];
                	if( ( $pos = strpos( $k, "txt_" ) ) !== false ) {
                	    $item['products_options_id'] = (int)str_replace( "txt_", "", $k);
		    			$item['products_options_values_id'] = 0;
		    			$item['products_options_values_text'] = $v;
                	}else{
                	    $item['products_options_id'] = $k;
		    		    $item['products_options_values_id'] = $v;
		    			$item['products_options_values_text'] = '';
                	}
                	$options[] = $item;
                }
            }
            $items[] = array( 'cart'=>$cart, 'options'=>$options );
            $this->setCookieCart( $items );
            
            return $items;
            
    	}
    	return false;
    }
    
    public function getItems() {
        
        if( $this->_items !== null ) {
        	return $this->_items;
        }
        
        $sn = ClsFactory::instance("ClsSignin");
        $items = array();
        if( $sn->getCookieUser() ){
            $userId = $sn->getCookieUser()->getUserId();
            $stc = Hqw::getApplication()->getModels( "shopping_cart" );
            $stc = $stc->select( "*" );
        	$stc = $stc->join( Hqw::getApplication()->getModels( "shopping_cart_attributes" ), array('on'=>'shopping_cart_id', 'type'=>'left') );
        	$stc = $stc->select( "shopping_cart_id as attr_shopping_cart_id,products_options_id,products_options_values_id,products_options_values_text", Hqw::getApplication()->getModels( "shopping_cart_attributes" ) );
        	$stc = $stc->where( array( 'user_id'=>$userId,'orders_id'=>0 ) );
        	$result = $stc->fetchAll();
        	if( $result ) {
        		foreach( $result as $k => $v ) {
        			if( array_key_exists( $v['shopping_cart_id'], $items ) ) {
        			    $items[$v['shopping_cart_id']]['options'][] = array( 'products_options_id'=>$v['products_options_id'], 'products_options_values_id'=>$v['products_options_values_id'], 'products_options_values_text'=>$v['products_options_values_text'] );
        			}else{
        			    $items[$v['shopping_cart_id']]['cart'] = array( 'shopping_cart_id'=>$v['shopping_cart_id'],'session_id'=>$v['session_id'], 'user_id'=>$v['user_id'], 'checkout_selected'=>$v['checkout_selected'], 'products_id'=>$v['products_id'], 'quantity'=>$v['quantity'], 'odate'=>$v['odate'] );
        			    $items[$v['shopping_cart_id']]['options'][] = array( 'products_options_id'=>$v['products_options_id'], 'products_options_values_id'=>$v['products_options_values_id'], 'products_options_values_text'=>$v['products_options_values_text'] );
        			}
        		}
        	}else{
        	    return false;
        	}
        }else{
            $items = $this->getCookieCart();
        }
        
        $res = array();
		if( !empty($items) ) {
			foreach( $items as $k => $v ) {
				$res[] = new ClsCartProducts( $v['cart'], $v['options'] );
			}
		}
		return $this->_items = $res;
    }
    
    public function updateItemsQuantity( $cartId, $quantity ) {
    	if( !$cartId || !$quantity ) {
    		return false;
    	}
    	
    	$sn = ClsFactory::instance("ClsSignin");
    	
        if( $sn->getCookieUser() ){
            $userId = $sn->getCookieUser()->getUserId();
            $stc = Hqw::getApplication()->getModels( "shopping_cart" );
            
            $stc->where( array( 'user_id'=>$userId, 'shopping_cart_id'=>$cartId ) );
            if( $stc->update( array( 'quantity'=>(int)$quantity ) ) ){
                $this->clear();
                return true;
            }
        }else{
            $items = $this->getCookieCart();
            if( $items ) {
            	foreach( $items as $k => $v ) {
            		if( isset( $v['cart']['shopping_cart_id'] ) && $v['cart']['shopping_cart_id'] == $cartId ) {
            			$items[$k]['cart']['quantity'] = (int)$quantity;
            		}
            	}
            }
            
            $this->setCookieCart( $items );
            
            return true;
            
        }
        return false;
    }
    
    public function removeItems( $cartId ) {
    	if( !$cartId ) {
    		return false;
    	}
    	
    	$sn = ClsFactory::instance("ClsSignin");
    	
        if( $sn->getCookieUser() ){
            $userId = $sn->getCookieUser()->getUserId();
            $stc = Hqw::getApplication()->getModels( "shopping_cart" );
            
            if( $stc->delete( array( 'user_id'=>$userId, 'shopping_cart_id'=>$cartId ) ) ){
                $sca = Hqw::getApplication()->getModels( "shopping_cart_attributes" );
                $sca->delete( array( 'shopping_cart_id'=>$cartId ) );
                $this->clear();
                return true;
            }
        }else{
            $items = $this->getCookieCart();
            if( $items ) {
            	foreach( $items as $k => $v ) {
            		if( isset( $v['cart']['shopping_cart_id'] ) && $v['cart']['shopping_cart_id'] == $cartId ) {
            			unset( $items[$k] );
            		}
            	}
            }
            
            $this->setCookieCart( $items );
            
            return true;
            
        }
        return false;
    	
    }
    
    public function updateItemsCheckout( $cartId, $bool ){
        if( !$cartId ) {
    		return false;
    	}
    	if( $bool == "" || !in_array( $bool, array('Y','N') ) ) {
    		$bool = 'Y';
    	}
    	
    	$sn = ClsFactory::instance("ClsSignin");
    	if( $sn->getCookieUser() ){
            $userId = $sn->getCookieUser()->getUserId();
            $stc = Hqw::getApplication()->getModels( "shopping_cart" );
            
            $stc->where( array( 'user_id'=>$userId, 'shopping_cart_id'=>$cartId ) );
            if( $stc->update( array( 'checkout_selected'=>$bool ) ) ){
                $this->clear();
                return true;
            }
        }else{
            $items = $this->getCookieCart();
            if( $items ) {
            	foreach( $items as $k => $v ) {
            		if( isset( $v['cart']['shopping_cart_id'] ) && $v['cart']['shopping_cart_id'] == $cartId ) {
            			$items[$k]['cart']['checkout_selected'] = $bool;
            		}
            	}
            }
            
            $this->setCookieCart( $items );
            
            return true;
            
        }
        
        
    }
    
    /**
     * 合并未登录之前购物车商品
     * 
     * 增加checkbox选择，选择 是 进入购物车 checkout
     * 
     * 购物车checkout 未登录 只处理cookie里存的商品
     * 
     * 一般情况，登录之后 合并购物车商品
     *
     */
    public function SigninCombine( $checkout="Y" ) {
        
        $sn = ClsFactory::instance("ClsSignin");
        $cookieCart = $this->getCookieCart();
        if( !empty( $cookieCart ) && $sn->getUser() ) {
            $caData = array();
            $cartAttributes = array();
            $stc = Hqw::getApplication()->getModels( "shopping_cart" );
            
            if( $checkout == "N" ) {
                $stc->where( array( 'user_id'=>$sn->getUser()->getUserId() ) );
                $stc->update( array('checkout_selected'=>'N') );
            }
            
            foreach( $cookieCart as $k => $v ) {
                if( !array_key_exists( $v['cart']['shopping_cart_id'], $caData ) ) {
                	$cartId = $v['cart']['shopping_cart_id'];
                	unset($v['cart']['shopping_cart_id']);
                	$v['cart']['user_id'] = $sn->getUser()->getUserId();
                	if( $stc->insert( $v['cart'] ) !== false ){
                	    $caData[$cartId] = $stc->lastInsertId();
                	}
                }
                
                if( isset( $v['options'] ) ) {
                	$cartAttributes = array_merge( $cartAttributes, $v['options'] );
                }
            }
            
            $manyData = $params = array();
            foreach( $cartAttributes as $value ) {
                if( array_key_exists( $value['shopping_cart_id'], $caData ) ) {
                	$value['shopping_cart_id'] = $caData[$value['shopping_cart_id']];
                	$manyData[] = $value;
                	$params = array_merge( $params, array_values( $value ) );
                }
            }
            
            if( !empty( $manyData ) ) {
            	$sca = Hqw::getApplication()->getModels( "shopping_cart_attributes" );
            	$sca->insertMany( $manyData, $params );
            }
        }
    }
    
    public function getSubtotal() {
    	if( $this->_subtotal !== null ) {
    		return $this->_subtotal;
    	}
    	
    	$items = $this->getItems();
    	$count = 0;
    	if( $items ) {
    		foreach( $items as $k=>$v ) {
    		    $count = $count + $v->getProductsTotal();
    		}
    	}
    	return $this->_subtotal = number_format( $count, 2, ".", "" );
    }
    
    public function getCount() {
    	if( $this->_count !== null ) {
    		return $this->_count;
    	}
    	
    	$items = $this->getItems();
    	$count = 0;
    	if( $items ) {
    		foreach( $items as $k=>$v ) {
    		    $this->updateItemsCheckout( $v->getShoppingCartId(), 'Y' );
    		    $count = $count + $v->getQuantity();
    		}
    	}
    	return $this->_count = $count;
    }
    
    public function getCheckoutItems(){
        $items = $this->getItems();
        $result = array();
        if( !empty($items) ) {
            foreach( $items as $k => $v ) {
            	if( $v->getSelected() ) {
            	    $result[] = $v;
            	}
            }
        }
        return $result;
    }
    
    public function getCheckoutCount(){
        $items = $this->getItems();
    	$count = 0;
    	if( $items ) {
    		foreach( $items as $k=>$v ) {
    		    if( $v->getSelected() ) {
    		        $count = $count + $v->getQuantity();
    		    }
    		}
    	}
    	return $count;
    }
    
    public function getCheckoutSubtotal() {
    	$items = $this->getItems();
    	$total = 0;
    	if( $items ) {
    		foreach( $items as $k=>$v ) {
    		    if( $v->getSelected() ) {
    		    	$total = $total + $v->getProductsTotal();
    		    }
    		}
    	}
    	return number_format( $total, 2, ".", "" );
    }
    
    public function getCheckoutWeight(){
        $items = $this->getItems();
    	$total = 0;
    	if( $items ) {
    		foreach( $items as $k=>$v ) {
    		    if( $v->getSelected() ) {
    		    	$total = $total + $v->getProductsWeight();
    		    }
    		}
    	}
    	return $total;
    }
    
    public function isFreeShipping(){
        $items = $this->getItems();
    	if( $items ) {
    		foreach( $items as $k=>$v ) {
    		    if( $v->getSelected() ) {
    		        if( $v->getProductsWeight() !== 0 ){
    		            return false;
    		        }
    		    }
    		}
    	}
    	return true;
    }
    
}
?>