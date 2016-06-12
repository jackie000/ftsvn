<?php
$clsCommon = ClsFactory::instance( "ClsCommon" );
$clsPm = ClsFactory::instance( "ClsProductsMethod" );
$sc = ClsFactory::instance("ClsShoppingCart");
$currency = ClsFactory::instance( "ClsCurrency" );

$checkout = ClsCheckout::getCheckout();
?>
<div id="checkout_step">
    <div id="checkout_header" class="div_left">
        <img src="/images/logo.png" width="213">
    </div>
    <div class="step_one div_right">
        <div class="header_three"></div>
        <div class="header_title" >
            <ul>
                <li class="one_title beenset"><?php echo strtoupper( USER_SIGNIN );?></li>
                <li class="two_title beenset"><?php echo strtoupper( CHECKOUT_SHIPPING_PAYMENT );?></li>
                <li class="three_title current"><?php echo strtoupper( CHECKOUT_PLACE_ORDER );?></li>
            </ul>
        </div>
    </div>    
    <div class="cl"></div>
    <div class="bottom_line"></div>
    <div class="sign_in order_area_bg">
        <table width="100%">
            <tr>
                <td colspan="4" class="title"><h3 style="color:#333;"><?php echo CHECKOUT_CONFIRM_ORDER;?></h3></td>
            </tr>
            <tr>
                <td colspan="4" class="pb10">
                
                <h3 class="dec"><?php echo CHECKOUT_CONFIRM_ORDER_COUPON_NOTE;?></h3>
                <h3 class="dec"><?php echo CHECKOUT_CONFIRM_ORDER_DESC;?></h3>
                </td>
            </tr>
        </table>
        
        <div class="area">
        <table width="100%">
            <tr class="bold_imp">
                <td><b><?php echo CHECKOUT_CONFIRM_ORDER_SHIPPING_ADDRESS;?></b></td>
                <td></td>
                <td width="280"><b><?php echo CHECKOUT_CONFIRM_ORDER_COUPON;?></b></td>
                <td width="264" rowspan="3">
                    <div class="cart-standard-right checkout_area" style="position:relative;left:0px;top:-8px;padding-top:10px;">
                        <form  id="frm" method="POST" action="<?php echo Hqw::getApplication()->createUrl('checkout/handle_orders', array( 'type'=>'process','action'=>'update' ) );?>">
                        <h3><input type="submit" name="submit" value="<?php echo CHECKOUT_CONFIRM_ORDER;?>" class="cart_checkout imp_price"></h3>
                        </form>
                        <h3 class="lightf" style="margin-top:10px;">
                            <div class="div_left item"><?php echo CART_SUBTOTAL;?>: </div>
                            <div class="div_left txt-right"><?php echo $currency->getCurrency();?> <?php echo $currency->getCurrencySign();?><?php echo $currency->getCurrencyValues( $sc->getCheckoutSubtotal() );?></div>
                        </h3>
                        <?php
                        //if( $checkout->getShippingFee() !== false ) {
                        ?>
                        <h3 class="lightf">
                            <div class="div_left item"><?php echo CHECKOUT_CONFIRM_ORDER_SHIPPING_FEE;?>: </div>
                            <div class="div_left txt-right"><?php echo $currency->getCurrency();?> <?php echo $currency->getCurrencySign();?><?php echo $currency->getCurrencyValues( $checkout->getShippingFee() );?></div>
                        </h3>
                        <?php
                        //}
                        ?>
                        <?php
                        //if( $checkout->getCouponValue() !== false ){
                        ?>
                        <h3 class="lightf">
                            <div class="div_left item"><?php echo CHECKOUT_CONFIRM_ORDER_COUPON;?>: </div>
                            <div class="div_left txt-right"> - <?php echo $currency->getCurrency();?> <?php echo $currency->getCurrencySign();?><?php echo $currency->getCurrencyValues( $checkout->getCouponValue() );?></div>
                        </h3>
                        <?php
                        //}
                        ?>
                        <h3 class="border-top">
                            <div class="txtcenter"><span class="imp_price"><?php echo CART_TOTAL;?>:</span> <span class="n_p"><?php echo $currency->getCurrency();?> <?php echo $currency->getCurrencySign();?><?php echo $currency->getCurrencyValues( $checkout->getCheckoutTotal() );?></span> </div>
                        </h3>
                        <div class="cl"></div>
                    </div>
                    <div class="area" style="margin-top:10px;border-color:#B6CCDA;">
                        <h5 class="lightf"><b>Sales Promotion Detail:</b></h5>
                        <h3 class="lightf" style="padding-left:10px;margin-top:3px;">&bull; <span>Free Shipping Over $99</span></h3>
                    </div>
                </td>
                
            </tr>

            <tr>
                <td class="address"><h5 class="lightf">
                    <?php
                    $country = $clsCommon->getCountries( $shippingAddress['country_id'] );
            	    $state = isset( $shippingAddress['state'] ) && $shippingAddress['state'] != '' ? $shippingAddress['state'] . ", " : $clsCommon->getZones( $shippingAddress['state_id'] ) . ", ";
            	    $line2 = isset( $shippingAddress['address_line'] )  && $shippingAddress['address_line'] != '' ? " ," . $shippingAddress['address_line'] : "";
                    $company = isset( $shippingAddress['company'] ) && $shippingAddress['company'] != '' ? $shippingAddress['company'] . " " : "";
                    ?>
                    <div class="address_list showtext">
                        <dl>
                            <dd><?php echo $shippingAddress['full_name'];?></dd>
                            <dd><?php echo $company . $shippingAddress['street_address'] . $line2;?></dd>
                            <dd><?php echo $shippingAddress['city'] . ", " . $shippingAddress['postcode'];?></dd>
                            <dd><?php echo $state . $country . "<br />" . $shippingAddress['phone_number'];?><a href="<?php echo Hqw::getApplication()->createUrl('checkout/shipping_address');?>" class="blueimp"> <?php echo CHECKOUT_CONFIRM_ORDER_EDIT;?> </a></dd>
                        </dl>
                    </div>
                    </h5>
                    <b class="bold_imp"><?php echo CHECKOUT_BILLING_ADDRESS;?></b>
                    <h5 class="lightf">
                    <?php
                    $billCountry = $clsCommon->getCountries( $billingAddress['country_id'] );
            	    $billState = isset( $billingAddress['state'] ) && $billingAddress['state'] != '' ? $billingAddress['state'] . ", " : $clsCommon->getZones( $billingAddress['state_id'] ) . ", ";
            	    $billLine2 = isset( $billingAddress['address_line'] )  && $billingAddress['address_line'] != '' ? " ," . $billingAddress['address_line'] : "";
                    $billCompany = isset( $billingAddress['company'] ) && $billingAddress['company'] != '' ? $billingAddress['company'] . " " : "";
                    ?>
                    <div class="address_list showtext">
                        <dl>
                            <dd><?php echo $billingAddress['full_name'];?></dd>
                            <dd><?php echo $billCompany . $billingAddress['street_address'] . $billLine2;?></dd>
                            <dd><?php echo $billingAddress['city'] . $billingAddress['postcode'];?></dd>
                            <dd><?php echo $billState . ", " . $billCountry;?> <a href="<?php echo Hqw::getApplication()->createUrl('checkout/payment_address');?>" class="blueimp"> <?php echo CHECKOUT_CONFIRM_ORDER_EDIT;?> </a></dd>
                        </dl>
                    </div>
                    </h5>
                </td>

                <td colspan="2">
                    <script language="javascript">
                        function chkCoupon(o){
                            var cc = $('input[name="coupon_code"]').val();
                            if( cc == null ){
                                alert("<?php echo CHECKOUT_CONFIRM_ORDER_COUPON_VALID;?>");
                                return false;
                            }
                        }
                    </script>
                    <form  id="frm" method="POST" onsubmit="return chkCoupon(this)" action="<?php echo Hqw::getApplication()->createUrl('checkout/confirmation', array( 'type'=>'process','action'=>'update' ) );?>">
                    <p><input type="text" name="coupon_code" style="height:16px;line-height:16px;padding:1px 2px;"><input type="submit" style="margin-left:2px;" class="search_button" value="<?php echo CHECKOUT_CONFIRM_ORDER_COUPON_APPLY;?>"></p>
                    </form>
                </td>
            </tr>

            <tr>
                <td colspan="2" class="order_list">
                    <div style="margin-top:20px;">
                        <h3 class="orgcolor showtext"><?php echo CHECKOUT_CONFIRM_ORDER_ESTIMATED_TIME;?> <br /><b><?php echo $checkout->getEstimatedTime();?></b></h3>
                    </div>
                    <?php
                    foreach( $items as $k => $v ) {
                        $itemProducts = $v->getProducts();
                        $productsBase = $itemProducts->getBase();
                        $productsLinks = ClsFactory::instance( "ClsSeo" )->getProductsLink( $productsBase );
                        
                        $attributes = $v->getProductsOptionsToValues();
                        $salesPrice = $currency->getCurrencyValues( $v->getProductsSales() );
                        $savePrice = $currency->getCurrencyValues( $itemProducts->getSavePrice() );
                        $savePercent = $itemProducts->getSavePercent();
                    ?>
                    <div class="p_list" style="margin-top:10px;">
                        <img title='<?php echo $productsLinks['title'];?>' alt='<?php echo $productsLinks['alt'];?>' src="<?php echo $clsPm->thumbnailImage( $productsBase['products_images'], 90 );?>" width="90">
                        <div style="height:auto;">
                            <h5 class="lightf"><?php echo $productsBase['products_name'];?></h5>
                            <h5 style="height:3px;"></h5>
                            <h5 class="save"><?php echo $currency->getCurrency();?> <?php echo $currency->getCurrencySign();?><?php echo $salesPrice;?></h5>
                            <?php
                            if( $attributes ){
                                foreach( $attributes as $m => $n ) {
                                	echo "<h6 class=\"lightf\">{$n['products_options_name']}: <b>{$n['products_options_values_name']}". ( isset( $n['desc'] ) ? $n['desc'] : "" ) ." </b></h6>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <br class="cl" />
                    <?php	
                    }
                    ?>
                </td>
                <td>
                    <div style="margin-top:20px;" class="bold_imp">
                        <h3 class="lightf"><b><?php echo CHECKOUT_CONFIRM_ORDER_SHIPPING_METHOD;?></b></h3>
                        <?php
                        $ce = ClsFactory::instance("ClsExpress");
                        $methods = $ce->getShippingMethod();
                        if( count($methods) > 0 ) {
                            foreach( $methods as $k => $v ) {
                                if( $v->getTitle() == $shippingMethod ){
                                    echo "<h3 class=\"lightf\">" . str_replace( '{day}', $v->getDeliveryDay(), CHECKOUT_SHIPPING_METHOD_WORKING ) . "</h3>";
                                    break;
                                }
                            }
                        }
                        ?>
                        <h3><a href="<?php echo Hqw::getApplication()->createUrl('checkout/shipping_method');?>" class="blueimp"> <?php echo CHECKOUT_CONFIRM_ORDER_EDIT;?> </a></h3>
                        <h3 style="margin-top:15px;" class="lightf"><b><?php echo CHECKOUT_CONFIRM_ORDER_PAYMENT_METHOD;?></b></h3>
                        <?php
                        $clsPayments = ClsFactory::instance( "ClsPayment" );
                        $payments = $clsPayments->getPaymentMethod();
                        if( count($payments) > 0 ) {
                            foreach( $payments as $k => $v ) {
                                if( $v->getTitle() == $paymentMethod ) {
                                	echo "<h3 class=\"lightf\">" . $v->getTitle() . "</h3>";
                                	if( $paymentMethod == MODULE_PAYMENT_WESTERNUNION ) {
                                	    echo "<div style=\"position:relative;\">";
                                		echo "<div style=\"position:absolute;width:500px;z-index:200;top:35px;\"><h6 class=\"lightf\">" . MODULE_PAYMENT_WESTERNUNION_PAYABLE . "</h6>";
                                		echo "<h6 class=\"lightf\" style=\"margin-top:5px;\">First Name: <b>" . $v->getValue( "MODULE_PAYMENT_WESTERNUNION_FIRST_NAME" ) . "</b></h6>";
                                		echo "<h6 class=\"lightf\">Last Name: <b>" . $v->getValue( "MODULE_PAYMENT_WESTERNUNION_LAST_NAME" ) . "</b></h6>";
                                		echo "<h6 class=\"lightf\">Address: <b>" . $v->getValue( "MODULE_PAYMENT_WESTERNUNION_ADDRESS" ) . "</b></h6>";
                                		echo "<h6 class=\"lightf\">Zip Code: <b>" . $v->getValue( "MODULE_PAYMENT_WESTERNUNION_ZIP_CODE" ) . "</b></h6>";
                                		echo "<h6 class=\"lightf\">City: <b>" . $v->getValue( "MODULE_PAYMENT_WESTERNUNION_CITY" ) . "</b></h6>";
                                		echo "<h6 class=\"lightf\">Country: <b>" . $v->getValue( "MODULE_PAYMENT_WESTERNUNION_COUNTRY" ) . "</b></h6>";
                                		echo "<h6 class=\"lightf\">Phone: <b>" . $v->getValue( "MODULE_PAYMENT_WESTERNUNION_PHONE" ) . "</b></h6>";
                                		echo "<h6 class=\"lightf\" style=\"color:red;margin-top:5px;\">" . MODULE_PAYMENT_WESTERNUNION_NOTICE . "</h6></div>";
                                		echo "</div>";
                                	}
                                	break;
                                }
                            }
                        }
                        ?>
                        <h3><a href="<?php echo Hqw::getApplication()->createUrl('checkout/payment');?>" class="blueimp"> <?php echo CHECKOUT_CONFIRM_ORDER_EDIT;?> </a></h3>
                    </div>
                </td>
            </tr>
        </table>
        </div>

    </div>
</div>
