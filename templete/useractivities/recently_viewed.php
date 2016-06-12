<div id="category_top_image" class="cwidth">
    <img src="/images/category_top_image.jpg" width="980">
</div>
<?php
$currency = ClsFactory::instance( "ClsCurrency" );
$clsSeo = ClsFactory::instance("ClsSeo");
$clsPm = ClsFactory::instance( "ClsProductsMethod" );
?>
<div id="breadcrumb" class="cwidth">
    <ul>
        <li class="title"><?php echo "<a href=\"" . Hqw::getApplication()->getComponent("Request")->getHostInfo() . "\">" . ucwords( Hqw::getApplication()->getComponent("Request")->getHostInfo(true) ) . "</a>";?></li>
        <li class="space">></li>
        <li class="title"><a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/index');?>"><?php echo USER_MY_ACCOUNT;?></a></li>
        <li class="space">></li>
        <li class="cur title"><?php echo USER_RECENTLY_VIEWED;?></li>

    </ul>
    <div class="cl"></div>
</div>

<div id="account" class="cwidth">
    <div class="account_menu">
        <?php
        $this->contentWidget( "/layouts/ua/account_menu" );
        ?>
        
        <div class="account_index div_left">
            <div class="order_list" style="border-top:solid 1px #EDD28B;">
                <?php
                if( $history ) {
                    //$history = array_merge($history,$history,$history);
                    foreach ($history as $k => $v) {
                        $pts = ClsProductsFactory::instance( $v );
                        $base = $pts->getBase();
                	    
                	    $salesPrice = $currency->getCurrencyValues( $pts->getSalesPrice() );
                        $marketPrice = $currency->getCurrencyValues( $pts->getMarketPrice() );
                        $savePrice = $currency->getCurrencyValues( $pts->getSavePrice() );
                        $savePercent = $pts->getSavePercent();
                	    $link = $clsSeo->getProductsLink( $base );
                ?>
                	<div class="middle_product">
                        <div class="also_bought">
                        <?php echo $clsSeo->getProductsSeoImages( $base['products_id'], 150 );?>
                        </div>
                        <div class="also_bought_dec">
                            <dl>
                                <dt><?php echo $clsSeo->getProductsSeoLink( $v['products_id'] );?></dt>
                                <dd>
                                <span class="save" style="display:inline-block;width:100%"><?php echo $currency->getCurrency();?> <?php echo $currency->getCurrencySign();?><?php echo $salesPrice;?>
                                    <span style="<?php echo (!$pts->getProductsReviewsCount()) ? "margin-top:5px;" : "";?>line-height:16px;display:inline-block;margin-left:10px;color:#666;" class="sw s_star_<?php echo $pts->getProductsRating();?>"><span></span><?php echo $pts->getProductsReviewsCount();?></span>
                                </span>
                                </dd>
                            </dl>
                        </div>
                    </div>
                <?php
                        if( $k % 4 == 3 ) {
                        	echo "<div class=\"cl\"></div>";
                        }
                    }
                
                }else{
                ?>
                <table width="100%">
                    <tr>
                        <td align="center"> <?php echo USER_DONOT_HISTORY;?> </td>
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