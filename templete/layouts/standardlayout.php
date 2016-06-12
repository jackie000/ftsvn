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
<link rel="stylesheet" type="text/css" href="/css/checkout.css" />
<link rel="stylesheet" type="text/css" href="/css/product.css" />
<link rel="stylesheet" type="text/css" href="/css/style.css" />
<script language="javascript">
$(document).ready(function() {
    $(".sign_in .input :input").focus(function() {
        $(this).addClass("input_fouce");
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
    
    $(document).bind('click', function(e) {
        var $clicked = $(e.target);
        if (! $clicked.parents().hasClass("dropdown"))
            $(".dropdown dd ul").hide();
    });

});
</script>
</head>
<body>


<?php
echo $content;
?>


<div id="simple_footer">
    <div class="copyright">
        Copyright © 2008-<?php echo date("Y",Hqw::getApplication()->getComponent("Date")->cTime());?> <?php echo ucwords( Hqw::getApplication()->getComponent("Request")->getHostInfo(true) );?>. All Rights Reserved. 
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
</body>
</html>