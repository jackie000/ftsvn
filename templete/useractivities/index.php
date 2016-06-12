<div id="category_top_image" class="cwidth">
    <img src="/images/category_top_image.jpg" width="980">
</div>

<div id="breadcrumb" class="cwidth">
    <ul>
        <li class="title"><?php echo "<a href=\"" . Hqw::getApplication()->getComponent("Request")->getHostInfo() . "\">" . ucwords( Hqw::getApplication()->getComponent("Request")->getHostInfo(true) ) . "</a>";?></li>
        <li class="space">></li>
        <li class="cur title"><?php echo USER_MY_ACCOUNT;?></li>
    </ul>
    <div class="cl"></div>
</div>
<div id="account" class="cwidth">
    <div class="account_menu">
        <?php
        $this->contentWidget( "/layouts/ua/account_menu" );
        $signin = ClsFactory::instance("ClsSignin");
        $signin->checkCookieUser();
        $user = $signin->getCookieUser();
        ?>
        <div class="account_index div_left">
            <div class="welcome">
                <table width="100%">
                    <tr>
                        <td><span class="hi"><?php echo USER_HI;?><?php echo $user->getName();?></span><?php echo USER_WELCOME;?></td>
                        <td class="other"></td>
                    </tr>
                </table>
            </div>
            
            <?php
            $coupons = Hqw::getApplication()->getModels( "coupon" );
            $t = Hqw::getApplication()->getComponent("Date")->cDate( "s" );
            $coupons = $coupons->condition( "start_date >= '$t'" );
            $coupons = $coupons->condition( "end_date <= '$t'" );
            $res = $coupons->fetchAll();
            if( $res ){
            ?>
            <div class="recent_order">
                <?php echo USER_MY_COUPONS;?>
            </div>
            <div class="order_list">
                <table width="100%">
                    <tr class="th">
                        <td><?php echo USER_COUPON_CODE;?></td>
                        <td><?php echo USER_COUPON_SUMMARY;?></td>
                        <td><?php echo USER_COUPON_BEGIN_DATE;?></td>
                        <td><?php echo USER_COUPON_EXPIRY_DATE;?></td>
                        <td><?php echo USER_COUPON_STATUS;?></td>
                    </tr>
                    <?php
                    foreach( $res as $k => $v ) {
                    ?>
                    <tr class="cen">
                        <td><?php echo $v['coupon_code'];?></td>
                        <td><?php echo $v['coupon_name'];?></td>
                        <td><?php echo $v['start_date'];?></td>
                        <td><?php echo $v['end_date'];?></td>
                        <td>Available</td>
                    </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
            <?php
            }
            $orders = Hqw::getApplication()->getModels( "orders" );
            $orders = $orders->where( array( "user_id"=>$user->getUserId() ) );
        	$orders = $orders->order("create_orders_date DESC");
        	$orders = $orders->limit( 3 );
        	$recentOrders = $orders->fetchAll();
        	
        	$clsSeo = ClsFactory::instance("ClsSeo");
        	$clsCommon = ClsFactory::instance( "ClsCommon" );
        	if( $recentOrders ) {
        	?>
        	
            <div class="recent_order">
                <?php echo USER_RECENT_ORDERS;?>
            </div>
            <div class="order_list">
                <table width="100%">
                    <?php
                    foreach( $recentOrders as $k => $v ) {
                        $clsOrders = ClsOrdersFactory::instance( (int)$v['orders_id'] );
                        $clsOrders->setBase( $v );
                        $cr = $clsOrders->getCurrency();
                        
                        $ck = new ClsCheckout();
                        $ck->setShippingMethod( $v['shipping_method'] );
                    ?>
                    <tr>
                        <td class="base">
                            <dl>
                                <dd><?php echo USER_ORDERS_DATE;?></dd>
                                <dd class="date"><b><?php echo date( "m / d / Y", strtotime( $v['purchased_date'] ) );?></b></dd>
                                <dd class="view"><a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/order',array('no'=>$v['orders_number']));?>"><?php echo USER_ORDERS_VIEW_DETAIL;?></a></dd>
                                <dd class="order_number"><a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/order',array('no'=>$v['orders_number']));?>"><?php echo $v['orders_number'];?></a></dd>
                                <dd><?php echo USER_ORDERS_RECIPIENTS;?> <?php echo $v['shipping_name'];?></dd>
                                <dd class="total"><?php echo USER_ORDERS_TOTAL?> <span class="save"><?php echo $cr->getCurrency();?> <?php echo $cr->getCurrencySign();?><?php echo $cr->getCurrencyValues( $v['orders_total'] );?></span></dd>
                            </dl>
                        </td>

                        <td class="product">
                            <div class="function div_right">
                                <dl>
                                    <dd class="order_status"> <?php echo $clsCommon->getOrderStatus( $v['orders_status'] );?> </dd>
                                    <dd><?php echo USER_ORDERS_ESTIMATED_TIME;?><br /><b><?php echo $ck->getEstimatedTime( strtotime( $v['purchased_date'] ) );?></b></dd>
                                    <dd class="h20"></dd>
                                    <dd class="h30"><a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/order',array('no'=>$v['orders_number']));?>"><input type="button" class="addtocart" value="<?php echo USER_ORDERS_TRACKING;?>"></a></dd>
                                    <dd class="h30"><a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/return_item',array('no'=>$v['orders_number']));?>" class="subt"><em><?php echo USER_ORDERS_RETURN_EXCHANGE;?></em></a></dd>
                                </dl>
                            </div>
                            
                            <?php
                            $products = $clsOrders->getProducts();
                            foreach( $products as $key => $val ) {
                                $link = $clsSeo->getProductsLink( $val );
                            ?>
                            <div class="p_list">
                                <?php echo $clsSeo->getProductsSeoImages( $val['products_id'], 90 );?>
                                <div><a href="<?php echo $link['href'];?>"><?php echo $link['name'];?></a><br /><br />
                                <?php
                                if( isset( $val['attributes'] ) && !empty( $val['attributes'] ) ) {
                                	foreach( $val['attributes'] as $atts ) {
                                		echo "{$atts['orders_products_options_name']} : {$atts['orders_products_options_values_name']} " . ( isset( $atts['desc'] ) ? $atts['desc'] : "" ) . " <br />";
                                	}
                                }
                                ?>
                                </div>
                                
                            </div>
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
            <?php	
        	}
            ?>
            
            <div class="recommend">
                <table>
                    <tr><td colspan="5" class="title">Today's Recommendation For You</td></tr>
                    <tr class="p">
                        <td><div class="other"><img src="/images/p_l1.jpg" height="90"></div>
                        <div class="dec">A-Line Sweetheart Short Satin & Chiffon Informal Wedding Dress</div></td>
    
                        <td><div class="other"><img src="/images/p_l1.jpg" height="90"></div>
                        <div class="dec">A-Line Sweetheart Short Satin & Chiffon Informal Wedding Dress</div></td>
    
                        <td><div class="other"><img src="/images/p_l1.jpg" height="90"></div>
                        <div class="dec">
                            <dl>
                                <dt>A-Line Sweetheart Short Satin & Chiffon Informal Wedding Dress</dt>
                            </dl>
                        </div></td>  
    
                        <td><div class="other"><img src="/images/p_l1.jpg" height="90"></div>
                        <div class="dec">
                            <dl>
                                <dt>A-Line Sweetheart Short Satin & Chiffon Informal Wedding Dress</dt>
                            </dl>
                        </div></td> 
                        
                        <td><div class="other"><img src="/images/p_l1.jpg" height="90"></div>
                        <div class="dec">
                            <dl>
                                <dt>A-Line Sweetheart Short Satin & Chiffon Informal Wedding Dress</dt>
                            </dl>
                        </div></td>
                        
                        <td><div class="other"><img src="/images/p_l1.jpg" height="90"></div>
                        <div class="dec">
                            <dl>
                                <dt>A-Line Sweetheart Short Satin & Chiffon Informal Wedding Dress</dt>
                            </dl>
                        </div></td>  
                    </tr>
                </table>
            </div>
            
            
        </div>
    </div>
    <div class="cl"></div>
</div>