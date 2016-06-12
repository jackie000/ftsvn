<?php
$this->contentWidget( "/layouts/breadcrumb/products", array('productBase'=>$productBase) );
?>
<script language="javascript">
$(document).ready(function() {
    
    $("a[quantity]").click(function(){
        var action = $(this).attr("quantity");
        var old_value = parseInt($("#quantity").val());
        if (action == "add"){
            old_value++;
        }else if(action == "reduce"){
            if(old_value > 1){
                old_value--;
            }
        }
        $("#quantity").val(old_value);
        return false;
    });
    
    $('.jqzoom').jqzoom({
        zoomType: 'standard',
        lens:true,
        preloadImages: false,
        alwaysOn:false,
        zoomWidth: 600,
        zoomHeight: 472,
        title:false
    });

    $("#box").roll();
});
</script>
<?php
$clsPm = ClsFactory::instance( "ClsProductsMethod" );
$productsLinks = ClsFactory::instance( "ClsSeo" )->getProductsLink( $productBase );
?>
<div itemscope itemtype="http://schema.org/Product">
<div class="product_buy_area">
    <div class="product_images div_left" id="box">
        <dl id="thumblist" class="image_small">
            <dd class="pre_button up"></dd>
            <dl class="list real">
                <?php
                if( $images ) {
                	foreach( $images as $k=>$im ) {
                		if( $k == 0 ) {
                			echo "<dd><a title='Zoom ". $productsLinks['title'] ."' alt='Zoom ". $productsLinks['alt'] ."' class=\"zoomThumbActive\" href='javascript:void(0);' rel=\"{gallery: 'gal1', smallimage: '". $clsPm->thumbnailImage( $im, 360 ) ."',largeimage: '". $clsPm->thumbnailImage( $im ) ."'}\">
                                <img title='Zoom ". $productsLinks['title'] ."' alt='Zoom ". $productsLinks['alt'] ."' src='" . $clsPm->thumbnailImage( $im, 64 ) . "' width=\"64\"></a>
                            </dd>";
                		}else{
                		    echo "<dd><a title='Zoom ". $productsLinks['title'] ."' alt='Zoom ". $productsLinks['alt'] ."' href='javascript:void(0);' rel=\"{gallery: 'gal1', smallimage: '". $clsPm->thumbnailImage( $im, 360 ) ."',largeimage: '". $clsPm->thumbnailImage( $im ) ."'}\">
                                <img title='Zoom ". $productsLinks['title'] ."' alt='Zoom ". $productsLinks['alt'] ."' src='" . $clsPm->thumbnailImage( $im, 64 ) . "' width=\"64\"></a>
                            </dd>";
                		}
                	}
                }
                ?>
            </dl>
            <dd class="next_button down"></dd>
        </dl>
        <dl class="image_large">
            <dd style="text-align:center;">
                <a title='Zoom <?php echo $productsLinks['title'];?>' alt='Zoom <?php echo $productsLinks['alt'];?>' href="<?php echo $clsPm->thumbnailImage( $productBase['products_images'] );?>" class="jqzoom" rel='gal1'>
                    <img title='Zoom <?php echo $productsLinks['title'];?>' alt='Zoom <?php echo $productsLinks['alt'];?>' src="<?php echo $clsPm->thumbnailImage( $productBase['products_images'], 360 );?>" width="360">
                </a>
            </dd>
        </dl>
    </div>
    <?php
    $reviewsLink = ClsFactory::instance( "ClsSeo" )->getProductsInteractLink( $productBase );
    ?>
    <div class="buy_information div_left">
        <div class="product_title cwidth">
            <h1 itemprop="name"><?php echo $productBase['sales_text'];?> <?php echo $productBase['products_name'];?> <?php echo $productBase['promotion_text'];?></h1>
        </div>
        <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="review_wishlist sw s_star_<?php echo ClsProductsFactory::instance( $productBase['products_id'] )->getProductsRating();?>">
            <label class="div_left" style="width:200px;cursor:default;"><?php echo PRODUCTS_SHOW_ITEM_CODE;?>: <?php echo $productBase['products_code'];?></label>
            <span></span> (<a title="<?php echo $reviewsLink['title'];?>" alt="<?php echo $reviewsLink['alt'];?>" href="<?php echo $reviewsLink['href'];?>"><b ><font itemprop="reviewCount"><?php echo $productStatus['review'] ?  $productStatus['review'] : 0;?></font><?php echo PRODUCTS_SHOW_CUSTOMER_REVIEWS;?></b></a>) | 
            <a href="javascript:void(0);" class="favorite"><span></span><?php echo PRODUCTS_SHOW_FAVORITE;?></a> (<font id="favorites_number"><?php echo $productStatus['favorites']?$productStatus['favorites']:0;?></font>)
            <font itemprop="ratingValue" class="show_hide"><?php echo str_replace( "1", "5", str_replace( "_", ".", ClsProductsFactory::instance( $productBase['products_id'] )->getProductsRating() ) );?></font>
        </div>
        <?php
        $currency = ClsFactory::instance( "ClsCurrency" );
        $salesPrice = $currency->getCurrencyValues( $pts->getSalesPrice() );
        $marketPrice = $currency->getCurrencyValues( $pts->getMarketPrice() );
        $savePrice = $currency->getCurrencyValues( $pts->getSavePrice() );
        $savePercent = $pts->getSavePercent();
        ?>
        <div class="product_price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
            <table width="100%">
            <tr><td class="item"><?php echo PRODUCTS_SHOW_LIST_PRICE;?></td><td class="value normal_price"><?php echo $currency->getCurrency();?> <?php echo $currency->getCurrencySign();?><?php echo $marketPrice;?></td>
                <td rowspan="3" class="sales_active">
                    <div class="buy_attention div_right">
                        <div>
                            <table width="100%">
                                <tr>
                                <td class="color_chart">Color Chart</td>
                                <td class="quality">Quality Guarantee</td>
                                </tr>
                                <!--<td class="buy_free_shipping">Free Shipping</td>
                                    <td class="buy_delivery">17-26 Days Arrived</td>
                                </tr>-->
                                <tr>
                                    <td class="size_charts">Size Charts</td>
                                    <td class="return">Return Policy</td>
                                </tr>
                                
                                <tr>
                                <td class="measuring">Measuring Guide</td>
                                
                                    <td class="faqs">FAQS</td>
                                </tr>
                            </table>
                            <div class="cl"></div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr><td class="item"><?php echo PRODUCTS_SHOW_PRICE;?></td><td class="value n_p price"><span itemprop="priceCurrency" content="<?php echo $currency->getCurrency();?>"><?php echo $currency->getCurrency();?></span> <?php echo $currency->getCurrencySign();?><span itemprop="price" content="<?php echo $salesPrice;?>"><?php echo $salesPrice;?></span></td>            </tr>
            <tr><td class="item"><?php echo PRODUCTS_SHOW_YOU_SAVE;?></td><td class="value save"><?php echo $currency->getCurrency();?> <?php echo $currency->getCurrencySign();?><?php echo $savePrice;?> (<?php echo $savePercent;?>)</td></tr>
            <link itemprop="availability" href="http://schema.org/InStock" />
            </table>
        </div>
        <?php
        $attributes = array();
        if( $productAttribute ) {
        	foreach( $productAttribute as $k => $v ) {
        		if( $v['show_order_count'] > 0 ) {
        			$attributes[$k] = $v;
        		}
        	}
        }
        $showOrderCount = count($attributes);
        ?>
        <style>
        <?php
        if( $showOrderCount == 2 ) {
            echo ".buy_information .buy_options {
                    line-height: 45px;
                    }";
            echo ".buy_information .buy_options {
                    padding-bottom:32px;
                    }";
        }
        
        if( $showOrderCount == 3 ) {
            echo ".buy_information .buy_options {
                    padding-bottom:32px;
                    }";
            echo ".buy_information .buy_options {
                    line-height: 40px;
                    }";
        }
        
        if( $showOrderCount == 4 ) {
            echo ".buy_information .buy_options {
                    padding-bottom:18px;
                    }";
        }
        
        if( $showOrderCount == 5 ) {
            echo ".buy_information .buy_options {
                    padding:5px 0;
                    }";
            echo ".buy_information .buy_icon {
                    padding: 2px 0 0 60px;
                    }";
        }
        ?>
        
        </style>
        <div class="buy_options">
        
            <link rel="stylesheet" type="text/css" href="/js/validationEngine.jquery.css" />
            <script language="javascript" src="/js/jquery.validationEngine.js"></script>
            <script language="javascript">
            $(document).ready(function() {
                $("[class^=validate]").validationEngine({
            		success :  function(){
            		    $("#frm").submit();
            		},
            		failure : function() {}
            	})
            });
            </script>
            <form id="frm" method="POST" action="<?php echo Hqw::getApplication()->createUrl('ShoppingCart/HandleBuy');?>">
            <input type="hidden" name="id" value="<?php echo $productBase['products_id'];?>">
            <div class="options">
                <table width="100%">
                    <tr>
                        <td>
                            <table>
                            <?php
                            if( $attributes ) {
                            	foreach( $attributes as $k => $v ) {
                            		echo "<tr>";
                                    echo "<td class=\"buy_options_item\">".ucfirst(strtolower($v['products_options_name'])) ." :</td>";
                                    echo "<td>";
                                    echo eHtml::createAttributesHtml( $v, " class='validate[required]' type='select-one'" );
                                    echo "</td></tr>";
                            	}
                            }
                            ?>
                            
                            <tr><td class="buy_options_item">Quantity :</td>
                            <td>
                                <a href="javascript:void(0);" quantity="reduce"><em class="oper_sign reduce">reduce</em></a>
                                <input class="validate[custom[onlyNumber],length[1,2]]" type="text" name="quantity" id="quantity" style="text-align:center;width:50px;margin:0px 5px 0px 3px;" class="text" value="1">
                                <a href="javascript:void(0);" quantity="add"><em class="oper_sign add">add</em></a></td>
                            </tr>
                            </table>
                            
                        </td>
                        <td valign="top" width="230">
                        <div style="padding-bottom:8px;"><img src="/images/Norton_Horiz_RGB.jpg" width="188"></div>
                        <div style="padding-top:5px;border-top:1px dashed #ccc; "><img src="/images/verified-by-google.jpg"></div>
                        </td>
                        </tr>
                </table>
            </div>
            <?php
            $currUser = ClsFactory::instance( "ClsSignin" )->getCookieUser();
            $fa = false;
            if( $currUser ) {
            	$fa = $currUser->getFavorites( $productBase['products_id'] );
            }
            ?>
            
            <script language="javascript" src="/js/favorites.js"></script>
            <script language="javascript">
            $(document).ready(function() {
                $("a[name='cancelFavorites']").hide();
                $("a[name='favorites']").hide();
                $("a[name='favorites']").favorites();
                $("a[name='cancelFavorites']").cancelFavorites();
                
                <?php 
                if( $fa ) {
                ?>
                $("a[name='cancelFavorites']").show();
                <?php
                }else{
                ?>
                $("a[name='favorites']").show();
                
                <?php    
                }
                ?>
            });
            </script>
            <div class="buy_icon">
                <div class="div_left"><input type="image" src="/images/buy_now.png"></div>
                <div class="div_left wishlist"><a products_id="<?php echo $productBase['products_id'];?>" 
                <?php 
                echo " name=\"cancelFavorites\" class=\"favorited subt\" ";
                echo " url=\"" . Hqw::getApplication()->createUrl('UserActivities/CancelFavorites'). "\" ";
                ?> href="javascript:void(0);"><em><?php echo PRODUCTS_SHOW_ADD_FAVORITES;?></em></a>
                
                <a products_id="<?php echo $productBase['products_id'];?>" 
                <?php 
                echo " name=\"favorites\" class=\"subt\" ";
                echo " url=\"" . Hqw::getApplication()->createUrl('UserActivities/PostFavorites'). "\" ";
                ?> href="javascript:void(0);"><em><?php echo PRODUCTS_SHOW_ADD_FAVORITES;?></em></a>
                
                </div>
            </div>
            </form>
            
            <div class="cl"></div>
        </div>
    </div>
    <div class="cl"></div>
</div>

<?php
//buying together
$productsTogether = Hqw::getApplication()->getModels( "products_together" );
$productsTogether = $productsTogether->join( Hqw::getApplication()->getModels( "products" ), array( 'on'=>'products_id' ) );
$productsTogether = $productsTogether->where( array( 'products_status'=>1 ), "AND", "products" );
$productsTogether = $productsTogether->limit( 4 );
$togetherResult   = $productsTogether->fetchAll( array("products_id"=>$productBase['products_id']) );

if( !empty( $togetherResult ) ) {
    $clsSeo = ClsFactory::instance("ClsSeo");
    $currency = ClsFactory::instance( "ClsCurrency" );
    
?>
<div class="bought_together">
    <div class="title_section div_left"><?php echo PRODUCTS_SHOW_FREQUENTLY_BOUGHT_TOGETHER;?></div>
    <div class="div_right"></div>
    <div class="cl"></div>
</div>
<div class="together">
    <table>
        <tr>
            <td>
                <dl class="current">
                    <dt><?php echo $clsSeo->getProductsSeoImages( $productBase['products_id'], 120 );?></dt>
                </dl>
            </td>
            <?php
            $togetherPrice = $salesPrice;
            $togetherMarketPrice = $marketPrice;
            foreach( $togetherResult as $k => $v ) {
                $pts = ClsProductsFactory::instance( $v['together_id'] );
            	$itemBase = $pts->getBase();
            	$savePercent = $pts->getSavePercent();
            	$link = $clsSeo->getProductsLink($itemBase);
            	$togetherPrice += $currency->getCurrencyValues( $pts->getSalesPrice() );
            	$togetherMarketPrice += $currency->getCurrencyValues( $pts->getMarketPrice() );
            
            ?>
            <td class="plus">+</td>
            <td>
                <div class="other"><?php echo $clsSeo->getProductsSeoImages( $v['together_id'], 64 );?></div>
                <div class="dec">
                    <dl>
                        <dt><?php echo $clsSeo->getProductsSeoLink( $v['together_id'] );?></dt>
                        <dd class="save"><label><input type="checkbox" checked="checked"><?php echo $currency->getCurrency();?> <span class="list_money"><?php echo $currency->getCurrencySign();?><?php echo $currency->getCurrencyValues( $pts->getSalesPrice() );?></span></label></dd>
                    </dl>
                </div>
            </td>
            <?php
            }
            ?>
            <td class="plus">=</td>
            <td>
                <table>
                    <tr><td colspan="2" class="pop_combo_title"><?php echo PRODUCTS_SHOW_POPULARITY_COMBINATION;?></td></tr>
                    <tr><td class="buy_item">Original Price:</td><td class="value save price_line"><line><?php echo $currency->getCurrency();?> <?php echo $currency->getCurrencySign();?><?php echo $togetherMarketPrice;?></line></td></tr>
                    <tr><td colspan="2" class="pop_combo_price n_p price "><?php echo $currency->getCurrency();?> <?php echo $currency->getCurrencySign();?><?php echo $togetherPrice;?></td></tr>
                    <tr><td colspan="2" align="center"><div class="red_button"><?php echo PRODUCTS_BUTTOM_BUY_NOW;?></div></td></tr>
                </table>
            </td>
        </tr>
    </table>
</div>
<?php
}
?>
<div class="bought_together">
    <div class="title_section div_left"><?php echo PRODUCTS_SHOW_ITEM_DESCRIPTION;?></div>
    <div class="cl"></div>
</div>
<div class="item_description content_area" itemprop="description">
    <div class="left">
    <dl>
        <dt><span class="dotspan"></span><?php echo PRODUCTS_SHOW_PRODUCT_NAME;?></dt>
        <dd><?php echo ClsFactory::instance( "ClsSeo" )->getProductsLink( $productBase, false);?></dd>
    </dl>
    <dl>
        <dt><span class="dotspan"></span> <?php echo PRODUCTS_SHOW_ITEM_CODE;?></dt>
        <dd><?php echo $productBase['products_code'];?></dd>
    </dl>
    
    <dl>
        <dt><span class="dotspan"></span> <?php echo PRODUCTS_SHOW_CATEORIES;?></dt>
        <dd>
            <?php
            if( isset( $productBase['categories_id'] ) && $productBase['categories_id'] != 0 ) {
                $clsSeo = ClsFactory::instance("ClsSeo");
                $toCategories = $pts->getProductsToCategories();
                
                $all = array();
                if( !empty( $toCategories ) ) {
                	foreach( $toCategories as $k => $v ) {
                		$all[] = $clsSeo->getCategoriesLink( $v, false );
                	}
                	if( !empty( $all ) ) {
                		echo implode( "、", $all );
                	}
                }
            }
            ?>
        </dd>
    </dl>
    <?php
        if( $productAttribute ) {
            $clsSeo = ClsFactory::instance("ClsSeo");
        	foreach( $productAttribute as $k => $v ) {
        		if( $v['type'] == "readonly" ) {
        		    $descAttributes = array();
        		    if( is_array( $v['values'] ) && !empty( $v['values'] ) ) {
        		    	foreach( $v['values'] as $kk => $vv ) {
        		    	    if( $vv['show_description'] == 1 ) {
            		    		$productsAttr = array( 0=>array( "products_options_id"=>$k, 'products_options_name'=>$v['products_options_name'], 'products_options_values_id'=>$vv['products_options_values_id'], 'products_options_values_name'=>$vv['products_options_values_name'] ) );
            		    		$attrLink = $clsSeo->getCategoriesLink( array("categories_id"=>$productBase['categories_id']), true, $productsAttr );
            		    		$descAttributes[] = "<a href=\"{$attrLink['href']}\" alt=\"{$attrLink['alt']}\" title=\"{$attrLink['title']}\">". ucfirst( strtolower($vv['products_options_values_name']) ) ."</a>";
        		    	    }
        		    	}
        		    }
        		    if( !empty( $descAttributes ) ){
        		        if( strtolower($v['products_options_name'])!= "designer" ) {
                			echo "<dl>
                                    <dt><span class=\"dotspan\"></span>". ucfirst( strtolower($v['products_options_name']) ) .":</dt>
                                    <dd>". implode( ", ", $descAttributes ) ."</dd>
                                </dl>";
            			}
        			}
        		}
        	}
        }
    ?>
    </div>
    <div class="right">
        <div class="title" style="border-top-style:none;">
            QUALITY GUARANTEE
        </div>
        <div class="desc">
            <?php echo ucwords( Hqw::getApplication()->getComponent("Request")->getHostInfo(true) );?> is the world's leading wedding and formal dress supplier, with a great reputation for providing perfect dresses for any occasion. We have made tons of dresses for women and girls worldwide, and more and more customers are now choosing us as the place to shop for their big days and events!
        </div>
        
        <div class="title">
            PAYMENT METHODS
        </div>
        <div class="desc">
            we accept credit card, PayPal, as the payment methods.<br />
            1. Credit Card.<br />
            Buyers can pay by credit card via PayPal.<br />
            <br />
            2. PayPal<br />
            The most convenient payment method in the world. 
        </div>
        <div class="icon payment_icon"></div>
        
        <div class="title">
            SHIPPING METHODS
        </div>
        <div class="desc">
            This guide shows you how to choose a shipping method for your order. This following is a list of the different kinds of shipping methods available for you selection on <?php echo ucwords( Hqw::getApplication()->getComponent("Request")->getHostInfo(true) );?>. When you place an order, you can choose the shipping method which suits you best. And we are currently still working on some more shipping options; we will tell all our <?php echo ucwords( Hqw::getApplication()->getComponent("Request")->getHostInfo(true) );?> customers when the day is available.
        </div>
        <div class="icon shipping_icon"></div>
    </div>
    <div class="cl"></div>
</div>
<?php
/*
<div class="bought_together">
    <div class="title_section div_left"><?php echo PRODUCTS_SHOW_CUSTOMERS_WHO_BOUGHT_THIS_ITEM_ALSO_BOUGHT;?></div>
    <div class="div_right"></div>
    <div class="cl"></div>
</div>
<div class="together">
    <table>
        <tr>
            
            <td>
                <div class="also_bought"><img src="/images/p_l1.jpg" width="150"></div>
                <div class="also_bought_dec">
                    <dl>
                        <dt>A-Line Sweetheart Short Satin & Chiffon Informal Wedding Dress</dt>
                        <dd class="save">USD <span class="list_money">$349.99</span></dd>
                        <dd class="sw s_star_1"><span></span>(12)</dd>
                    </dl>
                </div>
            </td>
            
            <td>
                <div class="also_bought"><img src="/images/p_l1.jpg" width="150"></div>
                <div class="also_bought_dec">
                    <dl>
                        <dt>A-Line Sweetheart Short Satin & Chiffon Informal Wedding Dress</dt>
                        <dd class="save">USD <span class="list_money">$349.99</span></dd>
                        <dd class="sw s_star_1"><span></span>(12)</dd>
                    </dl>
                </div>
            </td>
            
            <td>
                <div class="also_bought"><img src="/images/p_l1.jpg" width="150"></div>
                <div class="also_bought_dec">
                    <dl>
                        <dt>A-Line Sweetheart Short Satin & Chiffon Informal Wedding Dress</dt>
                        <dd class="save">USD <span class="list_money">$349.99</span></dd>
                        <dd class="sw s_star_1"><span></span>(12)</dd>
                    </dl>
                </div>
            </td>

            
            <td>
                <div class="also_bought"><img src="/images/p_l1.jpg" width="150"></div>
                <div class="also_bought_dec">
                    <dl>
                        <dt>A-Line Sweetheart Short Satin & Chiffon Informal Wedding Dress</dt>
                        <dd class="save">USD <span class="list_money">$349.99</span></dd>
                        <dd class="sw s_star_1"><span></span>(12)</dd>
                    </dl>
                </div>
            </td>

            <td>
                <div class="also_bought"><img src="/images/p_l1.jpg" width="150"></div>
                <div class="also_bought_dec">
                    <dl>
                        <dt>A-Line Sweetheart Short Satin & Chiffon Informal Wedding Dress</dt>
                        <dd class="save">USD <span class="list_money">$349.99</span></dd>
                        <dd class="sw s_star_1"><span></span>(12)</dd>
                    </dl>
                </div>
            </td>

            <td>
                <div class="also_bought"><img src="/images/p_l1.jpg" width="150"></div>
                <div class="also_bought_dec">
                    <dl>
                        <dt>A-Line Sweetheart Short Satin & Chiffon Informal Wedding Dress</dt>
                        <dd class="save">USD <span class="list_money">$349.99</span></dd>
                        <dd class="sw s_star_1"><span></span>(12)</dd>
                    </dl>
                </div>
            </td>
        </tr>
    </table>
</div>
*/
?>

<div class="bought_together">
    <div class="title_section div_left"><?php echo PRODUCTS_SHOW_CUSTOMER_REVIEWS;?></div>
    <div class="div_right">
    <?php
    if( $productStatus['review'] ) {
    ?>
    
    <a title="<?php echo $reviewsLink['title'];?>" alt="<?php echo $reviewsLink['alt'];?>" href="<?php echo $reviewsLink['href'];?>" ><?php echo PRODUCTS_SHOW_SEE_ALL;?> <?php echo $productStatus['review'];?> <?php echo strtolower(PRODUCTS_SHOW_CUSTOMER_REVIEWS);?> »</a>
    
    <?php
    }
    ?>
    </div>
    <div class="cl"></div>
</div>
<?php
if( $productReviews ) {
    if( ($productsTags = ClsProductsFactory::instance( $productBase['products_id'] )->getProductsTags()) ) {
    	
    }else{
        $mostHelpfull = ClsProductsFactory::instance( $productBase['products_id'] )->getMostHelpfulReviews();
    }
    
    //var_dump($mostHelpfull);
    //
    
?>
<div class="content_area">
    <div class="rating div_left">
        <dl>
        <dt><?php echo PRODUCTS_SHOW_AVERAGE_RATING;?></dt>
        <dd class="sw bsw s_bstar_<?php echo ClsProductsFactory::instance( $productBase['products_id'] )->getProductsRating();?>"><span></span>
        <b><?php echo ClsFactory::instance("ClsProductsMethod")->getReviewsRatingFormat( $productStatus['review_rating'] );?> </b>
        (<a title="<?php echo $reviewsLink['title'];?>" alt="<?php echo $reviewsLink['alt'];?>" href="<?php echo $reviewsLink['href'];?>" ><?php echo $productStatus['review'];?><?php echo PRODUCTS_SHOW_CUSTOMER_REVIEWS;?></a>) </dd>
        <dd class="write_review"><a class="subt" href="<?php echo Hqw::getApplication()->createUrl('index/write_reviews',array('productsId'=>$productBase['products_id']));?>"><em><?php echo PRODUCTS_SHOW_WRITE_REVIEW;?></em></a></dd>
        </dl>
    </div>

    <div class="most_helpful div_left">
        
            <?php
            if( $productsTags ) {
                echo "<div style=\"padding:20px 20px 0px;\">
                <dt><h2>". PRODUCTS_SHOW_OWNER_IMPRESSION ."</h2></dt><dd class=\"tags\"><ul>";
            	foreach( $productsTags as $k => $v ) {
            	    $t = ClsFactory::instance( "ClsSeo" )->getProductsTags( $v['products_reviews_tags_id'], $v['products_reviews_tags_name'] );
            		echo "<li><span><a href=\"" .$t['href']. "\" alt=\"". $t['alt'] ."\" title=\"". $t['title'] ."\" >" . $t['name']  . " (" . $v['tot'] . ")</a> </span></li>";
            	}
            	echo "</ul></dd></div>";
            }else{
                
            
                if( $mostHelpfull ) {
                    echo "<dl>
                <dt><h2>" . PRODUCTS_SHOW_MOST_HELPFUL_REVIEWS . "</h2></dt>";
                	foreach( $mostHelpfull as $k => $v ) {
                	    $userMost = ClsUserFactory::instance( $v['user_id'] )->getBase();
                		echo "<dd class=\"most_reviews_content\"><em>“ {$v['mark']} ”</em> <br/>" . $userMost['username'] . "  |  {$v['helpful']} ". PRODUCTS_SHOW_REVIEWERS_MADE_SIMILAR_STATEMENT ." </dd>";
                	}
                	echo "</dl>";
                }else{
                    //review ad
                    echo "<img src=\"/images/products_ad_reviews.jpg\">";
                }
                
            }
            ?>
        
    </div>
    <div class="cl"></div>
</div>
<div class="content_area">
    <div class="reviews_list" style="border-bottom:none;">
    <div class="reviews_list_sort">
    <?php
    $clen = count( $types );
    $cval = array();
    for( $i=0; $i<$clen; $i++ ){
        $cval[] = 0;
    }
    $levelCount = array_combine( $types, $cval );
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
    <div class="review_record"  itemprop="review" itemscope itemtype="http://schema.org/Review">
        <div class="user div_left">
            <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
            <div class="sw s_star_<?php echo $v['rating'];?>"><span></span></div>
            <meta itemprop="worstRating" content = "1"/>
            <span itemprop="bestRating" class="show_hide">5</span>
            <span itemprop="ratingValue" class="show_hide"><?php echo $v['rating'];?></span>
            </div>
            <div><?php echo PRODUCTS_SHOW_REVIEW_BY;?> <span itemprop="author"><?php echo $userBase['reviews_by_user'];?></span> </div>
            <div><?php echo PRODUCTS_SHOW_REVIEW_FROM;?> <?php echo isset( $userBase['country'] ) ? $userBase['country'] : "unknow";?></div>
            <div><?php echo date("M /d / Y H:i:s", strtotime($v['odate']))?></div>
            <meta itemprop="datePublished" content="<?php echo $v['odate'];?>">
        </div>

        <div class="user_comments div_left">
            <div class="tags">
                <?php
                if( $v['tags'] != '' ) {
                    echo PRODUCTS_SHOW_TAGS;
                	$tags = explode( ",", $v['tags'] );
                	foreach( $tags as $tag ) {
                		if( ( $pos = strpos( $tag, "-" ) ) !== false ) {
                			echo "<span>" . ClsFactory::instance( "ClsSeo" )->getProductsTags( substr( $tag, 0, $pos ), substr( $tag, ( $pos+1 )), false ) . "</span>";
                		}
                	}
                }else{
                    //echo "<span> null </span>";
                }
                ?>
            </div>
            
            <div class="content" itemprop="description">
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
                <?php echo PRODUCTS_SHOW_WAS_HELPFUL;?> <span class="btn"><input type="button" name="helpful_button" products_reviews_id="<?php echo $v['products_reviews_id'];?>" helpful='Y' value="<?php echo PRODUCTS_SHOW_HELPFUL_YES;?>" url="<?php echo Hqw::getApplication()->createUrl('UserActivities/PostHelpful');?>" ></span> (<span helpful="<?php echo $v['products_reviews_id'];?>_Y"><?php echo $v['helpful'];?></span>) 
                <span class="btn"><input type="button"  name="helpful_button" products_reviews_id="<?php echo $v['products_reviews_id'];?>" helpful='N' value="<?php echo PRODUCTS_SHOW_HELPFUL_NO;?>" url="<?php echo Hqw::getApplication()->createUrl('UserActivities/PostHelpful');?>" ></span> (<span helpful="<?php echo $v['products_reviews_id'];?>_N"><?php echo $v['helpless'];?></span>)
            </div>
        </div>
        <div class="cl"></div>
    </div>
    <?php
    }
    ?> 
<?php
}else{
?>
<div class="together" style="padding:15px 0 30px;line-height:25px;">
<div style="padding-left:20px;">

</div>
</div>
<?php
}
?>
</div>
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


<?php
/*
$questionsLink = ClsFactory::instance( "ClsSeo" )->getProductsInteractLink( $productBase, "questions" );
?>
<div class="bought_together">
    <div class="title_section div_left"><?php echo PRODUCTS_SHOW_QUESTIONS_ANSWERS;?></div>
    <div class="div_right">
    <?php
    if( $productStatus['questions'] ) {
    ?>
    
    <a title="<?php echo $questionsLink['title'];?>" alt="<?php echo $questionsLink['alt'];?>" href="<?php echo $questionsLink['href'];?>" ><?php echo PRODUCTS_SHOW_SEE_ALL;?> <?php echo $productStatus['questions'];?> <?php echo PRODUCTS_SHOW_QUESTIONS_AND_ANSWERS;?> »</a>
    
    <?php
    }
    ?>
    </div>
    <div class="cl"></div>
</div>
<?php
if( $productQuestion ){

?>
<div class="content_area">
    <?php
    foreach( $productQuestion as $k => $v ) {
        $userQuestion = ClsUserFactory::instance( $v['questioner_user_id'] )->getBase();
    ?>
     <table width="100%" class="fqa">
        <tr>
            <td class="item"><h2><?php echo PRODUCTS_SHOW_QA_Q;?></h2></td>
            <td class="content"><?php echo $v['mark'];?></td>
            <td class="customer"><?php echo PRODUCTS_SHOW_REVIEW_BY;?> <b><?php echo $userQuestion['username'];?></b></td>
            <td class="date"><?php echo $v['odate'];?></td>
        </tr>
        <tr>
            <td class="item"><h2><?php echo PRODUCTS_SHOW_QA_A;?></h2></td>
            <td class="content" style="color:#ff6600;"><?php echo $v['answers_mark'];?></td>
            <td colspan="2"><?php echo $v['answers_odate'];?></td>
        </tr>
     </table>
     <?php	
    }
    ?>
</div>
<?php
}else{
?>
<div class="together" style="padding:15px 0 30px;line-height:25px;">
<div style="padding-left:20px;">
</div>
</div>
<?php
}
*/
?>

<?php
$this->contentWidget( "/layouts/widget/recent-history", array('productsId'=>$productBase['products_id']) );
$this->contentWidget( "/layouts/widget/signin" );
$this->contentWidget( "/layouts/widget/message" );
?>

<div id="body">
    <div id="part3" class="cwidth">
        <img src="/images/5358.jpg" width="1080">
        <div class="cl"></div>
    </div>
</div>    