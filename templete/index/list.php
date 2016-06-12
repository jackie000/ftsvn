<?php
$clsSeo = ClsFactory::instance("ClsSeo");
$clsPm = ClsFactory::instance( "ClsProductsMethod" );


$productsList = $pd->getData();
if( $pd->getPagination()->getCurrentPage() <= 1 ){
    $start = 1;
    if( $pd->getPagination()->getPageSize() < $pd->getTotalItemCount() ) {
    	$end = $pd->getPagination()->getPageSize();
    }else{
        $end = $pd->getTotalItemCount();
    }
}else{
    
    $start = ( $pd->getPagination()->getCurrentPage() - 1 ) * $pd->getPagination()->getPageSize();
    if( ( $start + $pd->getPagination()->getPageSize() ) > $pd->getTotalItemCount() ) {
    	$end = $pd->getTotalItemCount();
    }else{
        $end = $start + $pd->getPagination()->getPageSize();
    }
}
?>
<script language="javascript">
$(document).ready(function() {

    $("#products_list .dropdown dt a").click(function() {
        $("#products_list .dropdown dd ul").hide();
        $("#products_list .dropdown dd ul").toggle();
    });

    $("#products_list .dropdown dd ul li a").click(function() {
        var text = $(this).html();
        $("#products_list .dropdown dt a span").html(text);
        $("#products_list .dropdown dd ul").hide();
    });
   
    $("#products_list .search_category .options .options_title").toggle(function(){
        $(this).css("background-image","url('/images/arrow-right.png')");
    },function(){
        $(this).css("background-image","url('/images/arrow.png')");
    });

    $("#products_list .search_category .options .options_title").click(function() {
        $(this).parent().find("dd").toggle();
        $(this).parent().find(".hide").hide();
    });

    $("#products_list .search_category .options .pt10 a").toggle(function(){
        $(this).html("[-]<?php echo LIST_SHOPPING_OPTIONS_LESS;?>");
    },function(){
        $(this).html("[+]<?php echo LIST_SHOPPING_OPTIONS_MORE;?>");
    });

    $("#products_list .search_category .options .pt10 a").click(function() {
        $(this).parent().parent().find(".hide").toggle();
        //alert($(this).parent().attr("class"));
    });

    $(document).bind('click', function(e) {
        var $clicked = $(e.target);
        if (! $clicked.parents().hasClass("dropdown")){
            $(".dropdown dd ul").hide();
        }
    });

    $(".account_menu .search_category dd:eq(5)").addClass("cu_item");

    /*
    $("#flagSwitcher").click(function() {
        $(".dropdown img.flag").toggleClass("flagvisibility");
    });
    */
    /*menu list tree*/
    $("img[class='closed']").each(function(){
        $(this).nextAll(".products_list_category").hide();
    });
    $(".products_list_category img").click(function(){
        var status = $(this).attr("class");
        if( status == "opened" ){
            $(this).nextAll(".products_list_category").hide();
            $(this).attr("class", "closed");
        }
        
        if( status == "closed" ){
            $(this).nextAll(".products_list_category").show();
            $(this).attr("class", "opened");
        }
    });
    
    
    /*list*/
});
</script>
<script language="javascript" src="/js/list.js"></script>
<div id="category_top_image" class="cwidth">
    <img src="/images/category_top_image.jpg" width="980">
</div>

<?php
$this->contentWidget( "/layouts/breadcrumb/products", array('categoryId'=>$categoryId, 'attributeResult'=>$attributeResult) );
?>
<?php
$clsCommon = ClsFactory::instance( "ClsCommon" );
$categoriesIds = $clsCommon->getCategoriesFather( $categories, $categoryId );

$topCategory = 0;
if( !empty( $categoriesIds ) ) {
    $openedCategory = array();
	foreach( $categoriesIds as $k => $v ) {
		$openedCategory[] = $v['categories_id'];
		$topCategory = $v;
	}
}
?>
<div id="products_list" class="cwidth">
    <div class="product_detail">
        <div class="search_category  div_left">
            <dl style="margin-bottom:5px;">
                <dt class="option_title"><?php echo LIST_SHOPPING_BY_CATEGORY;?></dt>
            </dl>
            
            <?php
            function categoryMenu( $categoryId, $clsSeo, $topCategory, $categories, $openedCategory, $style, $num=1 ){
                if( $num > 1 ){
                    $num = 2;
                }
                if( $categoryId == $topCategory['categories_id'] ) {
                    echo "<dl class=\"products_list_category\">\r\n<dd class=\"set_current_category category_level_{$num}\" flag=\"{$style}\">";
                }else{
                    echo "<dl class=\"products_list_category\">\r\n<dd class=\"normal category_level_{$num}\" flag=\"{$style}\">";
                }
                
                $flag = "";
                if( in_array( $topCategory['categories_id'], $openedCategory ) && $style == "open" ) {
                	$flag =  "<img class=\"opened\" src=\"/images/s.gif\">";
                }else{
                    $flag = "<img class=\"closed\" src=\"/images/s.gif\">";
                }
                
                $parentFlag = false;
                foreach( $categories as $k => $v ) {
                	if( $v['category_parent_id'] == $topCategory['categories_id'] ) {
                		$parentFlag = true;
                		break;
                	}
                }
                
                if( $parentFlag == false ) {
                	$flag = "<img class=\"child\" src=\"/images/s.gif\">";
                }
                echo $flag;
                echo $clsSeo->getCategoriesLink( $topCategory, false );
                $num++;
                foreach( $categories as $k => $v ) {
                	if( $v['category_parent_id'] == $topCategory['categories_id'] ) {
                	    if( in_array( $v['categories_id'], $openedCategory ) ) {
                	    	categoryMenu( $categoryId, $clsSeo, $v, $categories, $openedCategory, "open", $num );
                	    }else{
                	        categoryMenu( $categoryId, $clsSeo, $v, $categories, $openedCategory, "close", $num );
                	    }
                		
                	}
                }
                echo "</dd></dl>\r\n";
            }
            
            if( is_array( $topCategory ) && !empty( $topCategory ) ) {
            	categoryMenu( $categoryId, $clsSeo, $topCategory, $categories, $openedCategory, "open" );
            }
            ?>
            
            <?php
            $attrList = array();
            if( $refined ){
                foreach ($refined as $k => $v) {
                    if( array_key_exists( $v['products_options_id'], $attrList ) ) {
                    	$attrList[$v['products_options_id']]['values'][] = array('id'=>$v['products_options_values_id'],'name'=>$v['products_options_values_name'],'tot'=>$v['tot'] );
                    }else{
                        $attrList[$v['products_options_id']] = array( "products_options_id"=>$v['products_options_id'],"products_options_name"=>$v['products_options_name'], "values"=>array() );
                        $attrList[$v['products_options_id']]['values'][] = array('id'=>$v['products_options_values_id'],'name'=>$v['products_options_values_name'],'tot'=>$v['tot'] );
                    }
                }
            }
            
            if( !empty( $attrList ) ) {
                echo "<dl class=\"scbottom\">\r\n
                <dt class=\"option_title\">".LIST_SHOPPING_OPTIONS."</dt>\r\n
            </dl>\r\n";
            	foreach ($attrList as $k => $v) {
            	    if( strtolower($v['products_options_name'])== "designer" ) {
            	        continue;
            	    }
            		echo "<dl class=\"options\">\r\n";
                    echo "    <dt class=\"options_title\">" . strtoupper( $v['products_options_name'] ) . "</dt>\r\n";
                    
                    $productsOptionsName = array();
                    foreach( $v['values'] as $subKey=>$subVal ){
                        $productsOptionsName[$subKey] = $subVal['name'];
                    }
                    
                    $subOptions = $v['values'];
                    array_multisort( $productsOptionsName, SORT_ASC, $subOptions );
                    
                    foreach ( $subOptions as $kk=>$val ) {
                        $checked = "";
                        $nowAttribute = $checkedResult = $attributeResult;
                        if( !empty( $attributeResult ) ) {
                        	foreach( $attributeResult as $ak => $av ) {
                        		if( $av['products_options_id'] == $v['products_options_id'] && $av['products_options_values_id'] == $val['id'] ) {
                        			$checked = " checked=\"checked\" ";
                        			unset($checkedResult[$ak]);
                        			break;
                        		}
                        	}
                        }
                        if( $checked != "" ) {
                        	$linkSeo = $clsSeo->getCategoriesLink( array("categories_id"=>$categoryId), true, $checkedResult, $params );
                        }else{
                            $nowAttribute[] = array( 'products_options_id'=>$v['products_options_id'], 'products_options_name'=>$v['products_options_name'], 'products_options_values_id'=>$val['id'], 'products_options_values_name'=>$val['name'] );
                            $linkSeo = $clsSeo->getCategoriesLink( array("categories_id"=>$categoryId), true, $nowAttribute, $params );
                        }
                        
                        if( ( count( $v['values'] ) - 1 ) == $kk && count( $v['values'] ) < 7 ) {
                        	echo "<dd class=\"pt10\">";
                        }elseif( count( $v['values'] ) >= 7 && $kk > 5 && $checked == "" ){
                            echo "<dd class=\"hide\">";
                        }else{
                            echo "<dd>";
                        }
                        
                    	echo "<label><input {$checked} type=\"checkbox\" href=\"" . $linkSeo['href'] . "\" value=\"{$val['id']}\" name=\"options[{$v['products_options_id']}]\">" . $val['name'] . " <font style=\"color:#999999\">(". $val['tot'] .")</font></label></dd>\r\n";
                    }
                    if( count( $v['values'] ) >= 7 ) {
                    	echo "<dd class=\"pt10\"><a href=\"javascript:void(0)\" >[+]" . LIST_SHOPPING_OPTIONS_MORE . "</a></dd>\r\n";
                    }
                    echo "</dl>\r\n";
            	}
            }
            ?>
            <?php
            $this->contentWidget( "/layouts/widget/hot-sale" );
            $this->contentWidget( "/layouts/widget/popular-search" );
            ?>
        </div>
        
        <div class="lists div_left">
            <?php
            if( $topCategory['images_path'] != '' && $topCategory['short_description'] != '' ){
            ?>
            <div class="category_description" style="background-image:'<?php echo $topCategory['images_path'];?>'">
                <h1><?php echo $topCategory['categories_name'];?></h1>
                <div class="category_description_info">
                    <div class="txt">
                    <p><?php echo $topCategory['categories_name'];?></p>
                    <h2><?php echo $topCategory['short_description'];?></h2>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
            <div class="browse page_nav ">
            <?php
            if( $pd->getTotalItemCount() > 0 ) {
            ?>
                <ul>
                    <li style="float:right;">
                    <?php
                    echo ClsFactory::instance( "ClsPagination" )->getListPage( array( 'categories_id'=>$categoryId ), $attributeResult, $params, $pd->getPagination(), "simpleness" );
                    ?>
                    </li>
                    <li style="float:right;"> <?php echo str_replace( "<s>", $start, str_replace( "<e>", $end, str_replace( "<t>", "<b>".$pd->getTotalItemCount()."</b>", LIST_PAGE_TITLE ) ) );?> </li>
                    <li class="sortby" style="float:left;">
                        <div>
                            <span><?php echo LIST_SORT_BY;?></span>
                            <?php
                            $sortParams = $params;
                            if( array_key_exists( "sort", $sortParams ) ) {
                                unset( $sortParams['sort'] );
                            }
                            if( $sort == "up" ) {
                                $sortParams['sort'] = "down";
                                $price = $clsSeo->getCategoriesLink( array("categories_id"=>$categoryId), true, $attributeResult, $sortParams );
                            }else{
                                $sortParams['sort'] = "up";
                                $price = $clsSeo->getCategoriesLink( array("categories_id"=>$categoryId), true, $attributeResult, $sortParams );
                            }
                            
                            if( $sort == "new" ){
                                $new = $clsSeo->getCategoriesLink( array("categories_id"=>$categoryId), true, $attributeResult );
                            }else{
                                $sortParams['sort'] = "new";
                                $new = $clsSeo->getCategoriesLink( array("categories_id"=>$categoryId), true, $attributeResult, $sortParams );
                            }
                            
                            if( $sort == "rating" ) {
                                $rating = $clsSeo->getCategoriesLink( array("categories_id"=>$categoryId), true, $attributeResult );
                            }else{
                                $sortParams['sort'] = "rating";
                                $rating = $clsSeo->getCategoriesLink( array("categories_id"=>$categoryId), true, $attributeResult, $sortParams );
                            }
                            
                            if( $sort == "best" ) {
                                $best = $clsSeo->getCategoriesLink( array("categories_id"=>$categoryId), true, $attributeResult );
                            }else{
                                $sortParams['sort'] = "best";
                                $best = $clsSeo->getCategoriesLink( array("categories_id"=>$categoryId), true, $attributeResult, $sortParams );
                            }
                            ?>
                            <span <?php echo $sort == "up" || $sort == "down" ? "class=\"curr\"" : "";?>><a href="<?php echo $price['href'];?>"  alt="<?php echo $price['alt'];?> sort by price <?php echo $sort == "up" ? "down" : "up";?>" title="<?php echo $price['title'];?> sort by price <?php echo $sort == "up" ? "down" : "up";?>" ><?php echo LIST_SORT_BY_PRICE;?></a><?php echo $sort == "up" || $sort == "down" ? "<b class=\"" . $sort . "\">&nbsp;</b>" : "";?></span>
                            <span class="sp">|</span>
                            <span <?php echo $sort == "new" ? "class=\"curr\"" : "";?>><a href="<?php echo $new['href'];?>"  alt="<?php echo $new['alt'];?> sort by price new arrivals" title="<?php echo $new['title'];?> sort by price new arrivals" ><?php echo LIST_SORT_BY_NEW_ARRIVALS;?></a></span>
                            <span class="sp">|</span>
                            <span <?php echo $sort == "best" ? "class=\"curr\"" : "";?>><a href="<?php echo $best['href'];?>"  alt="<?php echo $best['alt'];?> sort by price best selling" title="<?php echo $best['title'];?> sort by price best selling" ><?php echo LIST_SORT_BY_BEST_SELLING;?></a></span>
                            <span class="sp">|</span>
                            <span <?php echo $sort == "rating" ? "class=\"curr\"" : "";?>><a href="<?php echo $rating['href'];?>"  alt="<?php echo $rating['alt'];?> sort by price customer rating" title="<?php echo $rating['title'];?> sort by price customer rating" ><?php echo LIST_SORT_BY_CUSTOMER_RATING;?></a></span>
                        </div>
                    </li>
                </ul>
                <div class="cl"></div>
            <?php
            }else{
                echo "<div class=\"list_not_found\">" . LIST_DONT_FOUND . "</div>";
                echo " best saler ";
            }
            ?>
            </div>
            <div class="cl"></div>
            <?php
            $currency = ClsFactory::instance( "ClsCurrency" );
            if( !empty( $productsList ) ) {
            	foreach( $productsList as $k => $v ) {
            	    $pts = ClsProductsFactory::instance( $v['products_id'] );
            	    /*if( !isset( $v['products_images'] ) ) {
            	    	$v = array_merge( $v, $pts->getBase() );
            	    }
            	    
            	    if( !isset( $v['products_name'] ) ) {
            	    	$v = array_merge( $v, $pts->getBase() );
            	    }
            	    
            	    if( !isset( $v['view'] ) ) {
            	    	$v = array_merge( $v, $pts->getProductStatus() );
            	    }
            	    */
            	    $pts->setBase( $v );
            	    $pts->setProductsStatus( $v );
            	    
                    $savePercent = $pts->getSavePercent();
            	    $link = $clsSeo->getProductsLink($v);
            	    
            	    if( $k !== 0 && $k % 3 == 0 ) {
            			echo "<br style=\"clear:left;\">\r\n";
            		}
            		if( $savePercent > 65 ){
            		    $savePercent = "<span class=\"upoff\">{$savePercent}</span><br>Off";
            		}else{
            		    $savePercent = "";
            		}
            		echo "
            		<dl>\r\n
                        <dt>{$clsSeo->getProductsSeoImages( $v['products_id'], 260 )}<em class=\"free_shipping\">{$savePercent}</em></dt>\r\n
                        <dd class=\"list_product_title\">{$clsSeo->getProductsSeoLink( $v['products_id'] )}</dd>\r\n
                        <dd class=\"n_p\"><span class=\"normal_price\">{$currency->getCurrency()} {$currency->getCurrencySign()}{$currency->getCurrencyValues( $pts->getMarketPrice() )}</span> {$currency->getCurrency()} <span class=\"list_money\">{$currency->getCurrencySign()}{$currency->getCurrencyValues( $pts->getSalesPrice() )}</span></dd>\r\n
                        <dd class=\"sw s_star_" . ClsProductsFactory::instance( $v['products_id'] )->getProductsRating() . "\"><span></span>" . $pts->getProductsReviewsCount() . "</dd>\r\n
                    </dl>\r\n
            		";
            	}
            }
            ?>
            
            <br style="clear:left;">
            <div class="pagination">
            <?php
            echo ClsFactory::instance( "ClsPagination" )->getListPage( array( 'categories_id'=>$categoryId ), $attributeResult, $params, $pd->getPagination() );
            ?>
            </div>
        </div>
        
    </div>
    <div class="cl"></div>
</div>
<?php
//$this->contentWidget( "/layouts/widget/customer-reviews" );
?>