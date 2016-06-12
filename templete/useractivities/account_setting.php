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

<div id="category_top_image" class="cwidth">
    <img src="/images/category_top_image.jpg" width="980">
</div>

<div id="breadcrumb" class="cwidth">
    <ul>
        <li class="title"><?php echo "<a href=\"" . Hqw::getApplication()->getComponent("Request")->getHostInfo() . "\">" . ucwords( Hqw::getApplication()->getComponent("Request")->getHostInfo(true) ) . "</a>";?></li>
        <li class="space">></li>
        <li class="title"><a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/index');?>"><?php echo USER_MY_ACCOUNT;?></a></li>
        <li class="space">></li>
        <li class="cur title"><?php echo USER_ACCOUNT_SETTING;?></li>

    </ul>
    <div class="cl"></div>
</div>

<div id="account" class="cwidth">
    <div class="account_menu">
        <?php
        $this->contentWidget( "/layouts/ua/account_menu" );
        ?>
        <div class="account_index div_left">
            <?php
            if( $successTips != "" ) {
            	$this->contentWidget( "/layouts/ua/tips", array( 'type'=>'_tips_success', 'msg'=>$successTips ) );
            }elseif( $errorTips != "" ){
                $this->contentWidget( "/layouts/ua/tips", array( 'type'=>'_tips_error', 'msg'=>$errorTips ) );
            }
            ?>
            <form id="frm" method="POST" action="<?php echo Hqw::getApplication()->createUrl('UserActivities/account_setting', array('type'=>'process') );?>">
            <div class="account_information" style="border-top:solid 1px #EDD28B;">
                <table width="100%">
                    <tr>
                        <td class="item"><em>*</em> <?php echo USER_GENDER;?></td>
                        <td class="input"><label><input name="gender" type="radio" value="m" <?php echo isset( $profile['gender'] ) && $profile['gender']== "m" ? "checked='checked'" : "";?> name="gender"><?php echo USER_MALE;?></label>
                        <label><input type="radio" name="gender" value="f" <?php echo isset( $profile['gender'] ) && $profile['gender']== "f" ? "checked='checked'" : "";?> name="gender"><?php echo USER_FEMALE;?></label></td>
                        <td class="input item" align="right"><em>*</em> <b><?php echo USER_REQUEST_INFORMATION;?></b></td>
                    </tr>
                    <tr>
                        <td class="item"><em>*</em> <?php echo USER_FIRST_NAME;?></td>
                        <td class="input"><input type="text" name="firstname" id="firstname" class="validate[required,length[2,30]]" value="<?php echo $profile['firstname'];?>"></td>
                        <td class="input"></td>
                    </tr>
                    <tr>
                        <td class="item"><em>*</em> <?php echo USER_LAST_NAME;?></td>
                        <td class="input"><input type="text" name="lastname" id="lastname" class="validate[required,length[2,30]]" value="<?php echo $profile['lastname'];?>"></td>
                        <td class="input"></td>
                    </tr>
                    <tr>
                        <td class="item"><em>*</em> <?php echo USER_EMAIL;?></td>
                        <td class="input"><input type="text" name="user_email_address" id="user_email_address" value="<?php echo $profile['user_email_address'];?>" class="validate[required,custom[email]]" _email="<?php echo USER_MISSING_EMAIL;?>"></td>
                        <td class="input"></td>
                    </tr>
                    <tr>
                        <td class="item"><em>*</em> <?php echo USER_TELEPHONE;?></td>
                        <td class="input"><input type="text" name="telephone" id="telephone"  class="validate[required,length[8,50]]" value="<?php echo $profile['telephone'];?>"></td>
                        <td class="input"></td>
                    </tr>
                    <tr>
                        <td class="item"><?php echo USER_FAX_NUMBER;?></td>
                        <td class="input"><input type="text" name="fax" id="fax" value="<?php echo $profile['fax'];?>"></td>
                    </tr>
                </table>
            </div>
            <div class="right_button">
                <input type="submit" value="<?php echo USER_UPDATE_BUTTON_TEXT;?>" class="search_button">
            </div>
            </form>
        </div>
        
    </div>
    <div class="cl"></div>
</div>