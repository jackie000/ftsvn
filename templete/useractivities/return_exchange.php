<div id="category_top_image" class="cwidth">
    <img src="/images/category_top_image.jpg" width="980">
</div>

<div id="breadcrumb" class="cwidth">
    <ul>
        <li class="title"><?php echo "<a href=\"" . Hqw::getApplication()->getComponent("Request")->getHostInfo() . "\">" . ucwords( Hqw::getApplication()->getComponent("Request")->getHostInfo(true) ) . "</a>";?></li>
        <li class="space">></li>
        <li class="title"><a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/index');?>"><?php echo USER_MY_ACCOUNT;?></a></li>
        <li class="space">></li>
        <li class="cur title"><?php echo USER_ORDER_RETURN_EXCHANGE;?></li>

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
                <?php echo USER_ORDER_RETURN_EXCHANGE;?>
            </div>
            <div class="order_list">
                <table width="100%">
                    <tr class="th">
                        <td>Apply Code</td>
                        <td>Item</td>
                        <td>Apply Date</td>
                        <td>Status</td>
                    </tr>
                    <!--
                    <tr>
                        <td><a href="#">123456</a></td>
                        <td>
                            <div class="p_list">
                            <a href="#"><img src="p_l1.jpg" height="90"></a>
                            <div style="line-height:15px;">
                                <a href="#">A-Line Sweetheart Short Satin & Chiffon Informal Wedding Dress</a><br />
                                Item Code : <a href="#">N9-0091-3525</a>
                            </div>
                            </div>
                        </td>
                        <td>
                            2012-10-28 16:17:00
                        </td>
                        <td>Status</td>
                    </tr>-->
                </table>
            </div>
        </div>
        
    </div>
    <div class="cl"></div>
</div>