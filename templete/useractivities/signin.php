<link rel="stylesheet" type="text/css" href="/js/validationEngine.jquery.css" />
<script language="javascript" src="/js/jquery.validationEngine.js"></script>
<script language="javascript">
$(document).ready(function() {
    $("[class^=validate]").validationEngine({
		success :  function(){
		    $("#frm").submit();
		},
		failure : function() {}
	})
});
</script>
<div id="checkout_step">
    <div id="checkout_header" class="div_left">
        <img src="/images/logo.png" width="213">
    </div>
    <div class="step_one div_right">
        <div class="header_one"></div>
        <div class="header_title" style="text-align:right;font-weight:bold;">
            <?php
            if( isset( $_GET['from'] ) ){	
				if( ($from = base64_decode( $_GET['from'] ) ) === false ){
					$from = "";
				}
			}
			if( strpos( strtolower( $from ), "checkout" ) === false ){
			    echo "<h1 style=\"font-size:18px;\">" . USER_SIGNIN . "</h1>";
			}else{
			    echo "<ul>
                <li class=\"one_title current\">" . strtoupper( USER_SIGNIN ) . "</li>
                <li class=\"two_title\">" . strtoupper( CHECKOUT_SHIPPING_PAYMENT ). "</li>
                <li class=\"three_title\">" . strtoupper( CHECKOUT_PLACE_ORDER ). "</li>
            </ul>";
			}
            ?>
        </div>
    </div>    
    <div class="cl"></div>
    <div class="bottom_line"></div>
    <div class="sign_in">
    <form id="frm" method="POST" action="<?php echo Hqw::getApplication()->createUrl( 'UserActivities/signin', array('type'=>'process','from'=>urlencode($_GET['from']) ) );?>">
        <table>
            <tr>
                <td colspan="3" class="title"></td>
            </tr>
            <tr>
                <?php
                $signin = ClsFactory::instance("ClsSignin");
                $currUserEmail = '';
                if( ( $cookieUser = $signin->getCookieUser() ) !== false ){
                    $baseUser = $cookieUser->getBase();
                    if( isset( $baseUser['user_email_address'] ) ) {
                    	$currUserEmail = $baseUser['user_email_address'];
                    }
                }
                ?>
                <td class="txt_item"><?php echo USER_EMAIL;?></td><td class="input" width="350">
                    <input name="email" id="email" value="<?php echo $currUserEmail;?>" class="validate[required,custom[email]]" type="text" _email="<?php echo USER_MISSING_EMAIL;?>">
                </td><td></td>
            </tr>
            <tr>
                <td></td>
                <td>&nbsp;</td>
                <td></td>
            </tr>

            <tr>
                <td class="txt_item"><?php echo USER_PASSWORD;?></td><td class="input">
                    <input type="password" name="password" id="password" class="validate[required,length[6,30]]" _length="<?php echo USER_PASSWORD_FORMAT;?>">
                </td><td></td>
            </tr>
            
            <?php
            if( ClsFactory::instance("ClsSignin")->getAttemptSignin() ){
            ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="txt_item"></td><td class="input"><img id="captcha" src="<?php echo Hqw::getApplication()->createUrl('UserActivities/captcha');?>" style="margin-right:50px;"><a href="javascript:void(0);" onclick="document.getElementById('captcha').src='<?php echo Hqw::getApplication()->createUrl('UserActivities/Captcha');?>?'+Math.random();"><?php echo USER_REFRESH_IMAGES;?></a></td><td></td>
            </tr>
            <tr>
                <td class="txt_item"><?php echo USER_ENTER_VERIFY_CODE;?></td><td class="input"><input type="text" class="validate[required]" name="verify_code" style="width:150px;"></td><td></td>
            </tr>
            
            <?php
            }
            ?>
            <tr>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td></td><td class="keep_sign">
                <label><input type="checkbox" checked="checked" value="1" name="keep"><?php echo USER_KEEP_SIGNED;?><span><a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/forgot');?>"><?php echo USER_FORGOT_PASSWORD;?></a></span></label>
                </td><td></td>
            </tr>
            
            <?php
            if( $errorTips ) {
            ?>
            <tr>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2" style="font-family:Verdana;border:solid 0px #ccc;line-height:25px;color:#CC0000;">
                <?php echo $errorTips;?>
                </td>
            </tr>
            <?php
            }
            ?>
            <tr>
                <td></td>
                <td>&nbsp;</td>
                <td></td>
            </tr>
            
            <tr>
                <td></td><td class="login_button">
                <input type="submit" value="Sign In" ><h3 style="padding-top:10px;"><span style="padding:0px 5px;"><?php echo USER_SIGNIN_OR;?></span> <a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/register', array('from'=>$_GET['from']));?>" class="imp"><?php echo USER_REGISTER_FOR;?><?php echo ucwords( Hqw::getApplication()->getComponent("Request")->getHostInfo(true) );?></a></h3>
                </td><td></td>
            </tr>
        </table>
        </form>
        <div></div>
    </div>
</div>
