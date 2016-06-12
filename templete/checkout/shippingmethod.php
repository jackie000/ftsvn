<?php
$sc = ClsFactory::instance("ClsShoppingCart");
$clsCommon = ClsFactory::instance( "ClsCommon" );

$ce = ClsFactory::instance("ClsExpress");
$currency = ClsFactory::instance( "ClsCurrency" );
?>
<div id="checkout_step">
    <div id="checkout_header" class="div_left">
        <img src="/images/logo.png" width="213">
    </div>
    <div class="step_one div_right">
        <div class="header_two"></div>
        <div class="header_title">
            <ul>
                <li class="one_title beenset"><?php echo strtoupper( USER_SIGNIN );?></li>
                <li class="two_title current"><?php echo strtoupper( CHECKOUT_SHIPPING_PAYMENT );?></li>
                <li class="three_title"><?php echo strtoupper( CHECKOUT_PLACE_ORDER );?></li>
            </ul>
        </div>
    </div>    
    <div class="cl"></div>
    <div class="bottom_line"></div>
    <div class="sign_in">
        <?php
        if( $errorTips != "" && !empty( $errorTips ) ){
            $this->contentWidget( "/layouts/ua/tips", array( 'type'=>'_tips_error', 'msg'=>$errorTips ) );
        }
        ?>
        <div>
        <table width="100%">
            <tr>
                <td colspan="3" class="title"><h3 style="color:#333;"><?php echo CHECKOUT_SELECT_SHIPPING_METHOD;?></h3></td>
            </tr>
            <tr>
                <td colspan="3" class="pb10">
                <h3 class="dec"><?php echo CHECKOUT_SHIPPING_FEE;?></h3>
                <h3 class="dec"><?php echo CHECKOUT_ITEMS_WEIGHT;?> <b><?php echo $sc->getCheckoutWeight();?></b> <?php echo $clsCommon->getWeightUnit();?></h3>

                </td>
            </tr>
            
        </table>
        
        <?php
        $methods = $ce->getShippingMethod();
        if( count($methods) > 0 ) {
        ?>
        <table>
            <?php
            foreach( $methods as $k => $v ) {
                
                if( $v->isEnabled() === false ) {
                	continue;
                }
                
            ?>
            <form id="frm" method="POST" action="<?php echo Hqw::getApplication()->createUrl('checkout/shipping_method', array( 'type'=>'process','action'=>'update' ) );?>">
            <input type="hidden" name="key" value="<?php echo $v->getTitle();?>">
            <tr class="td_top_border">
                <td width="150"><h2><b><?php echo $v->getTitle();?></b></h2></td>
                <td style="padding:10px;">
                    <h5 class="lightf"><?php echo str_replace( '{day}', $v->getDeliveryDay(), CHECKOUT_SHIPPING_METHOD_WORKING );?></h5>
                    <h6><?php echo $v->getDescription();?></h6>
                </td>
                <td width="100">
                    <h5 class="lightf"><?php echo CHECKOUT_SHIPPING_COST;?></h5>
                    <h4 class="save"><?php echo $currency->getCurrency();?> <?php echo $currency->getCurrencySign();?><?php echo $currency->getCurrencyValues( $v->getShippingCost( $sc->getCheckoutWeight() ) );?></h4>
                </td>
                <td>
                    <input type="submit" value="<?php echo CHECKOUT_CONTINUE;?>" class="ship_address" >
                </td>
            </tr>
            </form>
            <?php
            }
            ?>
        </table>
        <?php
        }
        ?>
        </div>

    </div>
</div>
