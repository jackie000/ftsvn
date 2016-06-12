<div id="category_top_image" class="cwidth">
    <img src="/images/category_top_image.jpg" width="980">
</div>

<div id="breadcrumb" class="cwidth">
    <ul>
        <li class="title"><?php echo "<a href=\"" . Hqw::getApplication()->getComponent("Request")->getHostInfo() . "\">" . ucwords( Hqw::getApplication()->getComponent("Request")->getHostInfo(true) ) . "</a>";?></li>
        <li class="space">></li>
        <li class="title"><a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/index');?>"><?php echo USER_MY_ACCOUNT;?></a></li>
        <li class="space">></li>
        <li class="cur title"><?php echo USER_MY_ORDERS;?></li>

    </ul>
    <div class="cl"></div>
</div>

<div id="account" class="cwidth">
    <div class="account_menu">
        <?php
        $this->contentWidget( "/layouts/ua/account_menu" );
        ?>
        
        <div class="account_index div_left">
            <div class="search_order">
                <form method="POST" action="">
                <select name="node">
                    <?php
                    if( isset( $_POST['node'] ) && $_POST['node'] == "6" ) {
                    ?>
                    <option value="1"><?php echo USER_RECENT_ORDERS;?></option>
                    <option value="6" selected="selected"><?php echo USER_ORDER_PAST_SIX_MONTHS;?></option>
                    <?php
                    }else{
                    ?>
                    <option value="1"><?php echo USER_RECENT_ORDERS;?></option>
                    <option value="6"><?php echo USER_ORDER_PAST_SIX_MONTHS;?></option>
                    <?php
                    }
                    ?>
                </select>
                <input type="text" name="no" value="<?php echo $_POST['no'];?>">
                <input type="submit" value="Search" class="search_button">
                </form>
            </div>
            <div class="order_list">
                <?php
                $signin = ClsFactory::instance("ClsSignin");
                $signin->checkUser();
                $user = $signin->getUser();
                
                $orders = Hqw::getApplication()->getModels( "orders" );
                $orders = $orders->where( array( "user_id"=>$user->getUserId() ) );
            	$orders = $orders->order("create_orders_date DESC");
            	
            	$t = Hqw::getApplication()->getComponent("Date")->cTime();
            	$t = date( 'Y-m-d', strtotime( "-6 month", $t ) );
            	
            	if( isset( $_POST['node'] ) && $_POST['node'] == "6" ) {
            		$orders = $orders->condition( "purchased_date <= '$t'" );
            	}else{
            	    $orders = $orders->condition( "purchased_date >= '$t'" );
            	}
            	
            	if( isset( $_POST['no'] ) && $_POST['no'] != "" ) {
            		$recentOrders = $orders->fetchAll( array( 'orders_number'=>$_POST['no'] ) );
            	}else{
            	    $recentOrders = $orders->fetchAll();
            	}
            	
            	
            	$clsSeo = ClsFactory::instance("ClsSeo");
            	$clsCommon = ClsFactory::instance( "ClsCommon" );
            	if( $recentOrders ) {
                ?>
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
                <?php
            	}else{
            	?>
            	
            	<table width="100%">
                    <tr>
                        <td align="center"> <?php echo USER_ORDER_NO_RESULTS;?> </td>
                    </tr>
                </table>
            	<?php
            	}
                ?>
            </div>
        </div>
        
    </div>
    <div class="cl"></div>
</div>