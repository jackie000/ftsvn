<?php
$clsSeo = ClsFactory::instance("ClsSeo");
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
<div id="category_top_image" class="cwidth">
    <img src="/images/category_top_image.jpg" width="980">
</div>

<div id="breadcrumb" class="cwidth">
    <ul>
        <li class="title"><?php echo "<a href=\"" . Hqw::getApplication()->getComponent("Request")->getHostInfo() . "\">" . ucwords( Hqw::getApplication()->getComponent("Request")->getHostInfo(true) ) . "</a>";?></li>
        <li class="space">></li>
        <li class="cur title">Search</li>
    </ul>
    <div class="cl"></div>
</div>

<div id="products_list" class="cwidth">
    <div class="product_detail">
        <div class="search_category  div_left">
            <dl style="margin-bottom:5px;">
                <dt class="option_title">Search Keywords</dt>
            </dl>
            <dl class="scbottom">
                <dt class="search_keywords_menu"><?php echo $keywords;?></dt>
            </dl>
            <?php
            $this->contentWidget( "/layouts/widget/hot-sale" );
            $this->contentWidget( "/layouts/widget/popular-search" );
            ?>
        </div>
        <div class="lists div_left">
            <div class="browse page_nav ">
                <?php
                if( $pd->getTotalItemCount() > 0 ) {
                ?>
                <ul>
                    <li style="float:right;">
                    <?php
                    echo ClsFactory::instance( "ClsPagination" )->getSearchPage( $keywords, $params, $pd->getPagination(), "simpleness" );
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
                                $price = $clsSeo->getSearchSeoLink( $keywords, $sortParams );
                            }else{
                                $sortParams['sort'] = "up";
                                $price = $clsSeo->getSearchSeoLink( $keywords, $sortParams );
                            }
                            
                            if( $sort == "new" ){
                                $new = $clsSeo->getSearchSeoLink( $keywords );
                            }else{
                                $sortParams['sort'] = "new";
                                $new = $clsSeo->getSearchSeoLink( $keywords, $sortParams );
                            }
                            
                            if( $sort == "rating" ) {
                                $rating = $clsSeo->getSearchSeoLink( $keywords );
                            }else{
                                $sortParams['sort'] = "rating";
                                $rating = $clsSeo->getSearchSeoLink( $keywords, $sortParams );
                            }
                            
                            if( $sort == "best" ) {
                                $best = $clsSeo->getSearchSeoLink( $keywords );
                            }else{
                                $sortParams['sort'] = "best";
                                $best = $clsSeo->getSearchSeoLink( $keywords, $sortParams );
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
            	    $savePercent = $pts->getSavePercent();
            	    
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
                        <dd class=\"n_p\"><span class=\"normal_price\">{$currency->getCurrency()} {$currency->getCurrencySign()}{$currency->getCurrencyValues( $pts->getMarketPrice())}</span> {$currency->getCurrency()} <span class=\"list_money\">{$currency->getCurrencySign()}{$currency->getCurrencyValues($pts->getSalesPrice())}</span></dd>\r\n
                        <dd class=\"sw s_star_" . ClsProductsFactory::instance( $v['products_id'] )->getProductsRating() . "\"><span></span>" . $pts->getProductsReviewsCount() . "</dd>\r\n
                    </dl>\r\n
            		";
            		
            		
                }
            }
            ?>
        </div>
    </div>
    <div class="cl"></div>
</div>
<?php
$this->contentWidget( "/layouts/widget/customer-reviews" );
?>