<?php
class ClsSeo extends ClsSettings {
    
    public function getTitle(){
        return CONFIGURATION_SEO;
    }
    
    public function handleMeta( $value ) {
        
    	if( $this->getValue( 'SEO_PREFIX' ) ) {
    		$prefix = $this->getValue( 'SEO_PREFIX' );
    		$prefix = ucwords( strtolower( $prefix ) );
    		
    		$value = str_replace( "{prefix}", $prefix, $value );
    	}else{
    	    $value = str_replace( "{prefix}", "", $value );
    	}
    	
    	if( $this->getValue( 'SEO_LOCATION' ) ) {
    		$local = $this->getValue( 'SEO_LOCATION' );
    		$local = ucwords( strtolower( $local ) );
    		
    		$value = str_replace( "{local}", $local, $value );
    	}else{
	    	$value = str_replace( "{local}", "", $value );
    	}
    	
    	return $value;
    }
    
    /**
     * category url
     *
     * eg:/c.1_4_38-cheap-wedding-dresses/destination-wedding-dresses/castle-wedding-dresses/1-4_2-12.color-green-size-custom size?pagesize=24&page=1
     * 
     * @param array $categories eg:array('categories_id'=>0,'categories_name'=>'aa')
     * @param boolean $array
     * @param array $attributes eg: array(0=>array('products_options_id'=>1,'products_options_values_id'=>1,'products_options_name'=>'color','products_options_values_name'=>'red'))
     * @param array $params eg array('sort'=>'', 'pagesize'=>24)
     * @param string $page eg "p=2"
     * @return string or array
     */
    public function getCategoriesLink( $categories, $array=true, $attributes=array(), $params=array(), $page=null ) {
        if( !empty( $categories ) ) {
            $attrNames = $attrIds = $cateNames = $cateIds = array();
            $linkName = $hrefTitle = "";
            
            $clsCate = ClsFactory::instance( "ClsCategories" );
            $categoriesAll = $clsCate->getCategories();
            
            $clsCommon = ClsFactory::instance( "ClsCommon" );
            $breadcrumbCategories = $clsCommon->getCategoriesFather( $categoriesAll, $categories['categories_id'] );
            $breadcrumbCategories = array_reverse( $breadcrumbCategories );
            
        	foreach( $breadcrumbCategories as $k => $v ) {
        		$cateIds[] = $v['categories_id'];
        		$cateNames[] = strtolower( str_replace( " ", "-", $v['categories_name'] ) );
        		$hrefTitle = strtolower( $v['categories_name'] );
        		$linkName = $v['categories_name'];
        	}
        	
        	if( !empty( $cateIds ) && !empty( $cateNames ) ) {
        	    $url = "/";
        		foreach( $attributes as $k => $v ) {
        			$attrIds[] = $v['products_options_id'] . "-" . $v['products_options_values_id'];        			
        			$attrNames[] = str_replace( " ", "-", strtolower( trim( $v['products_options_name'] ) . "-" . trim( $v['products_options_values_name'] ) ) );
        		}
        		$urlArray = array();
        		$urlArray[] = "c." . implode( "_", $cateIds );
        		
        		if( $this->getValue( 'SEO_PREFIX' ) ) {
            		$urlArray[] = $this->getValue( 'SEO_PREFIX' );
            		$hrefTitle = strtolower( $this->getValue( 'SEO_PREFIX' ) . " " . $hrefTitle );
            	}
            	
        		$urlArray[] = implode( "/", $cateNames );
        		$url .= implode( "-", $urlArray ) . "/";
        		
        		if( !empty( $attrIds ) && !empty( $attrNames ) ) {
        			$url .= implode( "_", $attrIds ) . ".";
        			$url .= implode( "-", $attrNames );
        		}
        		
    		    if( $params && is_array( $params ) && !empty( $params ) ) {
    		    	$url .= "?" . http_build_query( $params );
    		    }
    		    
    		    if( $page !== null ) {
                    if( $params && is_array( $params ) && !empty( $params ) ) {
                        $url .= "&$page";
                    }else {
                        $url .= "?$page";
                    }
                }
                
                if( $array == true ) {
        	    	return array( 'href'=>$url, 'alt'=>$hrefTitle, 'title'=>$hrefTitle, 'name'=>$linkName );
        	    }else{
        	        return "<a href=\"{$url}\" alt=\"{$hrefTitle}\" title=\"{$hrefTitle}\" >{$linkName}</a>";
        	    }
        	}
        }
        return array();
    }
    
    public function getProductsTags( $id, $tag, $array=true, $params=array(), $attributes=array(), $page=null ){
        //tags/1(id).beautiful(name)/16-17_17-19.Silhouette-Sheath?page=1&pagesize=24&categoryId=1
        $title = str_replace( " ", "-", trim( strtolower( $tag ) ) );
        if( $params && is_array($params) ) {
	    	$paramsParams = "?" . http_build_query($params);
	    }
	    if( $array == true ) {
	    	return array( 'href'=>"/tags.{$id}/" . urlencode( $title ), 'alt'=>"tags {$title}", 'title'=>"tags {$title}", 'name'=>$tag );
	    }else{
	        return "<a href=\"/tags.{$id}/" . urlencode( $title ) . "\" alt=\"tags {$title}\" title=\"tags {$title}\" >{$tag}</a>";
	    }
    }
    
    public function getProductsLink( $values, $array=true ) {
        
    	if( !isset($values['products_id']) || $values['products_id'] == "" ) {
    		return false;
    	}
    	
    	$url = "/" . $values['products_id'] . ".";
    	
    	$urlSeo = array();
    	if( $values['products_code'] != "" ) {
    		$urlSeo[] = str_replace( " ", "-", trim( strtolower( $values['products_code'] ) ) );
    	}
    	
    	if( $this->getValue( 'SEO_PREFIX' ) ) {
    		$urlSeo[] = str_replace( " ", "-", trim( strtolower( $this->getValue( 'SEO_PREFIX' ) ) ) );
    	}
    	
    	
    	if( $values['products_name'] != "" ) {
    		$urlSeo[] = str_replace( " ", "-", trim( strtolower( $values['products_name'] ) ) );
    	}
    	
    	if( $this->getValue( 'SEO_PREFIX' ) ) {
    	    $urlSeo[] = str_replace( " ", "-", trim( strtolower( $this->getValue( 'SEO_PREFIX' ) ) ) );
    	}
    	
    	$href = $url . implode( "-", $urlSeo  );
    	
    	$seoTitle = $seoAlt = $this->getValue( 'SEO_PREFIX' ) . " " . strtolower( trim( $values['products_name'] ) ) . " " . $this->getValue( 'SEO_POSTFIX' );
    	if( $array == true ) {
    		return array( 'href'=>$href, 'alt'=>$seoAlt, 'title'=>$seoTitle, 'name'=>$values['sales_text'] . ' ' . $values['products_name'] );
    	}else{
    	    return "<a href=\"" . $href . "\" title=\"" . $seoTitle . "\" alt=\"" . $seoAlt . "\">" . $values['sales_text'] . ' ' .  $values['products_name'] . "</a>";
    	}
    }
    
    /**
     * reviews link or questions link
     *
     * @param array $values products information
     * @return array
     */
    public function getProductsInteractLink( $values,  $type ="reviews",  $params=array(), $page=null ) {
    	$links = $this->getProductsLink( $values );
    	if( ($pos = strpos( $links['href'], "/" )) !== false ) {
    	    if( $pos == 0 ) {
    	    	$pos = 1;
    	    }
    		$links['href'] = substr( $links['href'], 0, $pos ) . "{$type}." . substr( $links['href'], $pos );
    		$paramsParams = $pageParams = $pageNum = '' ;
    		if( $params && is_array($params) && !empty($params) ) {
		    	$paramsParams = "?" . http_build_query($params);
		    }
		    
		    if( $page !== null ) {
		        if( $params && is_array($params) && !empty($params) ) {
		        	$pageParams = "&$page";
		        }else{
		            $pageParams = "?$page";
		        }
		        if( ($pos = strpos( $page, "=" )) !== false ) {
		        	$pageNum = " page " . substr( $page, ($pos+1));
		        }
		    }
		    $links['href'] .= $paramsParams . $pageParams;
    	}else{
    	    $links['href'] = "#";
    	}
    	
    	$links['alt'] = "{$type} " . $links['alt'] . $pageNum;
    	$links['title'] = "{$type} " . $links['title'] . $pageNum;
    	return $links;
    }
    
    public function getProductsSeoLink( $productsId ){
        if( !$productsId ) {
        	return false;
        }
        $pts = ClsProductsFactory::instance( $productsId );
        $base = $pts->getBase();
        //$base['products_name'] = $base['sales_text'] . ' ' . $base['products_name'];
        return $this->getProductsLink( $base, false );
    }
    
    public function getProductsSeoImages( $productsId, $w=0, $h=0 ){
        if( !$productsId || ( $w == 0 && $h == 0 ) ) {
        	return false;
        }
        $pts = ClsProductsFactory::instance( $productsId );
        $base = $pts->getBase();
        //$base['products_name'] = $base['sales_text'] . ' ' . $base['products_name'];
        $link = $this->getProductsLink( $base );
        $clsPm = ClsFactory::instance( "ClsProductsMethod" );
        $width = $w ? "width=\"{$w}\"" : '';
        $height = $h ? "height=\"{$h}\"" : '';
        return "<a href=\"{$link['href']}\"  alt=\"" . $link['title'] . "\" title=\"" . $link['title'] . "\" class=\"t\"><img alt=\"" . $link['title'] . "\" title=\"" . $link['title'] . "\" src=\"" . $clsPm->thumbnailImage( $base['products_images'], $w, $h ) .  "\" {$width} {$height} ></a>";
    }
    
    public function getSearchSeoLink( $keywords, $params=array(), $page=null ){
        //eg:/search/a-line?sort=down&page=2
        $title = "search {$keywords} ";
        $url = "/search?keywords={$keywords}";
        if( $params && is_array( $params ) && !empty( $params ) && $params != null ) {
	    	$url .= "&" . http_build_query( $params );
	    }
	    
	    if( $page !== null ) {
	        $title = str_replace( "=", " ", $page ) . $title;
            if( $params && is_array( $params ) && !empty( $params ) ) {
                $url .= "&$page";
            }else {
                $url .= "&$page";
            }
        }
        $title = strtolower( $title );
        return array( 'href'=>strtolower( $url ), 'alt'=>$title, 'title'=>$title, 'name'=>$title );
    }
    
    public function getMeta(){
        $currency = ClsFactory::instance( "ClsCurrency" );
        
        $title = "Wedding dresses, Formal &amp; Evening Dresses, Formal Dresses UK - Ftmer.com";
    	$keywords = "Wedding dresses, formal dresses, evening dresses, prom dresses, cheap wedding dresses uk, cheap formal dresses uk, cheap evening dresses uk, cheap prom dresses uk, discount evening dresses uk, discount wedding dresses uk, discount formal dresses uk, discount prom dresses uk";
    	$description = "Wedding Dresses, formal dresses, evening dresses, prom dresses with low price, Ftmer.com provides a great selection of quality dresses, Come in and get cheap dresses";
    	
    	$indexAction = Hqw::getApplication()->getController()->getActions()->getAction();
    	$indexAction = ucwords( str_replace( "_", " ", $indexAction ) );
    	
    	$controllerAction = Hqw::getApplication()->getController()->getId();
    	
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
                $title = ucwords( strtolower( $category['meta_title'] ) );
                $keywords = ucwords( strtolower( $category['meta_keywords'] ) );
                $description = ucwords( strtolower( $category['meta_description'] ) );
            }else{
                $postfix = $prefix = "";
                if( $this->getValue( 'SEO_PREFIX' ) ) {
            		$prefix = $this->getValue( 'SEO_PREFIX' );
            		$prefix = ucwords( strtolower( $prefix ) );
            	}
            	
            	if( $this->getValue( 'SEO_CATE_PREFIX' ) ) {
            		$postfix = $this->getValue( 'SEO_CATE_PREFIX' );
            		$postfix = ucwords( strtolower( $postfix ) );
            	}
            	
            	$description = $keywords = $title = $prefix . " " . ucwords( strtolower( $category['categories_name'] ) ) . $postfix;
            }
        	
        }elseif( isset($_GET['productsId']) && $_GET['productsId'] != '' ) {
            
            $pts = ClsProductsFactory::instance( $_GET['productsId'] );
            $productBase = $pts->getBase();
            if( $productBase ) {
                $title = $keywords = $description = $prefix = "";
                $salesPrice = $currency->getCurrencyValues( $pts->getSalesPrice() );
                if( $this->getValue( 'SEO_PREFIX' ) ) {
            		$prefix = $this->getValue( 'SEO_PREFIX' );
            	}
            	
                if( isset( $productBase['meta_title'] ) && $productBase['meta_title'] != '' ) {
                    $title = $productBase['meta_title'];
                    $title = $this->handleMeta( $title );
                }else{
                    if( $this->getValue( 'seoProductsTitle' ) ) {
                		$title = $this->getValue( 'seoProductsTitle' );
                		$title = $this->handleMeta( $title );
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
                    $keywords = $this->handleMeta( $keywords );
                }else{
                    if( $this->getValue( 'seoProductsKeywords' ) ) {
                		$keywords = $this->getValue( 'seoProductsKeywords' );
                		$keywords = $this->handleMeta( $keywords );
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
                    $description = $this->handleMeta( $description );
                }else{
                    if( $this->getValue( 'seoProductsDescription' ) ) {
                		$description = $this->getValue( 'seoProductsDescription' );
                		$description = $this->handleMeta( $description );
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
            }
        }elseif( strtolower( $indexAction ) == "index" && ( strtolower( $controllerAction ) == "index" || $controllerAction == "" ) ){
            
        }else{
            $title = "{$indexAction} : {$title}";
            $keywords .= " " . $indexAction;
            $description = "{$title} {$description}";
        }
        
        echo "<title>{$title}</title>\r\n";
        echo "<meta name=\"description\" content=\"{$description}\" />\r\n";
        echo "<meta name=\"keywords\" content=\"{$keywords}\" />\r\n";
    }
}

?>