<div id="breadcrumb" class="cwidth" itemscope itemtype="http://schema.org/WebPage">
    <ul itemprop="breadcrumb">
        <li class="title"><?php echo "<a href=\"" . Hqw::getApplication()->getComponent("Request")->getHostInfo() . "\">" . ucwords( Hqw::getApplication()->getComponent("Request")->getHostInfo(true) ) . "</a>";?></li>
        <li class="space">></li>
        <?php
        $clsSeo = ClsFactory::instance("ClsSeo");
        $clsCate = ClsFactory::instance( "ClsCategories" );
        $clsCommon = ClsFactory::instance( "ClsCommon" );
        if( isset( $productBase ) ) {
        	if( isset( $productBase['categories_id'] ) && $productBase['categories_id'] != 0 ) {
                $categories = $clsCate->getCategories();
                $breadcrumbCategories = $clsCommon->getCategoriesFather( $categories, $productBase['categories_id'] );
                $breadcrumbCategories = array_reverse( $breadcrumbCategories );
                if( !empty( $breadcrumbCategories ) ) {
                	foreach( $breadcrumbCategories as $k => $v ) {
                		echo "<li class=\"title\">" . $clsSeo->getCategoriesLink( $v, false ) . "</li><li class=\"space\">></li>";
                	}
                }
            }
            if( isset( $type ) ) {
            	$productsLinks = ClsFactory::instance( "ClsSeo" )->getProductsLink( $productBase,false );
            	echo "<li class=\"title\">" . $productsLinks . "</li><li class=\"space\">></li>";
            	echo "<li class=\"cur title\">" . ucfirst( strtolower( $type ) ) . "</li>";
            }else{
                echo "<li class=\"cur title\">" . $productBase['products_name'] . "</li>";
            }
        }
        
        if( isset( $categoryId ) && $categoryId != 0 ) {
            $categories = $clsCate->getCategories();
            $breadcrumbCategories = $clsCommon->getCategoriesFather( $categories, $categoryId );
            $breadcrumbCategories = array_reverse( $breadcrumbCategories );
            
            $allCategories = array();
            $currentCategories = array();
            if( !empty( $breadcrumbCategories ) ) {
                $count = count( $breadcrumbCategories );
            	foreach( $breadcrumbCategories as $k => $v ) {
            	    if( ($count-1) != $k ) {
            	    	$allCategories[] = "<li class=\"title\">" . $clsSeo->getCategoriesLink( $v, false ) . "</li>";
            	    }else{
            	        $allCategories[] = "<li class=\"cur title\">" . $v['categories_name'] . "</li>";
            	    }
            	    
            	    if( $v['categories_id'] == $categoryId ) {
            	    	$currentCategories = $v;
            	    }
            	}
            }
            if( !empty( $allCategories ) ) {
            	echo implode( "<li class=\"space\">></li>", $allCategories );
            }
            
            if( isset( $attributeResult ) && !empty( $attributeResult ) ) {
                echo "<li class=\"selected_attribute\">";
            	foreach( $attributeResult as $k => $v ) {
            	    
            	    $exceptAttribute = array();
            	    foreach( $attributeResult as $kk => $vv ) {
            	    	if( $v['products_options_values_id'] != $vv['products_options_values_id'] ) {
            	    		$exceptAttribute[] = $vv;
            	    	}
            	    }
            	    
            	    $currentLinks = $clsSeo->getCategoriesLink( $currentCategories, true, $exceptAttribute );
            		echo " <span>{$v['products_options_values_name']} <a href=\"{$currentLinks['href']}\"><label>X</label></a></span> ";
            	}
            	echo "</li>";
            }
        }
        
        ?>
        
    </ul>
    <div class="cl"></div>
</div>