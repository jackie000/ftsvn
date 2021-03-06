<script language="javascript">
$(document).ready(function() {
     $(".public_modal").height($(document).height()).width($(document).width()).appendTo("body").hide();
     $('.public_signin').hide();
     
     $(".jqmClose").click(function(){
         $.closeSignin();
     });
});
$(document).ready(function() {
    $("[class^=validate]").validationEngine({
		success :  function(){
		    $("#frm").submit();
		},
		failure : function() {}
	})
});
</script>
<div class="public_modal">
</div>
<div class="public_signin" id="public_signin">
    <div class="sign_in">
        <form id="frm" method="POST" action="<?php echo Hqw::getApplication()->createUrl( 'UserActivities/signin', array('type'=>'process','from'=>urlencode( base64_encode( Hqw::getApplication()->getComponent("Request")->getHostInfo() . Hqw::getApplication()->getComponent("Request")->getUrl() ) ) ) );?>">
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr style="background-color: #8F1402;">
                <td class="title"><h3><?php echo USER_SIGNIN;?></h3></td>
                <td colspan="2" style="text-align:right;padding-right:10px;"><a href="javascript:void(0);" class="jqmClose">Close</a></td>
            </tr>
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
            <tr><td colspan="3" style="padding-top:20px;"></td></tr>
            <tr>
                <td class="txt_item"><?php echo USER_EMAIL;?></td><td class="input">
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
            <tr>
                <td></td>
                <td>&nbsp;</td>
                <td></td>
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
            <tr>
                <td colspan="3"></td>
            </tr>

            <tr>
                <td></td><td class="login_button">
                <input type="submit" value="Sign In" ><h3 style="padding-top:10px;"><span style="padding:0px 5px;"><?php echo USER_SIGNIN_OR;?></span> <a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/register', array('from'=>$_GET['from']));?>" class="imp"><?php echo USER_REGISTER_FOR;?><?php echo ucwords( Hqw::getApplication()->getComponent("Request")->getHostInfo(true) );?></a></h3>
                </td><td></td>
            </tr>
            <tr><td colspan="3" style="padding-top:20px;"></td></tr>
        </table>
        </form>
        <div></div>
    </div>

</div>