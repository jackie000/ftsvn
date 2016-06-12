<?php
$clsSeo = ClsFactory::instance("ClsSeo");
$base = $order->getBase();
$currency = $order->getCurrency();
$clsCommon = ClsFactory::instance( "ClsCommon" );
?>
<div id="category_top_image" class="cwidth">
    <img src="/images/category_top_image.jpg" width="980">
</div>

<div id="breadcrumb" class="cwidth">
    <ul>
        <li class="title"><?php echo "<a href=\"" . Hqw::getApplication()->getComponent("Request")->getHostInfo() . "\">" . ucwords( Hqw::getApplication()->getComponent("Request")->getHostInfo(true) ) . "</a>";?></li>
        <li class="space">></li>
        <li class="title"><a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/index');?>"><?php echo USER_MY_ACCOUNT;?></a></li>
        <li class="space">></li>
        <li class="cur title"><?php echo USER_ORDER_INFORMATION;?></li>
    </ul>
    <div class="cl"></div>
</div>

<div id="account" class="cwidth">
    <div class="order_list">
    <table width="100%">
        <tr>
            <td valign="top">
                <div><span class="table_div_title"><?php echo USER_ORDER_NO;?></span><b><?php echo $order->getNumber();?></b></div>
                <div><span class="table_div_title"><?php echo USER_ORDERS_DATE;?></span><?php echo date( "m / d / Y", strtotime( $base['purchased_date'] ) );?></div>
                <div><span class="table_div_title"><?php echo USER_ORDER_STATUS;?></span><b class="orgcolor"><?php echo $clsCommon->getOrderStatus( $base['orders_status'] );?></b></div>
                <div><span class="table_div_title"><?php echo CHECKOUT_CONFIRM_ORDER_PAYMENT_METHOD;?></span><?php echo $base['payment_method'];?></div>
                <?php
                $billState = isset( $base['billing_state'] ) && $base['billing_state'] != '' ? $base['billing_state'] . ", " : "";
        	    $billLine2 = isset( $base['billing_address_line'] )  && $base['billing_address_line'] != '' ? " ," . $base['billing_address_line'] : "";
                $billCompany = isset( $base['billing_company'] ) && $base['billing_company'] != '' ? $base['billing_company'] . " " : "";
                ?>
                <div><span class="table_div_title" style="vertical-align:top;"><?php echo CHECKOUT_BILLING_ADDRESS;?></span><span style="display:inline-block;padding-top:5px;">
                <b><?php echo $base['billing_name'];?></b>
                <br /><?php echo $billCompany . $base['billing_street_address'] . $billLine2;?>
                <br /><?php echo $base['billing_city'] . ", " . $base['billing_postcode'];?>
                <br /><?php echo $billState  . $base['billing_country'];?></span></div>
            </td>
            <td  valign="top" style="line-height:18px;">
                <div><span class="table_div_title"><?php echo CHECKOUT_CONFIRM_ORDER_SHIPPING_METHOD;?></span><?php echo $base['shipping_method'];?></div>
                <?php
                $shipState = isset( $base['shipping_state'] ) && $base['shipping_state'] != '' ? $base['shipping_state'] . ", " : "";
        	    $shipLine2 = isset( $base['shipping_address_line'] )  && $base['shipping_address_line'] != '' ? " ," . $base['shipping_address_line'] : "";
                $shipCompany = isset( $base['shipping_company'] ) && $base['shipping_company'] != '' ? $base['shipping_company'] . " " : "";
                ?>
                <div><span class="table_div_title" style="vertical-align:top;"><?php echo CHECKOUT_CONFIRM_ORDER_SHIPPING_ADDRESS;?></span>
                <span style="display:inline-block;padding-top:5px;"><b><?php echo $base['shipping_name'];?></b>
                <br /><?php echo $shipCompany . $base['shipping_street_address'] . $shipLine2;?>
                <br /><?php echo $base['shipping_city'] . ", " . $base['shipping_postcode'];?>
                <br /><?php echo $shipState  . $base['shipping_country'];?></span></div>
                <!--<div><span class="table_div_title"><?php echo USER_ORDER_PACKAGE_INFORMATION;?></span><b>11111813238790</b></div>-->
            </td>
        </tr>
    </table>
    <?php
    $tracking = Hqw::getApplication()->getModels( "orders_tracking" );
    $res = $tracking->fetchAll( array( 'orders_id'=>$base['orders_id'] ) );
    if( $res ){
    ?>
    <div class="recent_order"><?php echo USER_ORDER_TRACKING;?></div>
    <table width="100%">
        <tr class="th">
            <td width="200"><?php echo USER_ORDER_TRACKING_DATE_TIME;?></td>
            <td width="200"><?php echo USER_ORDER_TRACKING_PLACE;?></td>
            <td><?php echo USER_ORDER_TRACKING_HISTORY;?></td>
        </tr>
        <?php
        foreach( $res as $k => $v ) {
        ?>
        <tr>
            <td><?php echo date("M /d / Y H:i:s", strtotime($v['added_time']))?></td>
            <td><?php echo $v['place'];?></td>
            <td><?php echo $v['shipment_history'];?></td>
        </tr>
        <?php
        }
        ?>
    </table>
    <?php
    }
    ?>
    
    <?php
    $products = $order->getProducts();
    if( count( $products ) > 0 ) {
    ?>
    <div id="mycart" style="border:0px;">
        <div class="cartContent" style="line-height:25px;margin-top:25px;">
            <div class="div_left qty itemfont">&nbsp;</div>
            <div class="div_left price itemfont" style="text-align:left;"><?php echo PRODUCTS_SHOW_ITEM_CODE;?></div>
            <div class="div_left item itemfont"><?php echo CART_CART_LIST_ITEM;?></div>
            <div class="div_left qty itemfont"><?php echo CART_CART_LIST_QUANTITY;?></div>
            <div class="div_left price itemfont" style="width:210px;"><?php echo CART_CART_LIST_PRICE;?></div>
            <div class="cl"></div>
        </div>
        <?php
        foreach( $products as $key => $val ) {
            $link = $clsSeo->getProductsLink( $val );
        ?>
        <div class="cartContent border-top">
            <div class="div_left qty itemfont" style="line-height:30px;"> <a class="subt" href="<?php echo Hqw::getApplication()->createUrl('index/write_reviews',array('productsId'=>$val['products_id']));?>"><em><b><?php echo PRODUCTS_SHOW_WRITE_REVIEW;?></b></em></a>
            <br /><a class="subt" href="<?php echo $link['href'];?>"><em><b><?php echo USER_ORDERS_VIEW_DETAIL;?></b></em></a> </div>
            <div class="div_left price" style="text-align:left;"><a href="<?php echo $link['href'];?>"><?php echo $val['products_code'];?></a></div>
            <div class="div_left item">
                <div class="div_left" style="padding-right:5px;"><?php echo $clsSeo->getProductsSeoImages( $val['products_id'], 90 );?></div>
                <dl>
                    <dt style="word-wrap: break-word;word-break:normal;"><a href="<?php echo $link['href'];?>"><?php echo $link['name'];?></a><dt>
                    <dd style="height:5px;"></dd>
                    <?php
                    if( isset( $val['attributes'] ) && !empty( $val['attributes'] ) ) {
                    	foreach( $val['attributes'] as $atts ) {
                    		echo "<dd>{$atts['orders_products_options_name']} : {$atts['orders_products_options_values_name']} " . ( isset( $atts['desc'] ) ? $atts['desc'] : "" ) . " </dd>";
                    	}
                    }
                    ?>
                    <dd style="height:5px;"></dd>
                </dl>
            </div>
            
            <div class="div_left qty"> <?php echo $val['products_quantity'];?></div>
            
            <div class="div_left price" style="width:210px;">
                <dl>
                    <dt class="value"><?php echo $currency->getCurrency();?> <?php echo $currency->getCurrencySign();?><?php echo $currency->getCurrencyValues( $val['final_price'] );?></dt>
                </dl>
            </div>
            
            <div class="cl"></div>
        </div>
        <?php
        }
        ?>
        
        <div class="txt-right pr15 div-border-top pt8">
            <span class="order_comp_title"><?php echo CART_SUBTOTAL;?>:</span><span class="order_comp_value"><?php echo $currency->getCurrency();?> <?php echo $currency->getCurrencySign();?><?php echo $currency->getCurrencyValues( $base['orders_subtotal'] );?></span>
        </div>
        <div class="txt-right pr15 pt8">
            <span class="order_comp_title"><?php echo CHECKOUT_CONFIRM_ORDER_SHIPPING_FEE;?>:</span><span class="order_comp_value"><?php echo $currency->getCurrency();?> <?php echo $currency->getCurrencySign();?><?php echo $currency->getCurrencyValues( $base['shipping_total'] );?></span>
        </div>
        
        <div class="txt-right pr15 pt8">
            <span class="order_comp_title"><?php echo CHECKOUT_CONFIRM_ORDER_COUPON;?>:</span><span class="order_comp_value"><?php echo $currency->getCurrency();?> <?php echo $currency->getCurrencySign();?><?php echo $currency->getCurrencyValues( $base['coupon_total'] );?></span>
        </div>
        
        <div class="txt-right pr15 pt8">
            <span class="order_comp_title div-border-top pt8" style="font-weight:bold;width:150px;"><?php echo CART_TOTAL;?>:</span><span class="order_comp_value n_p div-border-top pt8"><?php echo $currency->getCurrency();?> <?php echo $currency->getCurrencySign();?><?php echo $currency->getCurrencyValues( $base['orders_total'] );?></span>
        </div>
    </div>
    <?php
    }
    ?>
    
    </div>
    <div class="cl"></div>
    <div style="line-height:35px;"><a class="subt" href="<?php echo Hqw::getApplication()->createUrl('UserActivities/index');?>"><em><b>Go Back</b></em></a></div>
</div>