<div class="div_right">
<ul>
    <?php
    if( ( $user = ClsFactory::instance("ClsSignin")->getCookieUser() ) === false ){
    ?>
    <li class="login_register bright">
        <a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/signin');?>"><?php echo USER_SIGNIN;?></a> / <a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/register');?>"><?php echo USER_REGISTER;?></a>
    </li>
    <?php
    }else{
    ?>
    <li style="padding:0px 10px 0px 0px;">
        <?php echo INDEX_HELLO_USER;?><a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/index');?>"><b><?php echo ucwords( $user->getName() );?></b></a>
    </li>
    <li CLASS="member bright">
        <dl class="dropdown">
            <dt><span><div class="nation"><a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/index');?>"><?php echo USER_YOUR_ACCOUNT;?></a></div></span></dt>
            <dd>
                <ul style="height:69px;">
                    <li><div class="nation"><a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/index');?>"><?php echo USER_YOUR_ACCOUNT;?></a></div></li>
                    <li><div class="nation"><a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/index');?>"><?php echo USER_MY_ORDERS;?></a></div></li>
                    <li><div class="nation"><a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/my_favorites');?>"><?php echo USER_MY_FAVORITES;?></a></div></li>
                </ul>
            </dd>
        </dl>
    </li>
    <?php
    }
    ?>
    <li style="padding:0px 0px 0px 20px;">
        <?php echo INDEX_CURRENCIES;?>:
    </li>
    <?php
    $currency = ClsFactory::instance( "ClsCurrency" );
    $cur = Hqw::getApplication()->getModels( "currencies" );
    $curResult = $cur->fetchAll();
    ?>
    <script language="javascript" src="/js/currency.js"></script>
    <script language="javascript">
    $(document).ready(function() {
       $("a[currency_code]").currency();
    });
    </script>
    <li class="money bright" style="padding-left:5px;">
        <dl class="dropdown">
            <dt><span><a href="javascript:void(0);" url="<?php echo Hqw::getApplication()->createUrl('index/change_currency');?>" currency_code="<?php echo $currency->getCurrency();?>"><div class="flag <?php echo strtolower( $currency->getCurrency() );?>" alt="<?php echo strtolower( $currency->getCurrency() );?>"></div><div class="nation"><?php echo $currency->getCurrency();?></div></a></span></dt>
            <?php
            if( !empty( $curResult ) ){
            ?>
            <dd>
                <ul style="height:46px;">
                    <?php
                    foreach( $curResult as $k => $v ) {
                        if( $v['code'] == $currency->getCurrency() ) {
                        	continue;
                        }
                    ?>    
                        <li><a href="javascript:void(0);" url="<?php echo Hqw::getApplication()->createUrl('index/change_currency');?>" currency_code="<?php echo $v['code'];?>"><div class="flag <?php echo strtolower( $v['code'] );?>" alt="<?php echo strtolower( $v['code'] );?>"></div><div class="nation"><?php echo $v['code'];?></div></a></li>
                    <?php
                    }
                    ?>
                </ul>
            </dd>
            <?php
            }
            ?>
        </dl>
    </li>
    <li style="padding:0px 10px 0px 20px;"><a href="#"><?php echo INDEX_ORDER_STATUS;?></a></li>
    <li style="padding:0px 10px 0px 10px;"><a href="#"><?php echo INDEX_LIVE_CHAT;?></a></li>
</ul>
</div>