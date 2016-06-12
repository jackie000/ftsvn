<?php
$menuAction = Hqw::getApplication()->getController()->getActions()->getAction();
?>

<div class="search_category div_left">
    <dl>
        <dt class="option_title"><?php echo USER_MY_ORDERS;?></dt>
        <dd class="category_level_1 <?php echo $menuAction == "myorders" ||  $menuAction == "my_orders" ? "cu_item" : "";?>"><a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/my_orders');?>"><?php echo USER_MY_ORDERS;?> ></a></dd>
        <dd class="category_level_1 <?php echo $menuAction == "myfavorites" ||  $menuAction == "my_favorites" ? "cu_item" : "";?>"><a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/my_favorites');?>"><?php echo USER_MY_FAVORITES;?> ></a></dd>
        <dd class="category_level_1 <?php echo $menuAction == "recentlyviewed" ||  $menuAction == "recently_viewed" ? "cu_item" : "";?>"><a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/recently_viewed');?>"><?php echo USER_RECENTLY_VIEWED;?> ></a></dd>
    </dl>
    <dl>
        <dt class="option_title"><?php echo USER_MY_ACCOUNT;?></dt>
        <dd class="category_level_1 <?php echo $menuAction == "accountsetting" ||  $menuAction == "account_setting" ? "cu_item" : "";?>"><a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/account_setting');?>"><?php echo USER_ACCOUNT_SETTING;?> ></a></dd>
        <dd class="category_level_1 <?php echo $menuAction == "passwordmodification" ||  $menuAction == "password_modification" ? "cu_item" : "";?>"><a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/password_modification');?>"><?php echo USER_PASSWORD_MODIFICATION;?> ></a></dd>
        <dd class="category_level_1 <?php echo $menuAction == "addressbook" ||  $menuAction == "address_book" ||  $menuAction == "new_address" ||  $menuAction == "edit_address" ? "cu_item" : "";?>"><a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/address_book');?>"><?php echo USER_ADDRESS_BOOK;?> ></a></dd>
        <!--<dd class="category_level_1"><a href="email.html">Email Subscribe ></a></dd>-->
    </dl>
    <dl>
        <dt class="option_title">Service Center</dt>
        <dd class="category_level_1 <?php echo $menuAction == "returnexchange" ||  $menuAction == "return_exchange" ||  $menuAction == "return_item" ||  $menuAction == "returnitem" ? "cu_item" : "";?>"><a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/return_exchange');?>"><?php echo USER_ORDERS_RETURN_EXCHANGE;?> ></a></dd>
    </dl>
</div>