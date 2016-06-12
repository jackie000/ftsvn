<?php
$this->contentWidget( "/layouts/breadcrumb/products", array('productBase'=>$productBase, 'type'=>"reviews") );
?>
<?php
$productReviews = $pd->getData();
$currency = ClsFactory::instance( "ClsCurrency" );
$salesPrice = $currency->getCurrencyValues( $pts->getSalesPrice() );
$marketPrice = $currency->getCurrencyValues( $pts->getMarketPrice() );
$savePrice = $currency->getCurrencyValues( $pts->getSavePrice() );
$savePercent = $pts->getSavePercent();
$productsLink = ClsFactory::instance( "ClsSeo" )->getProductsLink( $productBase );
$clsPm = ClsFactory::instance( "ClsProductsMethod" );
$reviewsLink = ClsFactory::instance( "ClsSeo" )->getProductsInteractLink( $productBase );
?>
<div class="content_area">
    <div class="rating div_left" style="padding-bottom:20px;border:1px solid #ddd;width:240px;">
        <div class="also_bought" style="padding-left:30px;">
            <a href="<?php echo $productsLink['href'];?>" alt="<?php echo $productsLink['alt'];?>" title="<?php echo $productsLink['title'];?>"><img alt="<?php echo $productsLink['alt'];?>" title="<?php echo $productsLink['title'];?>" src="<?php echo $clsPm->thumbnailImage( $productBase['products_images'], 150 );?>" width="150"></a>
        </div>
        <div class="also_bought_dec">
            <a href="<?php echo $productsLink['href'];?>" alt="<?php echo $productsLink['alt'];?>" title="<?php echo $productsLink['title'];?>"><?php echo $productsLink['name'];?></a>
        </div>
        <div class="also_bought_dec n_p price">
            <?php echo $currency->getCurrency();?> <?php echo $currency->getCurrencySign();?><?php echo $salesPrice;?>
        </div>
        
        <dl>
        <dt><?php echo PRODUCTS_SHOW_AVERAGE_RATING;?></dt>
        <dd class="sw bsw s_bstar_<?php echo ClsProductsFactory::instance( $productBase['products_id'] )->getProductsRating();?>"><span></span>  
        <b><?php echo ClsFactory::instance("ClsProductsMethod")->getReviewsRatingFormat( $productStatus['review_rating'] );?> </b>
        (<a title="<?php echo $reviewsLink['title'];?>" alt="<?php echo $reviewsLink['alt'];?>" href="<?php echo $reviewsLink['href'];?>" ><?php echo $productStatus['review'];?> <?php echo PRODUCTS_SHOW_CUSTOMER_REVIEWS;?></a>) </dd>
        <dd class="write_review"><a class="subt" href="<?php echo Hqw::getApplication()->createUrl('index/write_reviews',array('productsId'=>$productBase['products_id']));?>"><em><?php echo PRODUCTS_SHOW_WRITE_REVIEW;?></em></a></dd>
        </dl>
    </div>
    <?php
    $productsTags = ClsProductsFactory::instance( $productBase['products_id'] )->getProductsTags();
    ?>
    <div class="div_left" style="width:810px;margin-left:6px;border-top:none;">
        
        <div class="review_list_title" style="border:1px solid #ddd;border-top:2px solid #AD3231;">
        <?php
        if( $productsTags ) {
            echo "<div style=\"padding:20px 20px 0px;\">
            <dt><h2>" . PRODUCTS_SHOW_OWNER_IMPRESSION . "</h2></dt><dd class=\"tags\"><ul>";
        	foreach( $productsTags as $k => $v ) {
        	    $t = ClsFactory::instance( "ClsSeo" )->getProductsTags( $v['products_reviews_tags_id'], $v['products_reviews_tags_name'] );
        		echo "<li><span><a href=\"" .$t['href']. "\" alt=\"". $t['alt'] ."\" title=\"". $t['title'] ."\" >" . $t['name']  . " (" . $v['tot'] . ")</a> </span></li>";
        	}
        	echo "</ul></dd></div>";
        }
        ?>
        </div>
        <?php
        if( $productReviews ) {
        ?>
            <div class="reviews_list">
                <div class="reviews_list_sort">
                <?php
                $levelCount = array( 'all'=>0, 'high'=>0, 'middle'=>0, 'base'=>0 );
                if( $reviewsLevels ){
                    foreach( $reviewsLevels as $k => $v ) {
                    	if( array_key_exists( $v['level'], $levelCount ) ) {
                    		$levelCount[$v['level']] = (int)$v['tot'];
                    		$levelCount['all'] = $levelCount['all'] + (int)$v['tot'];
                    	}
                    }
                }
                foreach( $types as $v ) {
                    $reviewsTypeLink = ClsFactory::instance( "ClsSeo" )->getProductsInteractLink( $productBase, "reviews", array('type'=>$v) );
            		if( $v == $type ) {
            			echo "<span class=\"curr\"> ". ucfirst($v) . " (" . $levelCount[$v] . ") " . " </span>";
            		}else{
            		    echo "<span> <a title=\"".$reviewsTypeLink['title'] ."\" href=\"". $reviewsTypeLink['href'] ."\">". ucfirst($v) . " (" . $levelCount[$v] . ") " . "</a> </span>";
            		}
            	}
                ?>
                
                </div>
            <?php
            foreach( $productReviews as $k => $v ) {
                $userBase = array();
                if( isset( $v['user_id'] ) && $v['user_id'] != "" && $v['user_id'] != "0" ){
                    $user = ClsUserFactory::instance( $v['user_id'] );
                    $userBase = $user->getBase();
                    $userBase['reviews_by_user'] = $user->getName();
                }else{
                    $userBase['reviews_by_user'] = $v['user_name'];
                }
            ?>
            <div class="review_record">
                <div class="user div_left">
                    <div class="sw s_star_<?php echo $v['rating'];?>"><span></span></div>
                    <div><?php echo PRODUCTS_SHOW_REVIEW_BY;?> <?php echo $userBase['reviews_by_user'];?> </div>
                    <div><?php echo PRODUCTS_SHOW_REVIEW_FROM;?> <?php echo $userBase['country'] ? $userBase['country'] : "unknow";?></div>
                    <div><?php echo date("M /d / Y H:i:s", strtotime($v['odate']))?></div>
                </div>
        
                <div class="user_comments div_left">
                        <?php
                        if( $v['tags'] != '' ) {
                            echo "<div class=\"tags\">
                        " . PRODUCTS_SHOW_TAGS;
                        	$tags = explode( ",", $v['tags'] );
                        	foreach( $tags as $tag ) {
                        		if( ( $pos = strpos( $tag, "-" ) ) !== false ) {
                        			echo "<span>" . ClsFactory::instance( "ClsSeo" )->getProductsTags( substr( $tag, 0, $pos ), substr( $tag, ( $pos+1 )), false ) . "</span>";
                        		}
                        	}
                        	echo "</div>";
                        }
                        ?>
                    
                    <div class="content">
                        <?php echo $v['mark'];?>
                    </div>
                    <?php
                    if( $v['images'] != '' ) {
                    	echo "<div class=\"content\" style=\"margin-top:8px;\">";
                    	$images = explode( ",", $v['images'] );
                    	$images = array_unique( $images );
                    	foreach( $images as $im ) {
                    	    if( ( $pos = strpos( $im, '/files' ) ) !== false ) {
                    	    	$im = "/files/thumbnail" . substr( $im, ( $pos + 6 ) );
                    	    }
                    		echo "<img src=\"{$im}\" width=\"64\" style=\"margin-right:5px;\">";
                    	}
                    	echo "</div>";
                    }
                    ?>
                    <div class="useful" style="text-align:right;padding-top:10px;">
                        <?php echo PRODUCTS_SHOW_WAS_HELPFUL;?> <span class="btn"><input type="button" name="helpful_button" products_reviews_id="<?php echo $v['products_reviews_id'];?>" helpful='Y' value="<?php echo PRODUCTS_SHOW_HELPFUL_YES;?>" url="<?php echo Hqw::getApplication()->createUrl('UserActivities/PostHelpful');?>"></span> (<span helpful="<?php echo $v['products_reviews_id'];?>_Y"><?php echo $v['helpful'];?></span>) 
                        <span class="btn"><input type="button"  name="helpful_button" products_reviews_id="<?php echo $v['products_reviews_id'];?>" helpful='N' value="<?php echo PRODUCTS_SHOW_HELPFUL_NO;?>" url="<?php echo Hqw::getApplication()->createUrl('UserActivities/PostHelpful');?>"></span> (<span helpful="<?php echo $v['products_reviews_id'];?>_N"><?php echo $v['helpless'];?></span>)
                    </div>
                </div>
                <div class="cl"></div>
            </div>
            <?php
            }
            ?>
            </div>
            <?php
            echo ClsFactory::instance( "ClsPagination" )->getProductsInteractPage( $productBase, 'reviews', $params, $pd->getPagination(), $pd->getTotalItemCount(), $pd->getItemCount() );
            ?>
        <?php
        }else{
        ?>
        <div style="margin-top:10px;padding:15px 0 30px;line-height:25px;border:1px solid #DDDDDD;">
        <div style="padding-left:20px;">
        暂无商品评价！争抢产品评价前5名，前5位评价用户可获得多倍京豆哦！（详见京豆规则）！ <br />
         只有购买过该商品的用户才能进行评价。  [发表评价]  [最新评价]
        </div>
        </div>
        <?php
        }
        ?>
        <?php
        //post review helpful
        ?>
        <style>
        .isloading-overlay {
            position: relative;
            text-align: center;
        }
        
        .isloading-wrapper {
            background: #FFFFFF;
            .border-radius(7px);
            display: inline-block;
            margin: 0 auto;
            top: 10%;
            z-index:9000;
        }
        .icon-spin{
            display: inline-block;
            height: 16px;
            line-height: 16px;
            vertical-align: text-top;
            width: 16px;
        }
        </style>
        <script language="javascript" src="/js/helpful.js"></script>
        <script language="javascript" src="/js/jquery.isloading.js"></script>
        <script language="javascript">
        $(document).ready(function() {
            $("input[name='helpful_button']").helpful();
        });
        </script>
    </div>
    <div class="cl"></div>
</div>
<?php
$this->contentWidget( "/layouts/widget/signin" );
$this->contentWidget( "/layouts/widget/message" );
?>
