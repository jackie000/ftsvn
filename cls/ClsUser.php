<?php
class ClsUser{
    
    private $_userId;
    
    public $_base = null;
    
    public function __construct( $userId ) {
    	$this->_userId =intval( trim( $userId ) );
    }
    
    
    public function getUserId() {
    	return $this->_userId;
    }
    
    
    public function getBase() {
        if( $this->_base !== null ) {
        	return $this->_base;
        }
        $userModels = Hqw::getApplication()->getModels( "user" );
    	$this->_base = $userModels->fetch( array( 'user_id'=>$this->_userId ) );
    	return $this->_base;
    }
    
    
    public function getName() {
    	$base = $this->getBase();
    	if( $base['firstname'] ) {
    		return $base['firstname'] . " " . $base['lastname'];
    	}elseif( $base['user_email_address'] ){
    	    return $base['user_email_address'];
    	}
    	return '';
    }
    
    /**
     * update user profile base
     *
     * @param array $data
     * @return boolean
     */
    public function updateBase( $data ){
        if( empty( $data ) ) {
        	return false;
        }
        
        $userModels = Hqw::getApplication()->getModels( "user" );
        $userModels = $userModels->where( array( 'user_id'=>$this->getUserId() ) );
        if( $userModels->update( $data ) ){
            $this->_base = null;
            return true;
        }
        return false;
    }
    
    public function postProductsReviews( $data, $tags, $images ) {
    	$data['status'] = 0;
        $data['level'] = ClsFactory::instance("ClsProductsMethod")->getReviewsLevels( $data['rating'] );
        $data['odate'] = ClsFactory::instance( "ClsCommon" )->getDatetime();
        if( Hqw::getApplication()->getModels( "products_reviews" )->insert( $data ) ) {
            $reviewsId = Hqw::getApplication()->getModels( "products_reviews" )->lastInsertId();
            Hqw::getApplication()->getModels( "products_reviews_status" )->insert( array('products_reviews_id'=>$reviewsId) );
            if( !empty( $tags ) && is_array( $tags ) ) {
                $tagsData = $tagsParams = array();
            	foreach( $tags as $k => $v ) {
            		$tagsData[] = array('products_reviews_id'=>$reviewsId, 'products_reviews_tags_id'=>(int)$v);
            		$tagsParams[] = $reviewsId;
            		$tagsParams[] = (int)$v;
            	}
            	if( !empty( $tagsData ) ) {
            		Hqw::getApplication()->getModels( "products_reviews_to_tags" )->insertMany( $tagsData, $tagsParams );
            	}
            }
            
            if( !empty( $images ) && is_array( $images ) ) {
            	$imagesData = $imagesParams = array();
            	foreach( $images as $k => $v ) {
            		$imagesData[] = array('products_reviews_id'=>$reviewsId, 'images'=>$v);
            		$imagesParams[] = $reviewsId;
            		$imagesParams[] = $v;
            	}
            	if( !empty( $imagesData ) ) {
            		Hqw::getApplication()->getModels( "products_reviews_images" )->insertMany( $imagesData, $imagesParams );
            	}
            }
            
            if( isset( $data['products_id'] ) ) {
                $pts = ClsProductsFactory::instance( $data['products_id'] );
            	$pts->postReviewsCount();
            	$pts->updateReviewsRating();
            }
            return true;
        }
        return false;
    }
    
    /**
     * review helpful
     *
     * @param array $data
     * @return boolen
     */
    public function postProductsReviewsHelpful( $data ) {
    	if( empty( $data ) || !isset( $data['products_reviews_id'] ) || $data['products_reviews_id'] == '' ) {
    		return false;
    	}
    	$data['user_id'] = $this->_userId;
    	$data['odate'] = ClsFactory::instance( "ClsCommon" )->getDatetime();
    	if( Hqw::getApplication()->getModels( "products_reviews_helpful" )->insert( $data ) ) {
    	    $sql = " UPDATE " . Hqw::getApplication()->getModels( "products_reviews_status" )->getTable() . " SET " ;
    		if( $data['helpful'] == 'N' ) {
    			$sql .= " helpless= helpless+1 WHERE products_reviews_id=" . $data['products_reviews_id'];
    		}else{
    		    $sql .= " helpful= helpful+1 WHERE products_reviews_id=" . $data['products_reviews_id'];
    		}
    		$command = Hqw::getApplication()->getModels( "products_reviews_status" )->getDbCommand( $sql );
    		return $command->execute();
    	}
    	
    	return false;
    }
    
    
    /**
     * add to favorites
     *
     * @param array $data
     * @return boolen
     */
    public function postFavorites( $data ) {
        
    	if( empty( $data ) || !isset( $data['products_id'] ) || $data['products_id'] == '' ) {
    		return false;
    	}
    	$data['user_id'] = $this->_userId;
    	$data['favorites_date_added'] = ClsFactory::instance( "ClsCommon" )->getDatetime();
    	
    	if( Hqw::getApplication()->getModels( "user_favorites" )->insert( $data ) ) {
    	    $sql = " UPDATE " . Hqw::getApplication()->getModels( "products_status" )->getTable() . " SET favorites = favorites + 1 WHERE products_id=" . $data['products_id'] ;
    	    
    		$command = Hqw::getApplication()->getModels( "products_status" )->getDbCommand( $sql );
    		return $command->execute();
    	}
    	
    	return false;
    }
    
    /**
     * cancel favorites products
     *
     * @param int $productsId
     * @return bool
     */
    public function cancelFavorites( $productsId ) {
    	if( !$productsId ){
    	    return false;
    	}
    	$option = array();
    	$option['products_id'] = $productsId;
    	$option['user_id'] = $this->_userId;
    	if( Hqw::getApplication()->getModels( "user_favorites" )->delete( $option ) ){    	    
    	    $sql = " UPDATE " . Hqw::getApplication()->getModels( "products_status" )->getTable() . " SET favorites = if( ( favorites - 1 ) > 0, favorites - 1, 0 ) WHERE products_id=" . $option['products_id'] ;
    	    $command = Hqw::getApplication()->getModels( "products_status" )->getDbCommand( $sql );
    		return $command->execute();
    	}
    }
    
    /**
     * exists favorites products
     *
     * @param unknown_type $productsId
     * @return unknown
     */
    public function getFavorites( $productsId ) {
        if( !$productsId ) {
        	return false;
        }
    	$uf = Hqw::getApplication()->getModels( "user_favorites" );
    	return $uf->fetch( array( 'products_id'=>$productsId, 'user_id'=>$this->_userId ) );
    }
    
    /**
     * brown history
     *
     * @param int $productsId
     * @return bool
     */
    public function postHistory( $productsId ){
        if( !$productsId ) {
        	return false;
        }
        
        $data = array();
        $data['user_id'] = $this->_userId;
    	$data['browse_time'] = ClsFactory::instance( "ClsCommon" )->getDatetime();
    	$data['products_id'] = $productsId;
        return Hqw::getApplication()->getModels( "user_history" )->insert( $data );
    }
    
    /**
     * history
     *
     * @param int $limit
     * @return array
     */
    public function getHistory( $limit=6 ) {
    	$uh = Hqw::getApplication()->getModels( "user_history" );
    	$uh = $uh->select("products_id");
    	$uh = $uh->group("products_id");
    	$uh = $uh->order("browse_time");
    	$uh = $uh->limit( $limit );
    	return $uh->fetchAll( array( 'user_id'=>$this->getUserId() ) );
    }
    
    /**
     * post new address
     *
     * @param unknown_type $data
     * @param unknown_type $defaultBook
     * @return bool
     */
    public function postAddress( $data, $defaultBook ){
        if( empty( $data ) ) {
    		return false;
    	}
    	$data['user_id'] = $this->_userId;
    	$data['address_date_added'] = ClsFactory::instance( "ClsCommon" )->getDatetime();
    	
    	if( Hqw::getApplication()->getModels( "user_address_book" )->insert( $data ) ) {
    	    $bookId = Hqw::getApplication()->getModels( "user_address_book" )->lastInsertId();
    	    if( $defaultBook === 1 ) {
        	    if( $this->updateBase( array( 'default_address_id'=>$bookId ) ) ) {
        	    	return $bookId;
        	    }
    	    }else{
    	        return $bookId;
    	    }
    	}
    	
    	return false;
    }
    
    public function updateAddress( $addressId, $data, $defaultBook ){
        if( empty( $data ) ) {
    		return false;
    	}
    	$data['address_date_added'] = ClsFactory::instance( "ClsCommon" )->getDatetime();
    	$userAddressModels = Hqw::getApplication()->getModels( "user_address_book" );
        $userAddressModels->where( array( 'user_id'=>$this->getUserId(), 'user_address_book_id'=>(int)$addressId ) );
        if( $userAddressModels->update( $data ) ){
            
            if( $defaultBook === 1 ) {
        	    if( $this->updateBase( array( 'default_address_id'=>(int)$addressId ) ) ) {
        	    	return true;
        	    }
    	    }else{
    	        return true;
    	    }
    	    
        }
        
        return false;
        
    }
    
    /**
     * address number
     *
     * @return int
     */
    public function getAddressNumber(){
        $addressBook = Hqw::getApplication()->getModels( "user_address_book" );
        $addressBook = $addressBook->select(" count(1) as total ");
        
        return $addressBook->fetchColumn( array( 'user_id'=>$this->_userId ) );
    }
    
    
    public function getAddressById( $addressId ){
        $addressBook = Hqw::getApplication()->getModels( "user_address_book" );
        return $addressBook->fetch( array( 'user_id'=>$this->_userId, 'user_address_book_id'=>$addressId ) );
    }
    
    
}

?>