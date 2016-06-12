<?php
class ClsProducts {
    
    private $_productsId;
    
    private $_productsBase = null;
    
    private $_productsAttributes = null;
    
    private $_productsStatus = null;
    
    private $_productsToCategories = null;
    
    private $_productsCategories = null;
    
    private $_productsReviews = null;
    
    private $_productsQuestions = null;
    
    private $_productsMostHelpfulReviews = null;
    
    private $_productsTags = null;
    
    private $_reviews_level = array('all','high','middle','base');
    
    private $_productsReviewsLevels = null;
    
    private $_default_images = null;
    
    private $_images = array();
    
    
    public function getReviewsLevel() {
    	return $this->_reviews_level;
    }
    
    public function getProductsId() {
    	return $this->_productsId;
    }
    
    public function getLanguage() {
    	$language = ClsFactory::instance( "ClsLanguage" );
    	return $language->getLanguage();
    }
    
    public function __construct( $productsId ){
        $this->_productsId = intval( trim( $productsId ) );
    }
    
    
    public function getSalesPrice() {
        //$currency = ClsFactory::instance( "ClsCurrency" );
    	$base = $this->getBase();
    	if( $this->isFree() == false ) {
    		return $base['sale_price'];
    	}
    	
    	return 0;
    }
    
    
    public function getMarketPrice() {
        //$currency = ClsFactory::instance( "ClsCurrency" );
    	$base = $this->getBase();
    	return $base['market_price'];
    }
    
    
    public function getSavePrice() {
    	return $this->getMarketPrice() - $this->getSalesPrice();
    }
    
    
    public function getSavePercent() {
    	return round( $this->getSavePrice() * 100 / $this->getMarketPrice() ) . "%" ;
    }
    
    public function getWeigth(){
        $base = $this->getBase();
        return $base['weight'];
    }
    
    public function isFreeShipping(){
        $base = $this->getBase();
        return $base['free_shipping_status'] == 0 ? false : true;
    }
    
    public function isFree(){
        $base = $this->getBase();
        return $base['free_status'] == 0 ? false : true;
    }
    
    public function setBase( $value ){
        $this->_productsBase = $value;
    }
    
    public function getBase() {
        if( $this->_productsBase != null ) {
        	return $this->_productsBase;
        }
        
        $productsId = $this->getProductsId();
        if( !$productsId ) {
    		return false;
    	}
        $languageId = $this->getLanguage();
        
    	$pts = Hqw::getApplication()->getModels( "products" );
    	$table = $pts->getTable();
    	
    	$pts = $pts->join( Hqw::getApplication()->getModels( "products_description" ), array('on'=>'products_id') );
    	$pts = $pts->where( array( 'language_id'=>$languageId ), "AND", "products_description" );
    	$pts = $pts->where( array( 'products_status'=>1, 'products_id'=>$productsId ) );
    	return $this->_productsBase = $pts->fetch();
    }
    
    public function getProductsAttributes() {
        if( $this->_productsAttributes != null ) {
        	return $this->_productsAttributes;
        }
        
    	$productsId = $this->getProductsId();
    	if( !$productsId ) {
    		return false;
    	}
        $languageId = $this->getLanguage();
        
        $pto = Hqw::getApplication()->getModels( "products_attribute" );
        $pto = $pto->join( Hqw::getApplication()->getModels( "products_options" ), array( 'on'=>'products_options_id' ) );
        $pto = $pto->join( Hqw::getApplication()->getModels( "products_options_values" ), array( 'on'=>'products_options_values_id' ) );
        $pto = $pto->where( array( 'language_id'=>$languageId ), "AND", "products_options" );
        $pto = $pto->where( array( 'products_id'=>$productsId ) );
        $pto = $pto->order( "sort DESC" );
        $pto = $pto->order( "sort DESC", "products_options" );
        $pto = $pto->order( "sort DESC", "products_options_values" );
        $pto = $pto->order( "products_options_values_id ASC", "products_options_values" );
    	return $this->_productsAttributes = $pto->fetchAll();
    }
    
    public function setProductsStatus( $value ) {
    	$this->_productsStatus = $value;
    }
    
    public function getProductStatus() {
        if( $this->_productsStatus != null ) {
        	return $this->_productsStatus;
        }
        
    	$productsId = $this->getProductsId();
    	if( !$productsId ) {
    		return false;
    	}
    	$pts = Hqw::getApplication()->getModels( "products_status" );
    	return $this->_productsStatus = $pts->fetch( array( 'products_id'=>$productsId ) );
    }
    
    
    public function getDefaultImage( $w=0, $h=0 ) {
        
        if( $w == 0 && $h == 0 ) {
        	return false;
        }
        
    	$base = $this->getBase();
    	if( isset( $base['products_images'] ) ) {
    		$image = $base['products_images'];
    	    
    	    $basePath = Hqw::getApplication()->getBasePath();
    	    if( ( $filePath = realpath( $image ) ) === false ){
    	        $filePath = $basePath . DIRECTORY_SEPARATOR . $image;
    	    }
    	    
    	    if ( file_exists( $filePath ) ) {
    	        return str_replace( $basePath, '', $filePath );
    	    }
    	}
    	return false;
    }
    
    public function getProductsImages() {
    	$base = $this->getBase();
    	if( isset( $base['products_images'] ) && $base['products_images'] != "" ) {
    	    $image = $base['products_images'];
    	    
    	    $basePath = Hqw::getApplication()->getBasePath();
    	    if( ( $filePath = realpath( $image ) ) === false ){
    	        $filePath = $basePath . DIRECTORY_SEPARATOR . $image;
    	    }
    	    if ( file_exists( $filePath ) ) {
    	        $fileInfo  = pathinfo($filePath);
                $dirName   = $fileInfo['dirname'];
                $fileName  = $fileInfo['filename'];
                $ext  = $fileInfo['extension'];
                $ext = trim($ext);
                
                $preg      = $dirName . DIRECTORY_SEPARATOR . $fileName . "*";
                $pregArray = glob($preg);
                $result = array();
                if ($pregArray) {
                	foreach ($pregArray as $v) {
                	    if (preg_match("/{$fileName}\.{$ext}|{$fileName}_[0-9]{0,5}\.{$ext}/i",$v)) {
                	    	$result[] = $v;
                	    }
                	}
                }
                return $result;
    	    }
    	}
    	return false;
    }
    
    
    public function getProductsReviews( $level = 'all', $limit=10 ) {
        if( $this->_productsReviews != null ) {
        	return $this->_productsReviews;
        }
        
        $productsId = $this->getProductsId();
    	if( !$productsId ) {
    		return false;
    	}
    	
    	$pr = Hqw::getApplication()->getModels( "products_reviews" );
    	$table = $pr->getTable();
    	$prtt = Hqw::getApplication()->getModels( "products_reviews_to_tags" )->getTable();
    	$pri = Hqw::getApplication()->getModels( "products_reviews_images" )->getTable();
    	$prt = Hqw::getApplication()->getModels( "products_reviews_tags" )->getTable();
    	
    	$pr = $pr->select("*, IFNULL(GROUP_CONCAT( {$pri}.`images` SEPARATOR \",\"),'') as images, IFNULL(GROUP_CONCAT( DISTINCT concat( {$prtt}.`products_reviews_tags_id`,\"-\",{$prt}. `products_reviews_tags_name`) SEPARATOR \",\"),'') as tags");
    	$pr = $pr->select("IFNULL(products_reviews_status.helpful,0) AS helpful,IFNULL(products_reviews_status.helpless,0) AS helpless", "");
    	$pr = $pr->join( Hqw::getApplication()->getModels( "products_reviews_images" ), array( 'on'=>'products_reviews_id', 'type'=>'left' ) );
        $pr = $pr->join( Hqw::getApplication()->getModels( "products_reviews_to_tags" ), array( 'on'=>'products_reviews_id', 'type'=>'left' ) );
        $pr = $pr->join( Hqw::getApplication()->getModels( "products_reviews_status" ), array( 'on'=>'products_reviews_id', 'type'=>'left' ) );
        $pr = $pr->join( Hqw::getApplication()->getModels( "products_reviews_tags" ), array( 'on'=>'products_reviews_tags_id','join'=>"products_reviews_to_tags", 'type'=>'left' ) );
        
        $query = array( 'products_id'=>$productsId, 'status'=>1 );
        if( $level != 'all' ) {
            $types = $this->getReviewsLevel();
            if( in_array( $level, $types ) ) {
                $query['level'] = $level;
    	    }
        }
        $pr = $pr->where( $query );
    	
    	$pr = $pr->group( "products_reviews_id" );
    	$pr = $pr->order( "odate DESC" );
    	$pr = $pr->limit( $limit );
    	
    	return $this->_productsReviews = $pr->fetchAll();
    }
    
    
    public function getProductsQuestions( $limit=6 ) {
        if( $this->_productsQuestions != null ) {
        	return $this->_productsQuestions;
        }
        
    	$productsId = $this->getProductsId();
    	if( !$productsId ) {
    		return false;
    	}
    	
    	$pq = Hqw::getApplication()->getModels( "products_questions" );
    	return $this->_productsQuestions = $pq->where(array('products_id'=>$productsId))->limit( $limit )->order("products_questions_id ASC")->fetchAll();
    }
    
    
    public function getProductsAttributesFormat(){
        $productsId = $this->getProductsId();
    	if( !$productsId ) {
    		return false;
    	}
    	
    	$result = $this->getProductsAttributes();
    	if( $result ) {
    	    $attrubtes = array();
    		foreach( $result as $k => $v ) {
    		    $values = array();
    		    $values['products_options_values_id'] = $v['products_options_values_id'];
    		    $values['products_options_values_name'] = $v['products_options_values_name'];
    		    $values['products_options_values_price'] = $v['products_options_values_price'];
    		    $values['price_prefix'] = $v['price_prefix'];
    		    $values['sort'] = $v['sort'];
    		    $values['show_description'] = $v['show_description'];
    		    $values['attribute_free'] = $v['attribute_free'];
    		    $values['default_attribute_value'] = $v['default_attribute_value'];
    		    $values['application_coupon'] = $v['application_coupon'];
    		    $values['show_order'] = $v['show_order'];
    			if( !array_key_exists( $v['products_options_id'], $attrubtes ) ) {
    			    $item = array();
    			    $item['products_options_id'] = $v['products_options_id'];
    			    $item['products_options_name'] = $v['products_options_name'];
    			    $item['type'] = $v['type'];
    			    
    			    $item['show_order_count'] = $item['default_attribute_value_id'] = 0;
    			    
    			    if( $v['show_order'] == 1 ) {
    			    	$item['show_order_count'] = 1;
    			    }
    			    
    			    if( $v['default_attribute_value'] == 1 ) {
    			    	$item['default_attribute_value_id'] = $v['products_options_values_id'];
    			    }
    			    
    			    $item['values'][$v['products_options_values_id']] = $values;
    			    
    				$attrubtes[$v['products_options_id']] = $item;
    			}else{
    			    if( $v['show_order'] == 1 ) {
    			    	$attrubtes[$v['products_options_id']]['show_order_count']++;
    			    }
    			    
    			    if( $v['default_attribute_value'] == 1 ) {
    			    	$attrubtes[$v['products_options_id']]['default_attribute_value_id'] = $v['products_options_values_id'];
    			    }
    			    
    			    $attrubtes[$v['products_options_id']]['values'][$v['products_options_values_id']] = $values;
    			}
    		}
    		return $attrubtes;
    	}
    	return false;
    }
    
    
    public function getProductsRating() {
    	$status = $this->getProductStatus();
    	$rating = "5";
        if( $status['review_rating'] == 5 ) {
        	$rating = "5";
        }elseif( $status['review_rating'] <= 5 &&  $status['review_rating'] > 4.5 ){
            $rating = "5";
        }elseif( $status['review_rating'] > 4 &&  $status['review_rating'] <= 4.5 ){
            $rating = "4_1";
        }elseif( $status['review_rating'] <= 4 &&  $status['review_rating'] > 3.5 ){
            $rating = "4";
        }elseif( $status['review_rating'] > 3 &&  $status['review_rating'] <= 3.5 ){
            $rating = "3_1";
        }elseif( $status['review_rating'] <= 3 &&  $status['review_rating'] > 2.5 ){
            $rating = "3";
        }elseif( $status['review_rating'] > 2 &&  $status['review_rating'] <= 2.5 ){
            $rating = "2_1";
        }elseif( $status['review_rating'] <= 2 &&  $status['review_rating'] > 1.5 ){
            $rating = "2";
        }
        return $rating;
    }
    
    public function getProductsReviewsCount(){
        $status = $this->getProductStatus();
        if( isset( $status['review'] ) && (int)$status['review'] != 0 ) {
        	return "({$status['review']})";
        }
    }
    
    
    public function getMostHelpfulReviews() {
    	
    	if( $this->_productsMostHelpfulReviews != null ) {
        	return $this->_productsMostHelpfulReviews;
        }
        
        $productsId = $this->getProductsId();
    	if( !$productsId ) {
    		return false;
    	}
    	$table = Hqw::getApplication()->getModels( "products_reviews_status" )->getTable();
    	$pr = Hqw::getApplication()->getModels( "products_reviews" );
    	$pr = $pr->join( Hqw::getApplication()->getModels( "products_reviews_status" ), array( 'on'=>'products_reviews_id' ) );
    	$pr = $pr->condition("{$table}.helpful > :helpful")->params(array('helpful'=>10));
    	$pr = $pr->where( array( 'products_id'=>$productsId, 'status'=>1 ) );
    	$pr = $pr->order( "helpful DESC", "products_reviews_status" );
    	$pr = $pr->limit(3);
    	
    	return $this->_productsMostHelpfulReviews = $pr->fetchAll();
    }
    
    
    public function getProductsTags() {
        
    	if( $this->_productsTags != null ) {
        	return $this->_productsTags;
        }
        
        $productsId = $this->getProductsId();
    	if( !$productsId ) {
    		return false;
    	}
    	
    	$pr = Hqw::getApplication()->getModels( "products_reviews" );
    	$pr = $pr->select("products_id, count(1)  AS tot");
    	$pr = $pr->select("products_reviews_tags_id,products_reviews_tags_name", "products_reviews_tags");
    	$pr = $pr->join( Hqw::getApplication()->getModels( "products_reviews_to_tags" ), array( 'on'=>'products_reviews_id' ) );
    	$pr = $pr->join( Hqw::getApplication()->getModels( "products_reviews_tags" ), array( 'on'=>'products_reviews_tags_id', 'join'=>"products_reviews_to_tags" ) );
    	$pr = $pr->where( array( 'products_id'=>$productsId, 'status'=>1 ) );
    	$pr = $pr->group( "products_reviews_tags_id", "products_reviews_to_tags" );
    	$pr = $pr->order( "tot DESC", '' );
    	
    	$pr = $pr->limit(12);
    	return $this->_productsTags = $pr->fetchAll();
    	
    }
    
    public function getProductsReviewsLevels() {
        
    	if( $this->_productsReviewsLevels != null ) {
        	return $this->_productsReviewsLevels;
        }
        
        $productsId = $this->getProductsId();
    	if( !$productsId ) {
    		return false;
    	}
    	$pr = Hqw::getApplication()->getModels( "products_reviews" );
    	$pr = $pr->select("level, count(1)  AS tot");
    	$pr = $pr->group( "level" );
    	$pr = $pr->where( array( 'status'=>1, 'products_id'=>$productsId ) );
    	return $this->_productsReviewsLevels = $pr->fetchAll();
    }
    
    
    public function postViewsCount() {
        
        $productsId = $this->getProductsId();
    	if( !$productsId ) {
    		return false;
    	}
        $sql = " UPDATE " . Hqw::getApplication()->getModels( "products_status" )->getTable() . " SET view = view + 1 WHERE products_id=" . $productsId ;
        $command = Hqw::getApplication()->getModels( "products_status" )->getDbCommand( $sql );
        return $command->execute();
    	
    }
    
    public function postReviewsCount() {
        
        $productsId = $this->getProductsId();
    	if( !$productsId ) {
    		return false;
    	}
        $sql = " UPDATE " . Hqw::getApplication()->getModels( "products_status" )->getTable() . " SET review = review + 1 WHERE products_id=" . $productsId ;
        $command = Hqw::getApplication()->getModels( "products_status" )->getDbCommand( $sql );
        return $command->execute();
    	
    }
    
    public function updateReviewsRating(){
        $productsId = $this->getProductsId();
    	if( !$productsId ) {
    		return false;
    	}
    	
        $sql = "SELECT ( sum( `rating` ) / count( 1 ) ) AS rating FROM " . Hqw::getApplication()->getModels( "products_reviews" )->getTable() . " WHERE 1 AND `status`=1 AND products_id=" . $productsId;
        $command = Hqw::getApplication()->getModels( "products_status" )->getDbCommand( $sql );
        $rating = $command->fetchColumn();
        $ps = Hqw::getApplication()->getModels( "products_status" );
        $ps = $ps->where( array( 'products_id'=>$productsId ) );
        return $ps->update( array( "review_rating"=>$rating ) );

        
    }
    
    
    public function getProductsToCategories() {
    	
        if( $this->_productsToCategories != null ) {
        	return $this->_productsToCategories;
        }
        
        $productsId = $this->getProductsId();
    	if( !$productsId ) {
    		return false;
    	}
    	
    	$ptc = Hqw::getApplication()->getModels( "products_to_categories" );
    	$ptc = $ptc->select(" * ", "categories_description");
    	$ptc = $ptc->join( Hqw::getApplication()->getModels( "categories" ), array( 'on'=>'categories_id' ) );
    	$ptc = $ptc->join( Hqw::getApplication()->getModels( "products" ), array( 'on'=>'products_id' ) );
    	$ptc = $ptc->join( Hqw::getApplication()->getModels( "categories_description" ), array( 'on'=>'categories_id', 'type'=>"left" ) );
    	$ptc = $ptc->where( array( 'products_id'=>$productsId ) );
    	$ptc = $ptc->where( array( 'language_id'=>$this->getLanguage() ), "AND", "categories_description" );
    	$ptc = $ptc->where( array( 'categories_status'=>1 ), "AND", "categories" );
    	$ptc = $ptc->where( array( 'products_status'=>1 ), "AND", "products" );
    	$ptc = $ptc->order( "category_sort DESC", 'categories' );
    	return $this->_productsToCategories = $ptc->fetchAll();
    }
    
    
    public function getProductsCategories() {
        if( $this->_productsCategories != null ) {
        	return $this->_productsCategories;
        }
        
        $productsId = $this->getProductsId();
    	if( !$productsId ) {
    		return false;
    	}
    	
    	$p = Hqw::getApplication()->getModels( "products" );
    	$p = $p->join( Hqw::getApplication()->getModels( "categories" ), array( 'on'=>'categories_id' ) );
    	$p = $p->join( Hqw::getApplication()->getModels( "categories_description" ), array( 'on'=>'categories_id', 'type'=>"left" ) );
    	$p = $p->where( array( 'products_id'=>$productsId, 'products_status'=>1 ) );
    	$p = $p->where( array( 'language_id'=>$this->getLanguage() ), "AND", "categories_description" );
    	$p = $p->where( array( 'categories_status'=>1 ), "AND", "categories" );
    	return $this->_productsCategories = $p->fetch();
    	
    }

    
}

?>