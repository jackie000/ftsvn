<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7">
<?php
$clsSeo = ClsFactory::instance("ClsSeo");
$clsSeo->getMeta();
?>
<script language="javascript">
var isSignIn = false;
<?php
$sn = ClsFactory::instance("ClsSignin");
if( $sn->getCookieUser() ){
?>    
isSignIn = true;
<?php
}
?>

/*language*/
var please_select = "<?php echo SELECTER_DEFAULT_OPTIONS;?>";
var choice_below = "<?php echo SELECTER_CHOICE_BELOW;?>";

</script>
<script language="javascript" src="/js/jquery.js"></script>
<script language="javascript" src="/js/public.js"></script>
<link rel="stylesheet" type="text/css" href="/css/style.css" />

<!-- index -->
<script language="javascript" src="/js/s3Slider.js"></script>
<link rel="stylesheet" type="text/css" href="/css/nav-top.css" />
<link rel="stylesheet" type="text/css" href="/css/slider.css" />
<link rel="stylesheet" type="text/css" href="/css/part1.css" />


<!-- detail -->
<script language="javascript" src="/js/jquery.jqzoom-core-pack.js"></script>
<link rel="stylesheet" type="text/css" href="/css/jquery.jqzoom.css" />
<link rel="stylesheet" type="text/css" href="/css/d.css" />
<script language="javascript" src="/js/roll.js"></script>

<!-- list -->
<link rel="stylesheet" type="text/css" href="/css/product.css" />

<style>
#menu{display:block;}
#top{display:block;}

.menu_category .cate{
    z-index: 98;
    <?php
    if( strtolower( Hqw::getApplication()->getController()->getId() ) != "index" || strtolower( Hqw::getApplication()->getController()->getActions()->getAction() != "index" ) ){
        echo "display:none;";
    }
    ?>
    position:relative;
}

</style>
<script language="javascript">
$(document).ready(function() {
    $("#searchform").submit(function(){
        if( $("#keywords").val() == "" ){
            return false;
        }
    });
    
    $("#easy_button .member .dropdown").hover(function() {
        $("#easy_button .member .dropdown dd ul").show();
    },function(){
        $("#easy_button .member .dropdown dd ul").hide();
    });
    
    $("#easy_button .money .dropdown").hover(function() {
        $("#easy_button .money .dropdown dd ul").show();
    },
    function(){
        $("#easy_button .money .dropdown dd ul").hide();
    });
    
    $("#easy_button .dropdown dt a").click(function() {
        $(this).parent().parent().parent().parent().find("ul").hide();
        $(this).parent().parent().children("dd").children("ul").toggle();
    });
                
    $("#easy_button .dropdown dd ul li a").click(function() {
        var text = $(this).html();
        $(this).parent().parent().parent().parent().children("dt").children("a").children("span").html(text);
        $(this).parent().parent().parent().parent().children("dd").children("ul").hide();
    });
    
    $(document).bind('click', function(e) {
        var $clicked = $(e.target);
        if (! $clicked.parents().hasClass("dropdown"))
            $(".dropdown dd ul").hide();
    });
});
</script>
</head>
<body>
<div id="easy_button">
    <div class="easy_button cwidth area">
        <div class="div_left">
        <ul>
            <li class="home"><?php echo INDEX_WELCOME;?><?php echo "<a href=\"" . Hqw::getApplication()->getComponent("Request")->getHostInfo() . "\">" . ucwords( Hqw::getApplication()->getComponent("Request")->getHostInfo(true) ) . "</a>";?> !</li>
        </ul>
        </div>
        <?php
        $this->contentWidget( "/layouts/widget/nav-top" );
        ?>
    </div>
</div>

<div id="logo" class="cwidth">
    <div><a href="<?php echo Hqw::getApplication()->getComponent("Request")->getHostInfo();?>"><img src="/images/logo.png" width="256"></a></div>
    <div><table><tr>
        <td style="height:60px;">
            <img src="/images/logo_image_1.gif">
        </td>
    </tr></table></div>
    <div style="width:369px;padding-right:0px;text-align:right;">
    <table class="simply_nav">
    <tr>
        <td><p class="title">FREE SHIPPING</p> <p>for order over  GBP£300</p></td>
        <td style="border-left:rgb(153, 153, 153) dotted 1px;"><p class="title">Sign Up For 5% Off </p><p><a href="#">coupon code: new5%off</a></p></td>
    </tr>    
    </table><div class="cl"></div>
    </div>
    
</div>

<div id="menu" class="cwidth">
    <div class="m_div">
    <?php
    $this->contentWidget( "/layouts/widget/nav-menu" );
    ?>
    <form id="searchform" enctype="application/x-www-form-urlencoded" action="<?php echo Hqw::getApplication()->createUrl('index/search');?>" method="GET" onsubmit="checkSearch(this);">
    <div class="div_left menu_search">
        <div class="search_area">
            <input type="text" name="keywords" id="keywords">
        </div>
    </div>
    <div class="div_left" style="margin-top:2px;">
        <div class="menu_search_button">
            <input type="submit" class="searchbtn" value="">
        </div>
    </div>
    </form>
    <?php
    $cart = ClsFactory::instance("ClsShoppingCart");
    $items = $cart->getItems();
    $clsPm = ClsFactory::instance( "ClsProductsMethod" );
    $currency = ClsFactory::instance( "ClsCurrency" );
    ?>
    <div class="shopping_cart div_right cart">
        <dl class="menu_cart">
            <dt>
                <span>
                    <a href="<?php echo Hqw::getApplication()->createUrl('ShoppingCart/index');?>"><span><?php echo CART_SHOPPING_CART;?> (<b><?php echo $cart->getCount();?></b>)</span></a>
                </span>
            </dt>
            <dd class="cart_detail dropdown_cart">
            <?php
            if( $items ) {
            ?>
            
            <table>
                <?php
                    foreach( $items as $k => $v ) {
                        $itemProducts = $v->getProducts();
                        $productsBase = $itemProducts->getBase();
                        $productsLinks = ClsFactory::instance( "ClsSeo" )->getProductsLink( $productsBase );
                        
                        $attributes = $v->getProductsOptionsToValues();
                        $salesPrice = $currency->getCurrencyValues( $itemProducts->getSalesPrice() );
                        $savePrice = $currency->getCurrencyValues( $itemProducts->getSavePrice() );
                        $savePercent = $itemProducts->getSavePercent();
                ?>
                <tr><td><a title='<?php echo $productsLinks['title'];?>' alt='<?php echo $productsLinks['alt'];?>' href="<?php echo $productsLinks['href'];?>">
                <img title='<?php echo $productsLinks['title'];?>' alt='<?php echo $productsLinks['alt'];?>' src="<?php echo $clsPm->thumbnailImage( $productsBase['products_images'], 64 );?>" width="64">
                </a></td>
                <td><?php echo ClsFactory::instance( "ClsSeo" )->getProductsLink( $productsBase, false );?>
                <br /><br />
                <?php
                if( $attributes ){
                    foreach( $attributes as $m => $n ) {
                    	echo "<b class=\"greencolor itemfont\">{$n['products_options_name']} : {$n['products_options_values_name']}". ( isset( $n['desc'] ) ? $n['desc'] : "" ) ."</b><br />";
                    }
                }
                ?>
                </td></tr>
                <?php	
                    }
                ?>
            </table>
            <table class="sub_total">
                <tr><td class="item"><h2><?php echo CART_TOTAL;?>: </h2></td><td class="n_p"><?php echo $currency->getCurrency();?> <?php echo $currency->getCurrencySign();?><?php echo $currency->getCurrencyValues( $cart->getSubtotal() );?></td>
                </tr>
                <tr><td colspan="2" align="center"><a href="<?php echo Hqw::getApplication()->createUrl('ShoppingCart/index');?>" class="checkout"><div class="view_cart"><?php echo CART_VIEW_CART;?></div></a></td>
                </tr>
            </table>
            <?php
            }else{
                echo "<p>".CART_IS_EMPTY."</p>";
            }
            ?>
            </dd>
        </dl>
    </div>
    </div>
</div>

<?php
echo $content;
?>



<div id="body" style="padding-bottom:30px;">
    <div id="part7" class="cwidth">
        <div class="cg">
            <dl>
                <dt><h2>Company Info</h2></dt>
                <dd><a href="<?php echo Hqw::getApplication()->createUrl("html/about_us");?>">About Us</a></dd>
                <dd><a href="<?php echo Hqw::getApplication()->createUrl("html/about_us");?>">Testimonials</a></dd>
                <dd><a href="<?php echo Hqw::getApplication()->createUrl("html/about_us");?>">Site Map</a></dd>
            </dl>
            <dl>
                <dt><h2>Customer Service</h2></dt>
                <dd><a href="<?php echo Hqw::getApplication()->createUrl("html/about_us");?>">Contact Us</a></dd>
                <dd><a href="<?php echo Hqw::getApplication()->createUrl("html/about_us");?>">Live Chat</a></dd>
                <dd><a href="<?php echo Hqw::getApplication()->createUrl("html/about_us");?>">Track Your Order</a></dd>
            </dl>
            <dl>
                <dt><h2>Payment & Shipping</h2></dt>
                <dd><a href="<?php echo Hqw::getApplication()->createUrl("html/about_us");?>">Payment Methods</a></dd>
                <dd><a href="<?php echo Hqw::getApplication()->createUrl("html/about_us");?>">Shipping Guide</a></dd>
                <dd><a href="<?php echo Hqw::getApplication()->createUrl("html/about_us");?>">Estimated Delivery Time</a></dd>
                <dd><a href="<?php echo Hqw::getApplication()->createUrl("html/about_us");?>">Return Policy</a></dd>
            </dl>
            <dl>
                <dt><h2>Company Policies</h2></dt>
                <dd><a href="<?php echo Hqw::getApplication()->createUrl("html/about_us");?>">Return Policy</a></dd>
                <dd><a href="<?php echo Hqw::getApplication()->createUrl("html/about_us");?>">Privacy Policy</a></dd>
                <dd><a href="<?php echo Hqw::getApplication()->createUrl("html/about_us");?>">Terms of Use</a></dd>
            </dl>
        </div>
        
        <script language="javascript" src="/js/newsletter.js"></script>
        <script language="javascript">
        $(document).ready(function() {
            $("a[name='newsletter_submit']").newsletter();
        });
        </script>
        <div class="newletter">
        <dl>
            <dt><?php echo NEWSLETTER;?></dt>
            <dd class="c"><?php echo NEWSLETTER_DESCRIPTION;?></dd>
            <dd><input type="text" name="newsletter_email" onfocus="this.value='';" value="<?php echo NEWSLETTER_EMAIL;?>" style="color: rgb(153, 153, 153);">
            <a href="javascript:void(0);" url="<?php echo Hqw::getApplication()->createUrl("index/newsletter");?>" name="newsletter_submit" class="subt"><em><?php echo NEWSLETTER_SUBSCRIBE;?></em></a></dd>
            <dd>blog facebook</dd>
        </dl>
        </div>
        <div class="cl"></div>
    </div>

    <div id="footer" class="cwidth">
        <div class="about_us">
            <a href="#">Home</a>|
            <a href="<?php echo Hqw::getApplication()->createUrl("html/about_us");?>">About us</a>|
            <a href="#">Copyright Infringement</a>|
            <a href="#">About us</a>|
            <a href="#">Copyright Infringement</a>|
            <a href="#">About us</a>|
            <a href="#">Copyright Infringement</a>|
            <a href="#">About us</a>|
            <a href="#">Copyright Infringement</a>
        </div>
        <div class="partner">
            <img src="/images/btn_paypal.png">
            <img src="/images/btn_mcafee.gif">
            <img src="/images/btn_verisign.gif">
            <img src="/images/btn_dhl.gif">
            <img src="/images/btn_ups.gif">
            <img src="/images/btn_tnt.gif">
            <img src="/images/btn_ems.gif">
        </div>
        <div class="copyright">
            Copyright © 2008-2012 8MONTH.com. All Rights Reserved. 
        </div>

        <div class="bot_search">
            <a href="#">maternity wedding dresses</a>
            <a href="#">bridesmaid dress</a>
            <a href="#">Prom gown</a>
            <a href="#">wedding gown</a>
            <a href="#">Cheap Wedding dresses</a>
            <a href="#">plus size wedding dresses</a>
            <a href="#">wedding dresses blog</a>
            <a href="#">discount wedding supplies</a>
        </div>
    </div>
</div>
</body>
</html>