<link rel="stylesheet" type="text/css" href="/js/validationEngine.jquery.css" />
<script language="javascript" src="/js/jquery.validationEngine.js"></script>
<script language="javascript" src="/js/country.js"></script>
<script language="javascript">
$(document).ready(function() {
    $("[class^=validate]").validationEngine({
		success :  function(){
		    $("#frm").submit();
		},
		failure : function() {
		    
		}
	})
	<?php
	//$addressBooks['country_id'] = 222;
	//$addressBooks['state_id']= 220;
	if( isset( $addressBooks['country_id'] ) && (int)$addressBooks['country_id'] != 0  ) {
	    $stateId = 0;
	    if( isset( $addressBooks['state_id'] ) && (int)$addressBooks['state_id'] != 0 ) {
	    	$stateId = (int)$addressBooks['state_id'];
	    }
		echo "$(\".account_information .input .state dd ul\").state( \"" . Hqw::getApplication()->createUrl('UserActivities/States', array('country_id'=>(int)$addressBooks['country_id']) ) . "\", ". (int)$addressBooks['country_id'] .", ". $stateId ." );";
	}
	?>
	$(".account_information .input .dropdown dt a").click(function() {
	    if( $(this).attr("value") != "-1" ){
            var s = $(this).parent().parent().parent().attr("class");
            $(".account_information ."+s+" .dropdown dd ul").hide();
            $(this).parent().parent().children("dd").children("ul").toggle();
	    }
	    //$(this).parent().parent().children("dd").children("ul").find("a[key='AIA']").focus();
    });
    
    $(".account_information .input .dropdown dd ul li a").click(function(){
        var text = $(this).html();
        var values = $(this).attr("value");
        $(this).parent().parent().parent().parent().children("dt").children("a").children("span").html(text);
        $(this).parent().parent().parent().parent().children("dt").children("a").attr("value",values);
        $("#country_id").attr("value", values);
        $(".account_information .input .state dd ul").state( "<?php echo Hqw::getApplication()->createUrl('UserActivities/States');?>&country_id="+values, values, 0 );
        $(this).parent().parent().parent().parent().children("dd").children("ul").hide();
    });
    
    $("input[url]").click(function(){
	    window.location.href = $(this).attr("url");
	});
    
});
</script>
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
        if( isset( $_GET['type'] ) && $_GET['type'] == "edit" && isset( $_GET['address_book_id'] ) && $_GET['address_book_id'] != "" ) {
        ?>
        <div>
        <table width="100%">
            <tr>
                <td colspan="3" class="title"><h3 style="color:#333;"><?php echo CHECKOUT_EDIT_ADDRESS;?></h3></td>
            </tr>
        </table>
        </div>
        <form id="frm" method="POST" action="<?php echo $processUpdate;?>">
        <input type="hidden" name="user_address_book_id" id="user_address_book_id" value="<?php echo $addressBooks['user_address_book_id'] ? $addressBooks['user_address_book_id'] : 0;?>">
        <?php
        }else{
        ?>
        <div>
        
        <table width="100%">
            <tr>
                <td colspan="3" class="title"><h3 style="color:#333;"><?php echo $titleOne;?></h3></td>
            </tr>
            <tr>
                <td colspan="3" class="pb10">
                <h3 class="dec"><?php echo $descOne;?></h3>
                </td>
            </tr>
            
            <?php
            $common = ClsFactory::instance( "ClsCommon" );
            if( !empty( $result ) ){
            ?>
            <tr>
                <td colspan="3" class="imp_td">
                    <?php echo CHECKOUT_ADDRESS_BOOK;?>
                </td>
            </tr>

            <tr>
                <td colspan="3" style="padding:10px 15px;">
                    <?php
                    foreach( $result as $k=>$v){
                        $country = $common->getCountries( $v['country_id'] );
                	    $state = isset( $v['state'] ) && $v['state'] != '' ? $v['state'] . ", " : $common->getZones( $v['state_id'] ) . ", ";
                	    $line2 = isset( $v['address_line'] )  && $v['address_line'] != '' ? " ," . $v['address_line'] : "";
                	    $defaultAddress = $v['user_address_book_id'] == $profile['default_address_id'] ? "(". USER_PRIMARY_ADDRESS .")" : "";
                	    $company = isset( $v['company'] ) && $v['company'] != '' ? $v['company'] . " " : "";
                    ?>
                    <div class="address_list showtext">
                        <dl>
                            <dt><form id="frm_ship_address" method="POST" action="<?php echo $submitUrl;?>"><input type="hidden" name="address_book_id" value="<?php echo (int)$v['user_address_book_id'];?>"><input type="submit" value="<?php echo $submitButton;?>" class="ship_address"></form></dt>
                            <dd><b><?php echo $v['full_name'] . " " . $defaultAddress;?></b> </dd>
                            <dd><?php echo $company . $v['street_address'] . $line2;?></dd>
                            <dd><?php echo $v['city'] . ", " . $v['postcode'];?></dd>
                            <dd><?php echo $state . $country . "<br />" . $v['phone_number'];?></dd>
                            <dd><form id="frm_delete_address" method="POST" action="<?php echo Hqw::getApplication()->createUrl( $submitController, array('type'=>'process') );?>">
                            <input url="<?php echo Hqw::getApplication()->createUrl( $submitController, array( 'type'=>'edit','address_book_id'=>(int)$v['user_address_book_id'] ) );?>" style="margin-left:0px;" type="button" value="<?php echo USER_ACT_EDIT;?>" class="edit_button">
                            <input type="hidden" name="user_address_book_id" value="<?php echo (int)$v['user_address_book_id'];?>">
                            <input style="margin-left:10px;" type="submit" value="<?php echo USER_ACT_DELETE;?>" class="edit_button">
                            </form>
                            </dd>
                        </dl>
                    </div>
                    <?php
                    }
                    ?>
                </td>
            </tr>
            <?php
            }
            ?>
            
            <?php
            if( count( $result ) < ClsSettings::$MAX_ADDRESS ) {
                //4. change title
            ?>
            <tr>
                <td colspan="3" class="title"><h3 style="color:#CC6600;<?php echo !empty($result) ? "border-top:solid 1px #ddd;" : ""; ?>padding-top:10px;margin-top:10px;"><?php echo !empty($result) ? CHECKOUT_OR . " " : "";?><?php echo $titleTwo;?></h3></td>
            </tr>
            <tr>
                <td colspan="3" class="pb10">
                <h3 class="dec"><?php echo CHECKOUT_FINISHED_CONTINUE;?></h3>
                </td>
            </tr>
            <?php
            }
            ?>
        </table>
        </div>
            <?php
            if( count( $result ) < ClsSettings::$MAX_ADDRESS ) {
            ?>
        <form id="frm" method="POST" action="<?php echo $processAddition;?>">
            <?php
            }
            ?>
        <?php
        }
        ?>
        
        <?php
        if( count( $result ) < ClsSettings::$MAX_ADDRESS || ( isset( $_GET['type'] ) && $_GET['type'] == "edit" ) ) {
        ?>
        <div class="account_information">
            <table width="100%">
                <tr>
                    <td class="item"><em>*</em> <?php echo USER_FULL_NAME;?></td>
                    <td class="input"><input type="text" name="full_name" id="full_name" class="validate[required,length[2,30]]" _required="<?php echo USER_FULL_NAME_TIPS;?>"  value="<?php echo $addressBooks['full_name'];?>"></td>
                    <td class="input item" align="right"><em>*</em> <b><?php echo USER_REQUEST_INFORMATION;?></b></td>
                </tr>
                <tr class="space">
                    <td></td>
                    <td class="save"><?php echo USER_FULL_NAME_TIPS;?></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="item"> <?php echo USER_COMPANY_NAME;?></td>
                    <td class="input"><input type="text" name="company" id="company" value="<?php echo $addressBooks['company'];?>"></td>
                    <td class="input"></td>
                </tr>
                <tr class="space">
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="item"><em>*</em> <?php echo USER_STREET_ADDRESS;?></td>
                    <td class="input"><input type="text" name="street_address" id="street_address" class="validate[required,length[6,200]]" _required="<?php echo USER_STREET_ADDRESS_TIPS;?>" value="<?php echo $addressBooks['street_address'];?>"></td>
                    <td class="input"></td>
                </tr>
                <tr class="space">
                    <td></td>
                    <td class="save"><?php echo USER_STREET_ADDRESS_TIPS;?></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="item"> <?php echo USER_ADDRESS_LINE;?></td>
                    <td class="input"><input type="text" name="address_line" id="address_line" value="<?php echo $addressBooks['address_line'];?>"></td>
                    <td class="input"></td>
                </tr>
                <tr class="space">
                    <td></td>
                    <td><?php echo USER_ADDRESS_LINE_TIPS;?></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="item"><em>*</em> <?php echo USER_CITY;?></td>
                    <td class="input"><input type="text" name="city" id="city" class="validate[required,length[2,50]]" _required="<?php echo USER_CITY_TIPS;?>" value="<?php echo $addressBooks['city'];?>" style="width:120px;"></td>
                    <td class="input"></td>
                </tr>
                <tr class="space">
                    <td></td>
                    <td class="save"><?php echo USER_CITY_TIPS;?></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="item"><em>*</em> <?php echo USER_COUNTRY;?></td>
                    <td class="input">
                        <dl class="dropdown country">
                            <dt><a href="javascript:void(0);" value="<?php echo $addressBooks['country_id'] ? $addressBooks['country_id'] : 0;?>"><span><?php echo SELECTER_DEFAULT_OPTIONS;?></span></a></dt>
                            <dd>
                                <ul>
                                
                                    <?php
                                    $countries = Hqw::getApplication()->getModels( "countries" )->fetchAll();
                                    if( !empty( $countries ) ) {
                                    	foreach( $countries as $k => $v ) {
                                    	    if( $addressBooks['country_id'] == $v['countries_id'] ) {
                                    	    	echo "<script language=\"javascript\">$(document).ready(function() { $(\".account_information .input .country span\").html(\"{$v['countries_name']}\"); });</script>";
                                    	    }
                                    		echo '<li><a href="javascript:void(0);" value="', $v['countries_id'] ,'" key="' , $v['countries_iso_code_3'] , '">', $v['countries_name'] ,'</a></li>';
                                    	}
                                    }
                                    ?>
                                </ul>
                            </dd>
                        </dl>
                        <input type="hidden" name="country_id" id="country_id" value="<?php echo $addressBooks['country_id'] ? $addressBooks['country_id'] : 0;?>">
                    </td>
                    <td class="input"></td>
                </tr>
                <tr class="space">
                    <td></td>
                    <td class="save"><?php echo USER_COUNTRY_TIPS;?></td>
                    <td></td>
                </tr>
                
                <tr>
                    <td class="item"> <?php echo USER_STATE;?></td>
                    <td class="input">
                        <dl class="dropdown state">
                            <dt><a href="javascript:void(0);" value="<?php echo $addressBooks['state_id'] ? $addressBooks['state_id'] : 0;?>"><span><?php echo SELECTER_DEFAULT_OPTIONS;?></span></a></dt>
                            <dd>
                                <ul>
                                    <li><a href="javascript:void(0);"></a></li>
                                </ul>
                            </dd>
                        </dl>
                        <input type="hidden" name="state_id" id="state_id" value="0">
                        <span style="float:left;margin-left:10px;"><input type="text" id="state" value="<?php echo $addressBooks['state'];?>" name="state" style="width:260px;"></span>
                    </td>
                    <td class="input"></td>
                </tr>
                <tr class="space">
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="item"><em>*</em> <?php echo USER_POST_CODE;?></td>
                    <td class="input"><input type="text" name="postcode" id="postcode" value="<?php echo $addressBooks['postcode'];?>" class="validate[required,length[2,20]]" _required="<?php echo USER_POST_CODE_TIPS;?>" style="width:150px;"></td>
                    <td class="input"></td>
                </tr>
                <tr class="space">
                    <td></td>
                    <td class="save"><?php echo USER_POST_CODE_TIPS;?></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="item"><em>*</em> <?php echo USER_PHONE_NUMBER;?></td>
                    <td class="input"><input type="text" name="phone_number" id="phone_number" value="<?php echo $addressBooks['phone_number'];?>" class="validate[required,length[2,30]]" _required="<?php echo USER_PHONE_NUMBER_TIPS;?>"  style="width:200px;"></td>
                    <td class="input"></td>
                </tr>
                <tr class="space">
                    <td></td>
                    <td class="save"><?php echo USER_PHONE_NUMBER_TIPS;?></td>
                    <td></td>
                </tr>
                <tr class="space">
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <!--tr>
                    <td colspan="2" style="line-height:20px;height:20px;">
                        <h2><b><?php echo CHECKOUT_BILLING_ADDRESS_CONFIRM;?></b></h2>
                        <p><label><input type="radio" name="is_bill" value="yes"><?php echo CHECKOUT_BILLING_YES;?></label>
                        <label><input type="radio" name="is_bill" value="no"><?php echo CHECKOUT_BILLING_NO;?></label><?php echo CHECKOUT_BILLING_ADDRESS_TIPS;?></p>
                    </td>
                    <td></td>
                </tr>-->
            </table>
        </div>
        <div style="margin:10px;"><input type="submit" value="<?php echo CHECKOUT_CONTINUE;?>" class="cart_checkout" ></div>
        </form>
        <?php
        }
        ?>

    </div>
</div>