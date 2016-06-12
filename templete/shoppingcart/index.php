<?php
$clsPm = ClsFactory::instance( "ClsProductsMethod" );
?>
<div id="category_top_image" class="cwidth">
    <img src="/images/category_top_image.jpg" width="980">
</div>

<div id="breadcrumb" class="cwidth">
    <ul>
        <li class="title"><?php echo "<a href=\"" . Hqw::getApplication()->getComponent("Request")->getHostInfo() . "\">" . ucwords( Hqw::getApplication()->getComponent("Request")->getHostInfo(true) ) . "</a>";?></li>
        <li class="space">></li>
        <li class="cur title"><?php echo CART_SHOPPING_CART;?></li>
    </ul>
    <div class="cl"></div>
</div>

<div id="mycart" class="cwidth">
    <?php
    $this->contentWidget( "/layouts/cart/tips" );
    ?>
    <div class="div_left" style="margin-top:25px;">
        <?php
        if( $cart ) {
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
        <script language="javascript" src="/js/cart.js"></script>
        <script language="javascript">
        $(document).ready(function() {
            
            $(".quantity_update").hide();
            $("a[cartId]").updateQuantity();
            $("a[remove]").removeItem();
            $("input[name='checked_all']").checkedAllItem("<?php echo Hqw::getApplication()->createUrl('ShoppingCart/UpdateCheckoutSelected');?>", $("input[name^='checkout_select']") );
            $("input[name^='checkout_select']").checkedItem("<?php echo Hqw::getApplication()->createUrl('ShoppingCart/UpdateCheckoutSelected');?>", $("input[name^='checkout_select']"), $("input[name='checked_all']") );
            
            
            $("input[id^='quantity_']").change(function(){
                var num = /^\d+$/;
                if( !num.test( $(this).val() ) ){
                    $(this).val(1);
                }
                $(this).next().next().show();
                $(this).next().next().find("a").attr("quantityValue", $(this).val());
            });
            
            $("a[quantity]").click(function(){
                var action = $(this).attr("quantity");
                var sign = $(this).attr("sign");
                var old_value = parseInt($("#quantity_" + sign).val());
                if (action == "add"){
                    old_value++;
                }else if(action == "reduce"){
                    if(old_value > 1){
                        old_value--;
                    }
                }
                
                var num = /^\d+$/;
                if( !num.test( old_value ) ){
                    old_value = 1;
                }
                
                $("[cartId='"+sign+"']").parent().show();
                $("[cartId='"+sign+"']").attr("quantityValue", old_value);
                
                $("#quantity_" + sign).val(old_value);
                
                return false;
            });
        });
        </script>
        <div class="cartContent p_list" style="line-height:25px;">
            <?php
            $chAll = "checked=\"checked\"";
            foreach( $cart as $k => $v ) {
                if( $v->getSelected() == false ) {
                	$chAll = '';
                	break;
                }
            }
            ?>
            <div class="div_left checkbox chk itemfont"><input type="checkbox" name="checked_all" <?php echo $chAll;?>></div>
            <div class="div_left item itemfont"><?php echo CART_CART_LIST_ITEM;?></div>
            <div class="div_left price itemfont"><?php echo CART_CART_LIST_PRICE;?></div>
            <div class="div_left qty itemfont"><?php echo CART_CART_LIST_QUANTITY;?></div>
            <div class="cl"></div>
        </div>
        <?php
        
        foreach( $cart as $k => $v ) {
            $itemProducts = $v->getProducts();
            $productsBase = $itemProducts->getBase();
            $productsLinks = ClsFactory::instance( "ClsSeo" )->getProductsLink( $productsBase );
            
            $attributes = $v->getProductsOptionsToValues();
            $currency = ClsFactory::instance( "ClsCurrency" );
            $salesPrice = $currency->getCurrencyValues( $v->getProductsSales() );
            $savePrice = $currency->getCurrencyValues( $itemProducts->getSavePrice() );
            $savePercent = $itemProducts->getSavePercent();
        ?>
        <div class="cartContent p_list border-top cartId<?php echo $v->getShoppingCartId();?>">
            <div class="div_left checkbox">
                <input type="checkbox" class="list" name="checkout_select[<?php echo $v->getShoppingCartId();?>]" value="<?php echo $v->getShoppingCartId();?>" <?php echo $v->getSelected() ? "checked='checked'":"";?>>
            </div>
            <div class="div_left">
            <a title='<?php echo $productsLinks['title'];?>' alt='<?php echo $productsLinks['alt'];?>' href="<?php echo $productsLinks['href'];?>"><img title='<?php echo $productsLinks['title'];?>' alt='<?php echo $productsLinks['alt'];?>' src="<?php echo $clsPm->thumbnailImage( $productsBase['products_images'], 64 );?>" width="64"></a>
            </div>
            <div class="div_left itemcontent">
                <dl>
                    <dt style="word-wrap: break-word;word-break:normal;"><?php echo ClsFactory::instance( "ClsSeo" )->getProductsLink( $productsBase, false );?><dt>
                    <dd style="height:10px;"></dd>
                    <?php
                    if( $attributes ){
                        foreach( $attributes as $m => $n ) {
                        	echo "<dd><b class=\"greencolor itemfont\">{$n['products_options_name']} : {$n['products_options_values_name']}". ( isset( $n['desc'] ) ? $n['desc'] : "" ) ."</b></dd>";
                        }
                    }
                    ?>
                    <dd style="height:10px;"></dd>
                    <dd><a href="javascript:void(0);" remove="<?php echo $v->getShoppingCartId();?>" url="<?php echo Hqw::getApplication()->createUrl('ShoppingCart/HandleRemove');?>" class="imp"><?php echo CART_ACT_REMOVE;?></a></dd>
                </dl>
            </div>
            <div class="div_left price">
                <dl>
                    <dt class="value n_p price"><?php echo $currency->getCurrency();?> <?php echo $currency->getCurrencySign();?><?php echo $salesPrice;?></dt>
                    <dd class="value save saveprice"><?php echo CART_YOU_SAVE;?><br/><?php echo $currency->getCurrency();?> <?php echo $currency->getCurrencySign();?><?php echo $savePrice;?> (<?php echo $savePercent;?>)</dd>
                </dl>
            </div>

            <div class="div_left qty">
                <a sign="<?php echo $v->getShoppingCartId();?>" quantity="reduce" href="javascript:void(0);"><em class="oper_sign reduce">reduce</em></a>
                <input onkeyup="value=value.replace(/[^\d]/g,1)" id="quantity_<?php echo $v->getShoppingCartId();?>" class="text" type="text" value="<?php echo $v->getQuantity();?>" style="margin:0px 5px 0px 3px;" name="quantity">
                <a sign="<?php echo $v->getShoppingCartId();?>" quantity="add" href="javascript:void(0);"><em class="oper_sign add">add</em></a>
                <p class="quantity_update"><a href="javascript:void(0);" url="<?php echo Hqw::getApplication()->createUrl('ShoppingCart/UpdateQuantity');?>" cartId="<?php echo $v->getShoppingCartId();?>" quantityValue="0" class="greencolor" style="text-decoration:underline;"><?php echo CART_ACT_UPDATE;?></a></p>
            </div>
            <div class="cl"></div>
        </div>
        <?php
        }
        ?>

        <div class="cartContent p_list border-top txt-right">
            <b><?php echo CART_SUBTOTAL;?>:</b> <b class="value n_p price" show="subtotal"><?php echo $currency->getCurrency();?> <?php echo $currency->getCurrencySign();?><?php echo $currency->getCurrencyValues( $sc->getSubtotal() );?></b>
        </div>
        <div class="cartContent p_list txt-right">
            <a href="#"><h3 class="paypal_checkout div_right"></h3></a>
        </div>
        <div class="empty_tips p_list" style="z-index:20px;display:none;">
            <?php
            echo "<b>" . CART_NOT_PRODUCTS_RECORD . "</b>";
            echo "<br />";
            echo CART_KEEPPING_SHOPPING;
            ?>
        </div>
        <?php
        }else{
        ?>
        <div class="p_list" style="z-index:20px;">
            <?php
            echo "<b>" . CART_NOT_PRODUCTS_RECORD . "</b>";
            echo "<br />";
            echo CART_KEEPPING_SHOPPING;
            ?>
        </div>
        <?php
        }
        ?>
        <div class="cl"></div>
        
        
        <?php
            $this->contentWidget( "/layouts/cart/my-favorites" );
            $this->contentWidget( "/layouts/cart/special-offer" );
        ?>
    </div>

    <div class="div_left cart-standard-right">
        <?php
        if( $cart ) {
        ?>
        <dl class="cartContent total">
            <dd><b style="font-size:12px;color:#272727;"><?php echo CART_SUBTOTAL;?></b>(<span show="count"><?php echo $sc->getCount()?></span> <?php echo CART_ITEMS;?>): 
            <b class="value n_p price" style="font-size:14px;padding-right:0px;" show="subtotal"><?php echo $currency->getCurrency();?> <?php echo $currency->getCurrencySign();?><?php echo $currency->getCurrencyValues( $sc->getSubtotal() );?></b>
            </dd>
            <a href="<?php echo Hqw::getApplication()->createUrl('Checkout/index');?>" class="checkout"><dd class="cart_checkout cart_index"><?php echo CART_CHECKOUT;?></dd></a>
        </dl>
        <?php
        }
        ?>
        <div class="cartContent small_table">
            <h3><?php echo CART_SAFE_SECURE;?></h3>
            <p><img style="padding-left:20px;" src="/images/mcafee-safe.gif"><img style="padding-left:20px;" src="/images/norton-safe.png"></p>
        </div>
        <?php
            $this->contentWidget( "/layouts/cart/recent-history" );
        ?>
    </div>
    <div class="cl"></div>
</div>
<?php
$this->contentWidget( "/layouts/widget/message" );
?>
<script language="javascript" src="/js/jquery.isloading.js"></script>
