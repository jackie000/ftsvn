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
	if( isset( $addressBooks['country_id'] ) && (int)$addressBooks['country_id'] != 0  ) {
	    $stateId = 0;
	    if( isset( $addressBooks['state_id'] ) && (int)$addressBooks['state_id'] != 0 ) {
	    	$stateId = (int)$addressBooks['state_id'];
	    }
		echo "$(\".account_information .input .state dd ul\").state( \"" . Hqw::getApplication()->createUrl('UserActivities/States', array('country_id'=>(int)$addressBooks['country_id']) ) . "\", ". (int)$addressBooks['country_id'] .", ". $stateId ." );";
	}
	?>
	$(".account_information .input .dropdown dt a").click(function() {
	    console.log( $(this).attr("value") );
	    if( $(this).attr("value") != "-1" ){
            var s = $(this).parent().parent().parent().attr("class");
            $(".account_information ."+s+" .dropdown dd ul").hide();
            $(this).parent().parent().children("dd").children("ul").toggle();
	    }
	    //$(this).parent().parent().children("dd").children("ul").find("a[key='AIA']").focus();
    });
    
    $(".account_information .input .dropdown dd ul li a").click(function(){
        console.log($(this).html());
        var text = $(this).html();
        var values = $(this).attr("value");
        $(this).parent().parent().parent().parent().children("dt").children("a").children("span").html(text);
        $(this).parent().parent().parent().parent().children("dt").children("a").attr("value",values);
        $("#country_id").attr("value", values);
        $(".account_information .input .state dd ul").state( "<?php echo Hqw::getApplication()->createUrl('UserActivities/States');?>&country_id="+values, values, 0 );
        $(this).parent().parent().parent().parent().children("dd").children("ul").hide();
    });
    
});
</script>
<div id="category_top_image" class="cwidth">
    <img src="/images/category_top_image.jpg" width="980">
</div>

<div id="breadcrumb" class="cwidth">
    <ul>
        <li class="title"><?php echo "<a href=\"" . Hqw::getApplication()->getComponent("Request")->getHostInfo() . "\">" . ucwords( Hqw::getApplication()->getComponent("Request")->getHostInfo(true) ) . "</a>";?></li>
        <li class="space">></li>
        <li class="title"><a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/index');?>"><?php echo USER_MY_ACCOUNT;?></a></li>
        <li class="space">></li>
        <li class="title"><a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/address_book');?>"><?php echo USER_ADDRESS_BOOK;?></a></li>
        <li class="space">></li>
        <li class="cur title"><?php echo USER_BOOK_NEW_ENTRY;?></li>

    </ul>
    <div class="cl"></div>
</div>

<div id="account" class="cwidth">
    <div class="account_menu">
        <?php
        $this->contentWidget( "/layouts/ua/account_menu" );
        ?>
        <div class="account_index div_left">
            <div class="recent_order" style="margin-top:0px;">
                <?php echo USER_NEW_ADDRESS_BOOK_ENTRY;?>
            </div>
            <div class="account_information">
                <?php
                if( $successTips != "" ) {
                	$this->contentWidget( "/layouts/ua/tips", array( 'type'=>'_tips_success', 'msg'=>$successTips ) );
                }elseif( $errorTips != "" ){
                    $this->contentWidget( "/layouts/ua/tips", array( 'type'=>'_tips_error', 'msg'=>$errorTips ) );
                }
                ?>
                <form id="frm" method="POST" action="<?php echo Hqw::getApplication()->createUrl('UserActivities/new_address', array('type'=>'process') );?>">
                <input type="hidden" name="user_address_book_id" id="user_address_book_id" value="<?php echo $addressBooks['user_address_book_id'] ? $addressBooks['user_address_book_id'] : 0;?>">
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
                    <tr>
                        <td class="item" style="padding-right:0px;">
                        <?php
                        if( isset($addressBooks['user_address_book_id']) && isset($profile['default_address_id']) && $profile['default_address_id'] != $addressBooks['user_address_book_id'] ){
                            echo "<input type=\"checkbox\" value=\"1\" id=\"user_address_default\" name=\"user_address_default\" style=\"width:25px;border:0px;\">";
                        }else{
                            echo "<input type=\"checkbox\" checked=\"checked\" value=\"1\" id=\"user_address_default\" name=\"user_address_default\" style=\"width:25px;border:0px;\">";
                        }
                        ?>
                        </td>
                        <td><?php echo USER_SET_PRIMARY_ADDRESS;?></td>
                        <td></td>
                    </tr>
                </table>
            </div>
            <div class="right_button">
                <input type="submit" value="<?php echo FORM_SUBMIT;?>" class="search_button">
            </div>
            </form>
        </div>
        
    </div>
    <div class="cl"></div>
</div>