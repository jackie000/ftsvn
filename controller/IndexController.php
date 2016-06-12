<?php
class IndexController extends Controller{
    
    public function getLayouts() {
    	return "layout";
    }
    
    public function ActionIndex() {
        $data = array();
    	$this->render( "index", $data );
    }
    
    public function getMeta(){
        $clsSeo = ClsFactory::instance("ClsSeo");
        $currency = ClsFactory::instance( "ClsCurrency" );
        if( isset($_GET['categoryId']) && $_GET['categoryId'] != '' ) {
            $categoriesId = $_GET['categoryId'];
            $categoriesId = explode("_",$categoriesId);
            $categoriesId = array_pop( $categoriesId );
            $clsCate = ClsFactory::instance( "ClsCategories" );
            $categories = $clsCate->getCategories();
            $category = array();
            if( !empty( $categories ) ) {
            	foreach ($categories as $k => $v) {
            		if( $v['categories_id'] == $categoriesId ) {
            			$category = $v;
            			break;
            		}
            	}
            }
            if( !empty( $category ) && $category['meta_title'] != "" ) {
            	echo "
            	<title>" . ucwords( strtolower( $category['meta_title'] ) ) . "</title>\r\n
                <meta name=\"description\" content=\"" . ucwords( strtolower( $category['meta_description'] ) ) . "\"/>\r\n
                <meta name=\"keywords\" content=\"" . ucwords( strtolower( $category['meta_keywords'] ) ) . "\"/>\r\n
            	";
            }else{
                $postfix = $prefix = "";
                if( $clsSeo->getValue( 'SEO_PREFIX' ) ) {
            		$prefix = $clsSeo->getValue( 'SEO_PREFIX' );
            		$prefix = ucwords( strtolower( $prefix ) );
            	}
            	
            	if( $clsSeo->getValue( 'SEO_CATE_PREFIX' ) ) {
            		$postfix = $clsSeo->getValue( 'SEO_CATE_PREFIX' );
            		$postfix = ucwords( strtolower( $postfix ) );
            	}
            	
                echo "
            	<title>" . $prefix . " " . ucwords( strtolower( $category['categories_name'] ) ) . $postfix . "</title>\r\n
                <meta name=\"description\" content=\"" . $prefix . " " . ucwords( strtolower( $category['categories_name'] ) ) . $postfix . "\"/>\r\n
                <meta name=\"keywords\" content=\"" . ucwords( strtolower( $category['categories_name'] ) ) . ", " . $prefix . " " . ucwords( strtolower( $category['categories_name'] ) ) . "\"/>\r\n
            	";
            }
        	
        }elseif( isset($_GET['productsId']) && $_GET['productsId'] != '' ) {
            
            $pts = ClsProductsFactory::instance( $_GET['productsId'] );
            $productBase = $pts->getBase();
            if( $productBase ) {
                $title = $keywords = $description = $prefix = "";
                $salesPrice = $currency->getCurrencyValues( $pts->getSalesPrice() );
                if( $clsSeo->getValue( 'SEO_PREFIX' ) ) {
            		$prefix = $clsSeo->getValue( 'SEO_PREFIX' );
            	}
            	
                if( isset( $productBase['meta_title'] ) && $productBase['meta_title'] != '' ) {
                    $title = $productBase['meta_title'];
                    $title = $clsSeo->handleMeta( $title );
                }else{
                    if( $clsSeo->getValue( 'seoProductsTitle' ) ) {
                		$title = $clsSeo->getValue( 'seoProductsTitle' );
                		$title = $clsSeo->handleMeta( $title );
                	}else{
                	    if( $prefix ) {
                	    	$title = $prefix . " " . $productBase['products_name'] . ", " . $productBase['products_code'] . ", " . $currency->getCurrencySign() . $salesPrice;
                	    }else{
                	        $title = $productBase['products_name'] . ", " . $productBase['products_code'] . ", " . $currency->getCurrencySign() . $salesPrice;
                	    }
                	    
                	}
                }
                
                if( isset( $productBase['meta_keywords'] ) && $productBase['meta_keywords'] != '' ) {
                    $keywords = $productBase['meta_keywords'];
                    $keywords = $clsSeo->handleMeta( $keywords );
                }else{
                    if( $clsSeo->getValue( 'seoProductsKeywords' ) ) {
                		$keywords = $clsSeo->getValue( 'seoProductsKeywords' );
                		$keywords = $clsSeo->handleMeta( $keywords );
                	}else{
                	    $productAttribute = $pts->getProductsAttributesFormat();
                    	$attribute = array();
                    	
                    	if( $prefix ) {
                	    	$attribute[] = $prefix . " " . $productBase['products_name'];
                	    }else{
                	        $attribute[] = $productBase['products_name'];
                	    }
                    	
                    	if( !empty( $productAttribute ) ) {
                    		foreach( $productAttribute as $k => $v ) {
                    		    if( $v['type'] == "readonly" ) {
                    		        if( is_array( $v['values'] ) && !empty( $v['values'] ) ) {
                    		            foreach( $v['values'] as $kk => $vv ) {
                    		                if( $vv['show_description'] == 1 ) {
                    		                    $attribute[] = $v['products_options_name'] . ":" . $vv['products_options_values_name'];
                    		                }
                    		            }
                    		        }
                    		    }
                    		}
                    	}
                    	$keywords = implode( ", ", $attribute  );
                	}
                }
                
                if( isset( $productBase['meta_description'] ) && $productBase['meta_description'] != '' ) {
                    $description = $productBase['meta_description'];
                    $description = $clsSeo->handleMeta( $description );
                }else{
                    if( $clsSeo->getValue( 'seoProductsDescription' ) ) {
                		$description = $clsSeo->getValue( 'seoProductsDescription' );
                		$description = $clsSeo->handleMeta( $description );
                	}else{
                	    $toCategories = $pts->getProductsToCategories();
                    	$categories = array();
                    	if( $prefix ) {
                	    	$categories[] = $prefix . " " . $productBase['products_name'];
                	    }else{
                	        $categories[] = $productBase['products_name'];
                	    }
                    	
                    	if( $toCategories ) {
                    		foreach( $toCategories as $k => $v ) {
                    			$categories[] = $v['categories_name'];
                    		}
                    	}
                    	$description = implode( ", ", $categories );
                	}
                }
                
                if( strpos( $title, "{category}") !== false ||  strpos( $keywords, "{category}") !== false || strpos( $description, "{category}") !== false) {
                	$pCategory = $pts->getProductsCategories();
                	if( !empty( $pCategory ) && isset( $pCategory['categories_name'] ) ) {
                		$title = str_replace( "{category}", $pCategory['categories_name'], $title );
            	    	$keywords = str_replace( "{category}", $pCategory['categories_name'], $keywords );
            	    	$description = str_replace( "{category}", $pCategory['categories_name'], $description );
                	}else{
                	    $title = str_replace( "{category}", "", $title );
            	    	$keywords = str_replace( "{category}", "", $keywords );
            	    	$description = str_replace( "{category}", "", $description );
                	}
                }
                
                
                if( strpos( $title, "{categories}") !== false ||  strpos( $keywords, "{categories}") !== false || strpos( $description, "{categories}") !== false) {
                    $toCategories = $pts->getProductsToCategories();
                    $categories = array();
                    if( $toCategories ) {
                    	foreach( $toCategories as $k => $v ) {
                    		$categories[] = $v['categories_name'];
                    	}
                    }
                    if( !empty( $categories ) ) {
                        $str = implode( ", ", $categories );
                    	$title = str_replace( "{categories}", $str, $title );
            	    	$keywords = str_replace( "{categories}", $str, $keywords );
            	    	$description = str_replace( "{categories}", $str, $description );
                    }else{
                        $title = str_replace( "{categories}", "", $title );
            	    	$keywords = str_replace( "{categories}", "", $keywords );
            	    	$description = str_replace( "{categories}", "", $description );
                    }
                }
                
                
                foreach( $productBase as $k => $v ) {
        	        if( $k == "sale_price" ) {
        	        	$v = $currency->getCurrencySign() . $currency->getCurrencyValues( $pts->getSalesPrice() );
        	        }
        	    	$title = str_replace( "{" . $k . "}", $v, $title );
        	    	$keywords = str_replace( "{" . $k . "}", $v, $keywords );
        	    	$description = str_replace( "{" . $k . "}", $v, $description );
        	    }
        	    $title = ucwords( strtolower( $title ) );
                $keywords = ucwords( strtolower( $keywords ) );
                $description = ucwords( strtolower( $description ) );
                
                echo "
            	<title>{$title}</title>\r\n
            	<meta name=\"description\" content=\"{$description}\"/>\r\n
                <meta name=\"keywords\" content=\"{$keywords}\"/>\r\n
            	";
            }
        }elseif( isset($_GET['keywords']) || $_GET['keywords'] != '' ){
            echo "
            <title>Search Results : Formal Dresses, Cheap Wedding Dresses,Discount Prom Dresses for Sale - Ftmer.com</title>
            <meta name=\"description\" content=\"Search Results - Cheap formal dresses,Buy cheap formal dresses,Shop cheap formal dresses,Online cheap formal dresses,Best cheap formal dresses,Wholesale cheap prom dresses,Discount cheap prom dresses,Buy cheap prom dresses,Shop cheap prom dresses,Online cheap prom dresses,Best cheap prom dresses.\" />
            <meta name=\"keywords\" content=\"wedding dresses,formal dresses,prom dresses,online formal dresses,online prom dresses,cheap wedding dresses, formal dresses, prom dresses, wedding dresses search result.\" />
        	";
        }else{
            echo "
            <title>Formal Dresses, Cheap Wedding Dresses,Discount Prom Dresses for Sale - Ftmer.com</title>
            <meta name=\"description\" content=\"Cheap formal dresses,Buy cheap formal dresses,Shop cheap formal dresses,Online cheap formal dresses,Best cheap formal dresses,Wholesale cheap prom dresses,Discount cheap prom dresses,Buy cheap prom dresses,Shop cheap prom dresses,Online cheap prom dresses,Best cheap prom dresses.\" />
            <meta name=\"keywords\" content=\"wedding dresses,formal dresses,prom dresses,online formal dresses,online prom dresses,cheap wedding dresses, formal dresses, prom dresses, wedding dresses\" />
        	";
        }
    }
    
    public function ActionList() {
        $data = array();
        if( !isset($_GET['categoryId']) || $_GET['categoryId'] == '' ) {
        	printScreen::tips( array( 'msg'=>LIST_DONT_CATEGORIES_ID ) );
        }
        
        $categoriesId = $_GET['categoryId'];
        $categoriesId = explode("_",$categoriesId);
        $categoriesId = array_pop( $categoriesId );
        $data['categoryId'] = $categoriesId;
        
        $clsCate = ClsFactory::instance( "ClsCategories" );
        $categories = $clsCate->getCategories();
        $categoriesName = array();
        foreach( $categories as $k => $v ) {
        	$categoriesName[$k] = $v['categories_name'];
        }
        
        array_multisort( $categoriesName, SORT_ASC, $categories );
        $data['categories'] = $categories;
        
        $clsCommon = ClsFactory::instance( "ClsCommon" );
        $categoriesIds = $clsCommon->getCategoriesChildren( $categories, $categoriesId );
        
        $ids = array();
        $ids[] = $categoriesId;
        if( !empty( $categoriesIds ) ) {
            foreach( $categoriesIds as $k => $v ) {
            	$ids[] = $v['categories_id'];
            }
        }
        
        $language = ClsFactory::instance( "ClsLanguage" );
    	$languageId = $language->getLanguage();
        
        $attribute = array();
        $attrSQL = $mainSQL = "";
        if( isset($_GET['attribute']) && $_GET['attribute'] != '' ) {
        	$attribute = explode( "_", $_GET['attribute'] );
        	$attribute = array_map( "addslashes", $attribute );
        	
        	$whereAttributeIds = array();
        	if( !empty( $attribute ) ) {
        		foreach( $attribute as $v ) {
        			if( ( $pos = strpos( $v, "-" ) ) !== false && ( $item = explode( "-", $v ) ) ) {
        			    $whereAttributeIds[$item[0]][] = $item[1];
        			}
        		}
        	}
        	
        	$where = $this->attributeWhere( $whereAttributeIds, "gptc");
        	$attrSQL = $mainSQL = " select products_id from
                        (
                            select pp.products_id,GROUP_CONCAT( CONCAT( `products_options_id`, \"-\", `products_options_values_id`) ) as findin from products_attribute ppa
                            JOIN products_to_categories pptc ON ppa.products_id = pptc.products_id
                            JOIN products pp ON ppa.products_id = pp.products_id
                            where pp.`products_status`=1 AND pptc.`categories_id` in (". implode( ",", $ids ) .") 
                            group by pp.products_id
                        )  gptc
                        
                        where {$where}";
        	
        }else{
            $attrSQL = "
                select products_id from
                (
                    select pp.products_id,GROUP_CONCAT( CONCAT( `products_options_id`, \"-\", `products_options_values_id`) ) as findin from products_attribute ppa
                    JOIN products_to_categories pptc ON ppa.products_id = pptc.products_id
                    JOIN products pp ON ppa.products_id = pp.products_id
                    where pp.`products_status`=1 AND pptc.`categories_id` in (". implode( ",", $ids ) .") 
                    group by pp.products_id
                )  gptc
            ";
            $mainSQL = " select distinct products_id from products_to_categories where `categories_id` in ( " . implode( ",", $ids ) . " ) ";
            
        }
        
        $data['refined'] = $this->attributesSearch( $attrSQL, $languageId );
        
        
        $data['attribute'] = $attribute;
        $data['params'] = $data['attributeResult'] = array();
        $sort = "";
        if( isset( $_GET['sort'] ) && $_GET['sort'] != '' ) {
            $sort = $_GET['sort'];
            $data['params'] = array('sort'=>$sort);
        }
        $data['sort'] = $sort;
        
        $order = " p.`sort` DESC, ps.`sales_total` DESC, ps.`review_rating` DESC, ps.`favorites` DESC, ps.`view` DESC ";
        if( $sort == "up" ) {
        	$order = " p.`sale_price` ASC, p.sort DESC, ps.`sales_total` DESC, ps.`review_rating` DESC, ps.`favorites` DESC, ps.`view` DESC ";
        }elseif( $sort== "down" ){
            $order = " p.`sale_price` DESC, p.sort DESC, ps.`sales_total` DESC, ps.`review_rating` DESC, ps.`favorites` DESC, ps.`view` DESC ";
        }elseif( $sort == "new" ){
            $order = " p.`online_date` DESC, p.sort DESC, ps.`sales_total` DESC, ps.`review_rating` DESC, ps.`favorites` DESC, ps.`view` DESC ";
        }elseif( $sort == "rating" ){
            $order = " ps.`review_rating` DESC, p.sort DESC, ps.`sales_total` DESC, ps.`favorites` DESC, ps.`view` DESC ";
        }elseif( $sort == "best" ){
            $order = " ps.`sales_total` DESC, p.sort DESC,ps.`review_rating` DESC, ps.`favorites` DESC, ps.`view` DESC ";
        }
        
        if( !empty( $attribute) ) {
        	$whereAttributes = "( CONCAT( potv.`products_options_id` , \"-\", potv.`products_options_values_id` ) = \"" . implode( "\" OR CONCAT( potv.`products_options_id` , \"-\", potv.`products_options_values_id` ) = \"", $attribute ) . "\" )";
        	$sql = "
        	SELECT po.`products_options_id`,po.`products_options_name`,pov.`products_options_values_id`,pov.`products_options_values_name`
            FROM `products_options_to_values` potv
            JOIN products_options po ON potv.`products_options_id` = po.`products_options_id`
            JOIN products_options_values pov ON potv.`products_options_values_id` = pov.`products_options_values_id`
            WHERE po.`language_id` = {$languageId}
            AND pov.`language_id` = {$languageId}
            AND {$whereAttributes}
        	";
        	$command = Hqw::getApplication()->getModels( "products_options_to_values" )->getDbCommand( $sql );
        	$data['attributeResult'] = $command->fetchAll();
        }
        
        
        $sql = "
        SELECT p.*, pd.*,ps.sales_total,ps.stock_total,ps.favorites,ps.view,ps.review,ps.review_rating,ps.questions FROM
            ( 
            {$mainSQL}
            ) ptc
            
            JOIN products p ON ptc.`products_id`=p.`products_id`
            JOIN products_description pd ON p.`products_id`=pd.`products_id`
            LEFT JOIN products_status ps ON p.`products_id`=ps.`products_id`
            WHERE pd.`language_id`=1
            
            order by {$order}
        ";
        //echo "##############" . $sql . "##############";
        $data['pd'] = new DbSQLDataProvider( Hqw::getApplication()->getModels( "products_to_categories" ), $sql, array( 'pagination'=>array( 'pagesize'=>36 ) ) );
        
    	$this->render( "list", $data );
    }
    
    public function ActionSearch(){
        $data = array();
    	if( !isset( $_GET['keywords'] ) || $_GET['keywords'] == '' ) {
        	printScreen::tips( array( 'msg'=>LIST_DONT_CATEGORIES_ID ) );
        }
        $data['keywords'] = $_GET['keywords'];
        $keywords = explode( ",", str_replace( "  ", ",", str_replace( " ", ",",  $_GET['keywords'] ) ) );
        $keywords = array_map( "addslashes", $keywords );
        $whereOr = array();
        foreach( $keywords as $v ) {
        	$whereOr[] = " CONCAT( products_code, \",\", products_name, \",\", short_description, \",\", products_description, \",\", promotion_text, \",\", sales_text, \",\", ifnull( attributes, \"\" ) ) LIKE '%{$v}%'";
        }
        $where = " WHERE 1  AND ( ". implode( "OR", $whereOr ) ." ) ";
        
        $sort = "";
        if( isset( $_GET['sort'] ) && $_GET['sort'] != '' ) {
            $sort = $_GET['sort'];
            $data['sort'] = $sort;
            $data['params'] = array('sort'=>$sort);
        }
        
        $order = " p.`sort` DESC, ps.`sales_total` DESC, ps.`review_rating` DESC, ps.`favorites` DESC, ps.`view` DESC ";
        if( $sort == "up" ) {
        	$order = " p.`sale_price` ASC, p.sort DESC, ps.`sales_total` DESC, ps.`review_rating` DESC, ps.`favorites` DESC, ps.`view` DESC ";
        }elseif( $sort== "down" ){
            $order = " p.`sale_price` DESC, p.sort DESC, ps.`sales_total` DESC, ps.`review_rating` DESC, ps.`favorites` DESC, ps.`view` DESC ";
        }elseif( $sort == "new" ){
            $order = " p.`online_date` DESC, p.sort DESC, ps.`sales_total` DESC, ps.`review_rating` DESC, ps.`favorites` DESC, ps.`view` DESC ";
        }elseif( $sort == "rating" ){
            $order = " ps.`review_rating` DESC, p.sort DESC, ps.`sales_total` DESC, ps.`favorites` DESC, ps.`view` DESC ";
        }elseif( $sort == "best" ){
            $order = " ps.`sales_total` DESC, p.sort DESC,ps.`review_rating` DESC, ps.`favorites` DESC, ps.`view` DESC ";
        }
        
        $language = ClsFactory::instance( "ClsLanguage" );
    	$languageId = $language->getLanguage();
        
        $sql =  " SELECT products_id FROM (
            SELECT p.products_id, p.`products_code` , pd.products_name, pd.`short_description` , pd.`products_description` , pd.`promotion_text` , pd.`sales_text` , GROUP_CONCAT( CONCAT( po.`products_options_name` , \"-\", pov.`products_options_values_name` ) ) AS attributes
            FROM `products` p
            JOIN products_description pd ON p.products_id = pd.products_id
            LEFT JOIN products_status ps ON p.`products_id`=ps.`products_id`
            LEFT JOIN products_attribute pa ON p.products_id = pa.products_id
            AND pa.show_description =1
            LEFT JOIN products_options po ON pa.`products_options_id` = po.`products_options_id`
            AND po.`type` = 'readonly'
            LEFT JOIN products_options_values pov ON pa.`products_options_values_id` = pov.`products_options_values_id`
            WHERE pd.`language_id` =" . (int)$languageId . "
            AND p.`products_status` =1
            GROUP BY p.products_id
            ORDER BY {$order}
        ) search_data {$where}
        ";
        
        $data['pd'] = new DbSQLDataProvider( Hqw::getApplication()->getModels( "products" ), $sql, array( 'pagination'=>array( 'pagesize'=>36 ) ) );
        $this->render( "search", $data );
        
    }
    
    private function attributeWhere( $whereAttributeIds, $table ){
    	$where = " 1 ";
    	if( !empty( $whereAttributeIds ) ) {
    		foreach( $whereAttributeIds as $k => $v ) {
    			if( is_array( $v ) && count( $v ) > 1 ) {
    			    $where .= " AND ( ";
    			    foreach( $v as $kk=>$val ) {
    			    	if( $kk > 0 ) {
    			    		$where .= " OR FIND_IN_SET( \"{$k}-{$val}\", {$table}.findin ) ";
    			    	}else{
    			    	    $where .= " FIND_IN_SET( \"{$k}-{$val}\", {$table}.findin ) ";
    			    	}
    			    }
    			    $where .= " ) ";
    			}else{
    			    $where .= " AND FIND_IN_SET( \"{$k}-{$v[0]}\", {$table}.findin ) ";
    			}
    		}
    	}
    	return $where;
    }
    
    private function attributesSearch( $ptcSQL, $languageId ){
        
        $sql = "
        SELECT po.`products_options_id`,po.`products_options_name`, pov.`products_options_values_id`, pov.`products_options_values_name`, count( distinct pa.`products_id` ) as tot
        FROM ( 
            {$ptcSQL}

        ) ptc 
        JOIN products_attribute pa ON ptc.`products_id` = pa.`products_id`
        JOIN products_options po ON pa.`products_options_id` = po.products_options_id
        JOIN products_options_values pov ON pov.`products_options_values_id` = pa.`products_options_values_id`
        
        WHERE po.`type` = 'readonly' 
        AND po.`language_id` = ". $languageId ."
        AND pov.`language_id` = ". $languageId ."
        GROUP BY pa.`products_options_values_id`
        ORDER BY po.`sort` DESC,pov.sort DESC
        ";
        //echo "#############" . $sql . "#############";
        $command = Hqw::getApplication()->getModels( "products_to_categories" )->getDbCommand( $sql );
        return $command->fetchAll();
        /*
        
        */
    }
    
    public function ActionProducts() {
        
        if( !isset($_GET['productsId']) || $_GET['productsId'] == '' ) {
        	printScreen::tips( array( 'msg'=>LIST_DONT_CATEGORIES_ID ) );
        }
        $productsId = $_GET['productsId'];
        
        //brown history
        ClsFactory::instance( "ClsSignin" )->postHistory( $productsId );
        
        $data = array();
        $pts = ClsProductsFactory::instance( $productsId );
        $data['productBase'] = $pts->getBase();
        $data['productStatus'] = $pts->getProductStatus();
        $data['productAttribute'] = $pts->getProductsAttributesFormat();
        $data['productReviews'] = $pts->getProductsReviews();
        $data['productQuestion'] = $pts->getProductsQuestions();
        $data['reviewsLevels'] = $pts->getProductsReviewsLevels();
        $data['images'] = $pts->getProductsImages();
        
        $types = $pts->getReviewsLevel();
    	$data['types'] = $types;
    	$data['pts'] = $pts;
    	
    	if( isset($_GET['type']) && $_GET['type'] !='' && $_GET['type'] != 'all') {
    	    if( in_array( $_GET['type'], $types ) ) {
    	    	$data['type'] = $_GET['type'];
    	    }
    	}else{
    	    $data['type'] = "all";
    	}
    	
        //add products views
        $pts->postViewsCount();
    	$this->render( "products", $data );
    }
    
    public function ActionReviews() {
        //分页
        if( !isset($_GET['productsId']) || $_GET['productsId'] == '' ) {
        	printScreen::tips( array( 'msg'=>LIST_DONT_CATEGORIES_ID ) );
        }
        $productsId = $_GET['productsId'];
        
    	$data = array();
        $pts = ClsProductsFactory::instance( $productsId );
        $data['productBase'] = $pts->getBase();
        $data['productStatus'] = $pts->getProductStatus();
        $data['reviewsLevels'] = $pts->getProductsReviewsLevels();
        
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
    	$pr = $pr->group( "products_reviews_id" );
    	$pr = $pr->order( "odate DESC" );
    	
    	$types = $pts->getReviewsLevel();
    	$data['types'] = $types;
    	$data['pts'] = $pts;
    	
    	$query = array( 'products_id'=>$productsId, 'status'=>1 );
    	if( isset($_GET['type']) && $_GET['type'] !='' && $_GET['type'] != 'all') {
    	    if( in_array( $_GET['type'], $types ) ) {
    	    	$query['level'] = $_GET['type'];
    	    	$data['type'] = $_GET['type'];
    	    }
    	}else{
    	    $data['type'] = "all";
    	}
    	
    	$data['pd'] = new DbDataProvider($pr,array('query'=>$query,'pagination'=>array('pagesize'=>10)));
    	
        
    	$data['params'] = array();
        $this->render( "reviews", $data );
    }
    
    public function ActionWriteReviews(){
        $errorTips = $data = array();
        if( Hqw::getApplication()->getRequest()->getIsPostRequest() && isset( $_GET['type'] ) && strtolower( trim( $_GET['type'] ) ) == "process" ) {
            $t = Hqw::getApplication()->getComponent("Date")->cTime();
            $maxMinute = 300;
            $maxCount = 10;
            if( ( !isset( $_SESSION['WRITE_REVIEW_SUBMIT_TAGS'] ) && !isset( $_SESSION['WRITE_REVIEW_SUBMIT_TAGS_COUNT'] ) ) || ( isset( $_SESSION['WRITE_REVIEW_SUBMIT_TAGS'] ) && ($_SESSION['WRITE_REVIEW_SUBMIT_TAGS'] + $maxMinute) < $t ) ) {
            	$_SESSION['WRITE_REVIEW_SUBMIT_TAGS'] = $t;
            	$_SESSION['WRITE_REVIEW_SUBMIT_TAGS_COUNT'] = 1;
            }
            
            if( ( $_SESSION['WRITE_REVIEW_SUBMIT_TAGS'] + $maxMinute ) >= $t ) {
            	$_SESSION['WRITE_REVIEW_SUBMIT_TAGS_COUNT'] += 1;
            }
            
            if( $_SESSION['WRITE_REVIEW_SUBMIT_TAGS_COUNT'] <= $maxCount ) {
            	if( isset( $_POST['tag'] ) && $_POST['tag'] != "" && $_POST['tag'] != PRODUCTS_REVIEWS_CUSTOM_TAG ) {
            	    $data = array( 'products_reviews_tags_name'=>$_POST['tag'] );
            	    $prt = Hqw::getApplication()->getModels( "products_reviews_tags" );
            	    $prt = $prt->where( $data );
            	    if( ( $tag = $prt->fetch() ) ){
            	        echo $tag['products_reviews_tags_id'];
            	        exit;
            	    }else{
                	    if( Hqw::getApplication()->getModels( "products_reviews_tags" )->insert( $data ) ){
                	        $tagId = Hqw::getApplication()->getModels( "products_reviews_tags" )->lastInsertId();
                	        echo $tagId;
                	        exit;
                	    }
            	    }
            	}
            }
            
            echo 0;
            exit;
        }
        //strip_tags
        //check user session
        $signin = ClsFactory::instance("ClsSignin");
        
        $productsId = 0;
        if( isset( $_GET['productsId'] ) || isset( $_POST['productsId'] ) ) {
        	$productsId = isset( $_GET['productsId'] ) ? $_GET['productsId'] : ( isset( $_POST['productsId'] ) ? $_POST['productsId'] : 0 );
        	$_GET['productsId'] = $productsId;
        	$signin->checkUser();
        	$userId = $signin->getUser()->getUserId();
        	//采购过当前商品， 并且还没有评论
        	$pts = ClsProductsFactory::instance( $productsId );
            $data['pts'] = $pts;
            $data['productBase'] = $pts->getBase();
        }
       
        
        if( Hqw::getApplication()->getRequest()->getIsPostRequest() && isset( $_GET['type'] ) && strtolower( trim( $_GET['type'] ) ) == "submit_reviews" ) {
            if( $productsId != 0 ) {
                //rating not empty
                //mark not empty
                if( !isset( $_POST['rating'] ) ) {
                	$errorTips[] = PRODUCTS_REVIEWS_REQUEST_RATING;
                }
                
                if( !isset( $_POST['mark'] ) || $_POST['mark'] == "" || strlen( $_POST['mark'] ) > 500 ) {
                	$errorTips[] = PRODUCTS_REVIEWS_REQUEST_MARK;
                }
                
                if( empty( $errorTips ) ) {
                	$postData = array();
                	$postData['products_id'] = (int)$productsId;
                	$postData['user_id'] = (int)$userId;
                	$postData['rating'] = (int)$_POST['rating'] ? (int)$_POST['rating'] : 5;
                	$postData['mark'] = $_POST['mark'];
                	
                	$tags = array();
                	if( !empty( $_POST['tags'] ) ) {
                		$tags = array_keys( $_POST['tags'] );
                	}
                	$images = $_POST['uploadImages'];
                	if( $signin->getUser()->postProductsReviews( $postData, $tags, $images ) ){
                	    $reviewsLink = ClsFactory::instance( "ClsSeo" )->getProductsInteractLink( $data['productBase'] );
                	    Hqw::getApplication()->getComponent("Request")->redirect( $reviewsLink['href'] );
                	}
                }
            }
        }
        
        if( !isset($_GET['productsId']) || $_GET['productsId'] == '' || $productsId == 0 ) {
        	printScreen::tips( array( 'msg'=>LIST_DONT_CATEGORIES_ID ) );
        }

        $data['productStatus'] = $pts->getProductStatus();
        $data['errorTips'] = implode( "<br />", $errorTips );
        
        $this->render( "writereview", $data );
    }
    
    public function ActionUploadReviewsFiles(){
        $options = array('script_url'=>Hqw::getApplication()->createUrl('index/upload_reviews_files'),'accept_file_types' => '/\.(gif|jpe?g|png)$/i','max_file_size' => 1024*1024*2 );
        $upload_handler = new UploadHandler($options);
        exit();
    }
    
    public function ActionQuestions() {
    	//分页
        if( !isset($_GET['productsId']) || $_GET['productsId'] == '' ) {
        	printScreen::tips( array( 'msg'=>LIST_DONT_CATEGORIES_ID ) );
        }
        $productsId = $_GET['productsId'];
        
        $data = array();
        $pts = ClsProductsFactory::instance( $productsId );
        $data['productBase'] = $pts->getBase();
        $data['productStatus'] = $pts->getProductStatus();
        $data['pts'] = $pts;
        
        $qu = Hqw::getApplication()->getModels( "products_questions" );
        $keywords = '';
        if( isset( $_POST['keywords'] ) ) {
            $keywords = $_POST['keywords'];
        	$qu->condition( "MATCH( mark, answers_mark ) AGAINST ('" . addslashes( $keywords ) . "' IN BOOLEAN MODE)" );
        }
        $query = array( 'products_id'=>$productsId );
        $qu = $qu->order( "products_questions_id ASC" );
        
        $data['params'] = array('keywords'=>$keywords);
        $data['pd'] = new DbDataProvider($qu,array('query'=>$query,'pagination'=>array('pagesize'=>10)));
        $this->render( "questions", $data );
        
    }
    
    public function ActionChangeCurrency(){
        if( isset( $_POST['code'] ) ) {
        	$currency = ClsFactory::instance( "ClsCurrency" );
        	$currency->setCurrency( $_POST['code'] );
        }
        exit;
    }
    
    public function ActionNewsletter(){
        if( Hqw::getApplication()->getRequest()->getIsPostRequest() && isset( $_POST['email'] ) && trim( $_POST['email'] ) != "" ) {
            $data = array( 'email'=>trim( $_POST['email'] ) );
            Hqw::getApplication()->getModels( "newsletter" )->insert( $data );
            exit();
        }
    }
}
?>