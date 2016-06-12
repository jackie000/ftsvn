<div id="category_top_image" class="cwidth">
    <img src="/images/category_top_image.jpg" width="980">
</div>
<div id="breadcrumb" class="cwidth">
    <ul>
        <li class="title"><?php echo "<a href=\"" . Hqw::getApplication()->getComponent("Request")->getHostInfo() . "\">" . ucwords( Hqw::getApplication()->getComponent("Request")->getHostInfo(true) ) . "</a>";?></li>
        <li class="space">></li>
        <li class="title"><a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/index');?>"><?php echo USER_MY_ACCOUNT;?></a></li>
        <li class="space">></li>
        <li class="cur title"><?php echo USER_ORDER_RETURN_ITEM;?></li>
    </ul>
    <div class="cl"></div>
</div>

<div id="account" class="cwidth">
    <div class="order_list">
    <table width="100%">
        <tr>
            <td> <?php echo USER_ORDER_RETURN_ITEM_TIPS;?></td>
            <td>
                <dl>
                    <dd><?php echo USER_ORDER_NO;?>: <a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/order',array('no'=>$order->getNumber()));?>" class="imp"><?php echo $order->getNumber();?></a></dd>
                    <dd><?php echo USER_ORDERS_DATE;?>: <?php echo date( "m / d / Y", strtotime( $base['purchased_date'] ) );?></dd>
                    <dd style="margin-top:5px;line-height:25px;height:25px;"><a class="subt" href="<?php echo Hqw::getApplication()->createUrl('UserActivities/order',array('no'=>$order->getNumber()));?>"><em><?php echo USER_ORDERS_VIEW_DETAIL;?></em></a></dd>
                </dl>
            </td>
        </tr>
    </table>
    <div class="recent_order">
        <?php echo USER_ORDER_RETURN_ITEM;?>
    </div>
    <?php 
    $clsSeo = ClsFactory::instance("ClsSeo");
    $products = $order->getProducts();
    if( count( $products ) > 0 ) {
    ?>
    <table width="100%">
        <?php
        foreach( $products as $key => $val ) {
            $link = $clsSeo->getProductsLink( $val );
        ?>
        <tr>
            <td>
            <input name="orders_products_id" value="<?php echo $key;?>" type="checkbox" style="float:right;margin-top:4%;">
            <div class="p_list">
                <?php echo $clsSeo->getProductsSeoImages( $val['products_id'], 90 );?>
                <div><a href="<?php echo $link['href'];?>"><?php echo $link['name'];?></a><br />
                <?php
                if( isset( $val['attributes'] ) && !empty( $val['attributes'] ) ) {
                	foreach( $val['attributes'] as $atts ) {
                		echo "{$atts['orders_products_options_name']} : {$atts['orders_products_options_values_name']} " . ( isset( $atts['desc'] ) ? $atts['desc'] : "" ) . " <br />";
                	}
                }
                ?>
                </div>
            </div>
            </td>
        </tr>
        <?php
        }
        ?>
    </table>
    <?php
    }
    ?>
    </div>
    <input type="hidden" name="orders_id" value="<?php echo $base['orders_id'];?>">
    <table style="margin-top:10px;">
        <tr>
            <td class="item"><b class="lightf"><?php echo USER_ORDER_RETURN_DISPOSAL_METHOD;?>:</b></td>
            <td class="method"><label><input type="radio" name="method">Exchange</label><label><input type="radio" name="method">Returns</label></td>
        </tr>
        <tr>
            <td class="item" valign="top" align="right"><b class="lightf"><?php echo USER_ORDER_RETURN_DESC;?>:</b></td>
            <td class="input" style="padding-top:3px;"><textarea class="txtaa"></textarea><br/>Enter More Than 500 Words.</td>
        </tr>
        <tr>
            <td colspan="2">
                <b class="lightf">In order to help us better problem-solving,Please</b> <input type="file" value="Browse" name="Upload">
            </td>
        </tr>
    </table>
    <div class="right_button">
        <input type="button" value="Submit" class="search_button">
    </div>

    <div class="cl"></div>
</div>