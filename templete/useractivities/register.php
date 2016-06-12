<link rel="stylesheet" type="text/css" href="/js/validationEngine.jquery.css" />
<script language="javascript" src="/js/jquery.validationEngine.js"></script>
<script language="javascript">
$(document).ready(function() {
    $("[class^=validate]").validationEngine({
		success :  function(){
		    $("#frm").submit();
		},
		failure : function() {
		    
		}
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
			    echo "<h1 style=\"font-size:18px;\">" . USER_REGISTER . "</h1>";
			}else{
			    echo "<ul>
                <li class=\"one_title current\">SIGN IN</li>
                <li class=\"two_title\">SHIPPING & PAYMENT</li>
                <li class=\"three_title\">PLACE ORDER</li>
            </ul>";
			}
            ?>
        </div>
    </div>    
    <div class="cl"></div>
    <div class="bottom_line"></div>
    <div class="sign_in">
        <form id="frm" method="POST" action="<?php echo Hqw::getApplication()->createUrl('UserActivities/register', array('type'=>'process','from'=>urlencode($_GET['from']) ) );?>">
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
                <td><?php echo USER_EMAIL_TIPS;?></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3"></td>
            </tr>

            <tr>
                <td class="txt_item"><?php echo USER_PASSWORD;?></td><td class="input">
                    <input type="password" name="password" id="password" class="validate[required,length[6,30]]" _length="<?php echo USER_PASSWORD_FORMAT;?>">
                </td><td></td>
            </tr>
            <tr>
                <td></td>
                <td><?php echo USER_PASSWORD_FORMAT;?></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3"></td>
            </tr>

            <tr>
                <td class="txt_item">Confirm Password : </td><td class="input">
                    <input type="password" name="confirm_password" id="confirm_password" class="validate[required,confirm[password],length[6,30]]" _length="<?php echo USER_PASSWORD_FORMAT;?>" _confirm="<?php echo USER_PASSWORD_NOT_MATCH;?>">
                </td><td></td>
            </tr>
            <?php
            if( ClsFactory::instance("ClsSignin")->getAttemptSignin() ){
            ?>
            <tr>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td class="txt_item"></td><td class="input"><img src="/images/validation.jpg" style="margin-right:50px;"><a href="#">Refresh the image</a></td><td></td>
            </tr>
            <tr>
                <td class="txt_item">Enter the verify code shown : </td><td class="input"><input type="text" class="validate[required]" name="verify_code" style="width:150px;"></td><td></td>
            </tr>
            <tr>
                <td colspan="3"></td>
            </tr>

            <tr>
                <td></td><td class="keep_sign">
                <label><input type="checkbox" checked="checked"> I have read and accepted the <?php echo ucwords( Hqw::getApplication()->getComponent("Request")->getHostInfo(true) );?> <a href="#">Registration Agreement</a>.</label>
                </td><td></td>
            </tr>
            <?php
            }
            ?>
            
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
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td></td><td class="login_button">
                <input type="submit" value="Register">
                </td><td></td>
            </tr>
        </table>
        </form>
        <div></div>
    </div>
</div>