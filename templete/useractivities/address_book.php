<script language="javascript">
$(document).ready(function() {
	$("input[url]").click(function(){
	    window.location.href = $(this).attr("url");
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
        <li class="cur title"><?php echo USER_ADDRESS_BOOK;?></li>

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
            <?php
            if( count($result) < ClsSettings::$MAX_ADDRESS ) {
            ?>
            <div class="right_button">
                <a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/new_address');?>" class="subt"><em><b><?php echo USER_BOOK_NEW_ENTRY;?></b></em></a>
            </div>
            <?php	
            }else{
            ?>
            <div class="right_button">
                <?php
                echo str_replace("<1>", ClsSettings::$MAX_ADDRESS, USER_MAX_ADDRESS);
                ?>
            </div>
            <?php    
            }
            ?>
            <div style="border-top:solid 1px #EDD28B;">
                <table width="100%">
                    <?php
                    if( $result ) {
                        $common = ClsFactory::instance( "ClsCommon" );
                    	foreach( $result as $k => $v ) {
                    	    $country = $common->getCountries( $v['country_id'] );
                    	    $state = isset( $v['state'] ) && $v['state'] != '' ? $v['state'] . ", " : $common->getZones( $v['state_id'] ) . ", ";
                    	    $line2 = isset( $v['address_line'] )  && $v['address_line'] != '' ? " ," . $v['address_line'] : "";
                    	    $defaultAddress = $v['user_address_book_id'] == $profile['default_address_id'] ? "(". USER_PRIMARY_ADDRESS .")" : "";
                    	    
                    	    $company = isset( $v['company'] ) && $v['company'] != '' ? $v['company'] . " " : "";
                    		echo "<tr>",
                                 "<td class=\"address\"><b>{$v['full_name']} {$defaultAddress}</b><br /><br />
                                                    {$v['full_name']} 
                                                    <br />{$company}{$v['street_address']}{$line2}
                                                    <br /> {$v['city']}, {$v['postcode']}
                                                    <br />{$state}{$country}, {$v['phone_number']}</td>",
                                 "<td width=\"200\"><form id=\"frm\" method=\"POST\" action=\"",  Hqw::getApplication()->createUrl('UserActivities/address_book', array( 'type'=>'process') ) ,"\">
                                 <input type=\"hidden\" name=\"address_book_id\" value=\"", $v['user_address_book_id'] ,"\">
                                 <input type=\"button\" url=\"", Hqw::getApplication()->createUrl('UserActivities/new_address', array( 'address_book_id'=>(int)$v['user_address_book_id'] )) ,"\" class=\"edit_button\" value=\"" .USER_ACT_EDIT. "\">
                                 <input type=\"submit\" class=\"edit_button\" url=\"", Hqw::getApplication()->createUrl('UserActivities/delete_address'), "\" value=\"" . USER_ACT_DELETE . "\"></form></td>",
                             "</tr>";
                    	}
                    }
                    ?>
                </table>
            </div>
        </div>
        
    </div>
    <div class="cl"></div>
</div>