<div class="website_navigation div_left menu_category">
    <div class="category_title cate_title">
        <dl>
            <dt>
                <span>
                    <span><?php echo INDEX_MENU_CATEGORY;?></span>
                </span>
            </dt>
        </dl>
    </div>
    <?php
        $clsCate = ClsFactory::instance( "ClsCategories" );
        $categories = $clsCate->getCategories();
    ?>
    <div class="category cate">
        <dl>
            <dd  itemscope itemtype="http://schema.org/WebPage">
                <?php
                $clsSeo = ClsFactory::instance("ClsSeo");
                function createNavMenu( $fid, $categories, $clsSeo ){
                    $findCount = 0;
                    foreach( $categories as $k => $v ) {
                    	if( $v['category_parent_id'] == $fid ) {
                    		echo "
                                <dl>
                                    <dt>" . $clsSeo->getCategoriesLink( array("categories_id"=>$v['categories_id']), false ) . "</dt>";
                    		foreach( $categories as $kk => $vv ) {
                    			if( $v['categories_id'] == $vv['category_parent_id'] ) {
                    				echo "<dd>" . $clsSeo->getCategoriesLink( array("categories_id"=>$vv['categories_id']), false ) . "</dd>";
                    			}
                    		}
                    		echo "</dl>";
                    		$findCount++;
                    		if( $findCount % 2 == 0 ) {
                    			echo "<div class=\"cl\"></div>";
                    		}
                    	}
                    }
                    
                }
                if( $categories ) {
                    $fidCount = 0;
                	foreach( $categories as $k => $v ) {
                	    if( $v['category_parent_id'] == 0 ) {
                	    	if( $fidCount == 0 ) {
                    			echo "<div class=\"category_name cate_name\" style=\"border-top:none;\">";
                    		}else{
                    		    echo "<div class=\"category_name cate_name\">";
                    		}
                    		$top = $fidCount * -33 - 4;
                    		echo "<div class=\"category_child cate_sub\" style=\"top:{$top}px;\">";
                    		echo "<div class=\"cswidth\">";
                    		createNavMenu( $v['categories_id'], $categories, $clsSeo );
                    		echo "</div>";
                    		echo "<div class=\"category_brand csbrand\" style=\"min-height:150px;\">
                            <dl>
                                <dt>Special Offers</dt>
                                <dd><table class=\"simply_nav\" style=\"text-align:left;float:left;\">
    <tr>
        <td><p class=\"title\">FREE SHIPPING</p> <p>for order over  GBP£300</p></td>
    </tr><tr>
        <td><p class=\"title\">Sign Up For 5% Off </p><p><a href=\"#\">coupon code: new5%off</a></p></td>
    </tr>    
    </table></dd>
                            </dl>
                            </div>";
                    		echo "</div>";
                    		if( $fidCount == 0 ) {
                    			echo "	<h2 style=\"height:33px;top:-1px;\" itemprop=\"breadcrumb\"><a href=\"javascript:void(0);\"><em>&gt;</em>" . $clsSeo->getCategoriesLink( array("categories_id"=>$v['categories_id']), false ) . "<span> <!--(13392)--></span></a></h2>";
                    		}else{
                    		    echo "	<h2 itemprop=\"breadcrumb\"><a href=\"javascript:void(0);\"><em>&gt;</em>" . $clsSeo->getCategoriesLink( array("categories_id"=>$v['categories_id']), false ) . "<span> <!--(13392)--></span></a></h2>";
                    		}
                            
                            echo "</div>";
                            $fidCount++;
                	    }
                	}
                }
                
                ?>
            </dd>
        </dl>
    </div>
</div>