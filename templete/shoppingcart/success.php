<?php
$clsPm = ClsFactory::instance( "ClsProductsMethod" );
$img = $clsPm->thumbnailImage( $productBase['products_images'], 120 );
$cart = ClsFactory::instance("ClsShoppingCart");

$productsLinks = ClsFactory::instance( "ClsSeo" )->getProductsLink( $productBase );

$currency = ClsFactory::instance( "ClsCurrency" );
$salesPrice = $currency->getCurrencyValues( $pts->getSalesPrice() );

$attributes = array();
if( $oProducts ) {
	$attributes = $oProducts->getProductsOptionsToValues();
}
?>
<div id="cart_success" class="cwidth">
    <div class="add_success" style="margin-top:25px;">
         1 <?php echo CART_ITEM_ADDED;?>
    </div>
    <div class="add_product">
        <div>
        <table style="margin:0 auto;"><tr>
            <td><div class="rvi">
            <a title="<?php echo $productsLinks['title'];?>" alt="<?php echo $productsLinks['alt'];?>" href="<?php echo $productsLinks['href'];?>"><img width="120" src="<?php echo $clsPm->thumbnailImage( $productBase['products_images'], 120 );?>"></a>
            <div>
                <h3><?php echo ClsFactory::instance( "ClsSeo" )->getProductsLink( $productBase, false );?></h3>
                <div>
                    <dl>
                        <dd><p class="save"></p></dd>
                        <dd>
                            <table>
                            <?php
                            if( $attributes ){
                                foreach( $attributes as $k => $v ) {
                                	echo "<tr><td >{$v['products_options_name']}</td><td > : {$v['products_options_values_name']}". ( isset( $n['desc'] ) ? $n['desc'] : "" ) ."</td></tr>";
                                }
                            }
                            ?>
                            </table>
                        </dd>
                        <dd><p class="save"> <?php echo $currency->getCurrency();?> <?php echo $currency->getCurrencySign();?><?php echo $salesPrice;?></p></dd>
                    </dl>
                </div>
                
            </div>
        </div></td>
            <td valign="top">
                <div class="cart_success_subtotal">
                    <input type="button" style="margin-top:10px;margin-right:20px;" value="<?php echo CART_BUTTON_CHECKOUT;?>" class="div_right cart_checkout_button">
                    <input onclick="javascript:window.location.href='<?php echo Hqw::getApplication()->createUrl('ShoppingCart/index');?>';" type="button" style="margin-top:10px;margin-right:20px;" value="<?php echo CART_BUTTON_EDIT;?>" class="div_right cart_edit_button">
                    <p><b><?php echo CART_SUBTOTAL;?>: <span class="save"><?php echo $currency->getCurrency();?> <?php echo $currency->getCurrencySign();?><?php echo $currency->getCurrencyValues( $cart->getSubtotal() );?></span></b></p>
                    <p class="lightshow"><b><?php echo $cart->getCount();?></b> <?php echo CART_ITEM_COUNT;?></p>
                    <p><h5>Your order can enjoy promotional</h5>
                    <h6>Free Shipping Over $99 Learn More>></h6> </p>
                </div>
            </td>
        </tr></table>
        </div>
        <?php
        $categoriesId = $productBase['categories_id'];
        if( $categoriesId ) {
        	$categories = Hqw::getApplication()->getModels( "categories" );
        	$categories = $categories->join( Hqw::getApplication()->getModels( "categories_description" ), array( 'on'=>'categories_id' ) );
        	$cResult = $categories->fetch( array( "categories_status"=>1 ) );
        	
        	$ptc = Hqw::getApplication()->getModels( "products_to_categories" );
        	$ptc = $ptc->join( Hqw::getApplication()->getModels( "products" ), array( 'on'=>'products_id' ) );
        	$ptc = $ptc->join( Hqw::getApplication()->getModels( "products_status" ), array( 'on'=>'products_id' ) );
        	$ptc = $ptc->order( " sales_total DESC ", Hqw::getApplication()->getModels( "products_status" ) );
        	$ptc = $ptc->where( array( 'products_status'=>1 ), "AND", "products" );
        	$ptc = $ptc->group( "products_id" );
        	$ptc = $ptc->limit( 5 );
        	$clsSeo = ClsFactory::instance("ClsSeo");
        	$currency = ClsFactory::instance( "ClsCurrency" );
        	$result = $ptc->fetchAll( array('categories_id'=>(int)$categoriesId) );
        	if( !empty( $result ) ) {
        	    $count = count($result);
        ?>
        <div id="cart_favorite" class="recommend" style="margin-top:25px;border:none;border-top:1px solid #CCCCCC;">
            <table width="<?php echo $count * 2;?>0%">
                <tr><td colspan="5" class="title"><?php echo CART_ACT_BESTSELLERS;?> <?php echo $cResult['categories_name'];?></td></tr>
                <tr class="p">
                    <?php
                    foreach( $result as $k => $v ) {
                    	$pts = ClsProductsFactory::instance( $v['products_id'] );
                    	$itemBase = $pts->getBase();
                    	$savePercent = $pts->getSavePercent();
                    	$link = $clsSeo->getProductsLink($itemBase);
                    ?>
                    <td width="20%" valign="top"><div class="other"><?php echo $clsSeo->getProductsSeoImages( $v['products_id'], 120 );?></div>
                    <h3 class="dec"><?php echo $clsSeo->getProductsSeoLink( $v['products_id'] );?></h3>
                    <p class="save"><?php echo $currency->getCurrency() . " " . $currency->getCurrencySign() . $currency->getCurrencyValues( $pts->getSalesPrice() );?></p>
                    <p class="sw s_star_<?php echo $pts->getProductsRating();?>"><span></span><?php echo $pts->getProductsReviewsCount();?></p>
                    
                    </td>
                    <?php
                    }
                    ?>
                </tr>
            </table>
        </div>
        <?php
            }
            
            $ptc = Hqw::getApplication()->getModels( "products_to_categories" );
        	$ptc = $ptc->join( Hqw::getApplication()->getModels( "products" ), array( 'on'=>'products_id' ) );
        	$ptc = $ptc->join( Hqw::getApplication()->getModels( "products_status" ), array( 'on'=>'products_id' ) );
        	$ptc = $ptc->order( " online_date DESC ", Hqw::getApplication()->getModels( "products" ) );
        	$ptc = $ptc->where( array( 'products_status'=>1 ), "AND", "products" );
        	$ptc = $ptc->group( "products_id" );
        	$ptc = $ptc->limit( 5 );
        	$newReleasesResult = $ptc->fetchAll( array('categories_id'=>(int)$categoriesId) );
        	if( !empty( $newReleasesResult ) ) {
        	    $count = count($newReleasesResult);
        ?>
        <div id="cart_favorite" class="recommend" style="margin-top:10px;border:none;border-top:1px solid #CCCCCC;">
            <table width="<?php echo $count * 2;?>0%">
                <tr><td colspan="5" class="title"><?php echo CART_ACT_NEW_RELEASES;?> <?php echo $cResult['categories_name'];?></td></tr>
                <tr class="p">
                    <?php
                    foreach( $newReleasesResult as $k => $v ) {
                    	$pts = ClsProductsFactory::instance( $v['products_id'] );
                    	$itemBase = $pts->getBase();
                    	$savePercent = $pts->getSavePercent();
                    	$link = $clsSeo->getProductsLink($itemBase);
                    ?>
                    <td width="20%" valign="top"><div class="other"><?php echo $clsSeo->getProductsSeoImages( $v['products_id'], 120 );?></div>
                    <h3 class="dec"><?php echo $clsSeo->getProductsSeoLink( $v['products_id'] );?></h3>
                    <p class="save"><?php echo $currency->getCurrency() . " " . $currency->getCurrencySign() . $currency->getCurrencyValues( $pts->getSalesPrice() );?></p>
                    <p class="sw s_star_<?php echo $pts->getProductsRating();?>"><span></span><?php echo $pts->getProductsReviewsCount();?></p>
                    
                    </td>
                    <?php
                    }
                    ?>
                </tr>
            </table>
        </div>
        <?php
        	}
        }
        ?>
        
        <?php
        
        $history = ClsFactory::instance( "ClsSignin" )->getHistory( 5, $productBase['products_id'] );

        if( !empty( $history ) ) {
            $count = count($history);
        ?>
        <div id="cart_favorite" class="recommend" style="margin-top:10px;border:none;border-top:1px solid #CCCCCC;">
            <table width="<?php echo $count * 2;?>0%">
                <tr><td colspan="5" class="title"><?php echo USER_RECENTLY_VIEWED;?></td></tr>
                <tr class="p">
                    <?php
                    foreach( $history as $k => $v ) {
                    	$pts = ClsProductsFactory::instance( $v['products_id'] );
                    	$itemBase = $pts->getBase();
                    	$savePercent = $pts->getSavePercent();
                    	$link = $clsSeo->getProductsLink($itemBase);
                    ?>
                    <td width="20%" valign="top"><div class="other"><?php echo $clsSeo->getProductsSeoImages( $v['products_id'], 120 );?></div>
                    <h3 class="dec"><?php echo $clsSeo->getProductsSeoLink( $v['products_id'] );?></h3>
                    <p class="save"><?php echo $currency->getCurrency() . " " . $currency->getCurrencySign() . $currency->getCurrencyValues( $pts->getSalesPrice() );?></p>
                    <p class="sw s_star_<?php echo $pts->getProductsRating();?>"><span></span><?php echo $pts->getProductsReviewsCount();?></p>
                    
                    </td>
                    <?php
                    }
                    ?>
                </tr>
            </table>
        </div>
        <?php
        }
        ?>
        <?php
        /*
        <div id="cart_favorite" class="recommend" style="margin-top:10px;border:none;border-top:1px solid #CCCCCC;">
            <table width="100%">
                <tr><td colspan="5" class="title">Customers Who Bought Items Also Bought</td></tr>
                <tr class="p">
                    <td><div class="other"><img src="<?php echo $img;?>"></div>
                    <h3 class="dec">A-Line Sweetheart Short Satin & Chiffon Informal Wedding Dress</h3>
                    <p class="save">USD $349.99</p>
                    <p class="sw s_star_1"><span></span>(12)</p>
                    
                    </td>

                    <td><div class="other"><img src="<?php echo $img;?>"></div>
                    <h3 class="dec">A-Line Sweetheart Short Satin & Chiffon Informal Wedding Dress</h3>
                    <p class="save">USD $349.99</p>
                    <p class="sw s_star_1"><span></span>(12)</p>
                    
                    </td>

                    <td><div class="other"><img src="<?php echo $img;?>"></div>
                    <h3 class="dec">A-Line Sweetheart Short Satin & Chiffon Informal Wedding Dress</h3>
                    <p class="save">USD $349.99</p>
                    <p class="sw s_star_1"><span></span>(12)</p>
                    
                    </td> 

                    <td><div class="other"><img src="<?php echo $img;?>"></div>
                    <h3 class="dec">A-Line Sweetheart Short Satin & Chiffon Informal Wedding Dress</h3>
                    <p class="save">USD $349.99</p>
                    <p class="sw s_star_1"><span></span>(12)</p>
                    
                    </td>
                    
                    <td><div class="other"><img src="<?php echo $img;?>"></div>
                    <h3 class="dec">A-Line Sweetheart Short Satin & Chiffon Informal Wedding Dress</h3>
                    <p class="save">USD $349.99</p>
                    <p class="sw s_star_1"><span></span>(12)</p>
                    
                    </td>
                </tr>
            </table>
        </div>
        */
        ?>
    </div>
    <div class="cl"></div>
</div>