<?php
$clsCommon = ClsFactory::instance( "ClsCommon" );

$clsPayments = ClsFactory::instance( "ClsPayment" );
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
        <script language="javascript">
            function chkPayment(o){
                var p = $('input:radio[name="payment"]:checked').val();
                if( p == null ){
                    alert("<?php echo CHECKOUT_PAYMENT_METHOD_NOTICE;?>");
                    return false;
                }
            }
        </script>
        <table width="100%">
            <tr>
                <td colspan="3" class="title"><h3 style="color:#333;"><?php echo CHECKOUT_BILLING_ADDRESS;?></h3></td>
            </tr>
            <tr>
                <td colspan="3">
                    <div class="address_list showtext">
                        <?php
                        $country = $clsCommon->getCountries( $addressBooks['country_id'] );
                	    $state = isset( $addressBooks['state'] ) && $addressBooks['state'] != '' ? $addressBooks['state']  . ", ": $clsCommon->getZones( $addressBooks['state_id'] ) . ", ";
                	    $line2 = isset( $addressBooks['address_line'] )  && $addressBooks['address_line'] != '' ? " ," . $addressBooks['address_line'] : "";
                        $company = isset( $addressBooks['company'] ) && $addressBooks['company'] != '' ? $addressBooks['company'] . " " : "";
                        ?>
                        <dl>
                            <dt><form id="frm_ship_address" method="POST" action="<?php echo Hqw::getApplication()->createUrl('checkout/payment_address');?>"><input type="hidden" name="address_book_id" value="<?php echo (int)$addressBooks['user_address_book_id'];?>"><input type="submit" value="<?php echo CHECKOUT_CHANGE_ADDRESS;?>" class="ship_address"></form></dt>
                            <dd><b><?php echo $addressBooks['full_name'];?></b> </dd>
                            <dd><?php echo $company . $addressBooks['street_address'] . $line2;?></dd>
                            <dd><?php echo $addressBooks['city'] . ", " . $addressBooks['postcode'];?></dd>
                            <dd><?php echo $state . $country;?></dd>
                        </dl>
                    </div>
                </td>
            </tr>
            <form id="frm" method="POST" onsubmit="return chkPayment(this)" action="<?php echo Hqw::getApplication()->createUrl('checkout/payment', array( 'type'=>'process','action'=>'update' ) );?>">
            <tr>
                <td colspan="3" class="pb10">
                <h3 class="dec"><?php echo CHECKOUT_BILLING_ADDRESS_NOTE;?></h3>
                </td>
            </tr>
            
            <tr>
                <td colspan="3" class="title"><h3 style="color:#333;"><?php echo CHECKOUT_SELECT_PAYMENT;?></h3></td>
            </tr>
            <tr>
                <td colspan="3" class="pb10">
                <h3 class="dec"><?php echo CHECKOUT_SELECT_PAYMENT_DESC;?></h3>
                </td>
            </tr>
            
            <tr>
                <td colspan="3" style="padding:0px 0px 0px 15px;">
                    <?php
                    $payments = $clsPayments->getPaymentMethod();
                    if( count($payments) > 0 ) {
                    
                        foreach( $payments as $k => $v ) {
                            if( $v->isEnabled() ) {
                    ?>
                    <h2 class="payment_method">
                        <p><label style="width:auto;"><input type="radio" name="payment" value="<?php echo $v->getTitle();?>" <?php echo $paymentMethod == $v->getTitle() ? "checked='checked'" : ""; ?>><b><?php echo $v->getTitle();?></b></label></p>
                        <?php echo $v->getDescription();?>
                    </h2>
                    <?php
                            }
                        }
                    }
                    ?>
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    <input type="submit" name="submit" value="<?php echo CHECKOUT_CONTINUE;?>" class="ship_address">
                </td>
            </tr>
            </form>
        </table>
        </div>

    </div>
</div>