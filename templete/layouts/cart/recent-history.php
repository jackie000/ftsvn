<script language="javascript">
$(document).ready(function() {
    $("input[class^=addtocart]").click(function(){
        window.location.href = $(this).attr("url");
    });
});
</script>
<?php
$history = ClsFactory::instance( "ClsSignin" )->getHistory( 4, $productsId );

if( $history ) {
    ?>
<div class="recent_order">
    <?php echo USER_RECENTLY_VIEWED;?>
</div>
<?php
    $currency = ClsFactory::instance( "ClsCurrency" );
    $clsSeo = ClsFactory::instance("ClsSeo");
	foreach( $history as $k => $v ) {
	    $pts = ClsProductsFactory::instance( $v );
	    $base = $pts->getBase();
	    $link = $clsSeo->getProductsLink($base);
		echo '
    	<div class="rvi">
            '.ClsFactory::instance( "ClsSeo" )->getProductsSeoImages( $v, 64 ).'
            <div>
                <h3>'.ClsFactory::instance( "ClsSeo" )->getProductsSeoLink( $v ).'</h3>
                <p class="save">'.$currency->getCurrency().' '.$currency->getCurrencySign() . $currency->getCurrencyValues( $pts->getSalesPrice() ) . '</p>
                <p class="sw s_star_'. $pts->getProductsRating() .'"><span></span>'.$pts->getProductsReviewsCount().'</p>
                <p><input class="addtocart" title="'.$link['title'] .'" url="'.$link['href'].'" type="button" value="'.CART_ADD_TO_CART.'"></p>
            </div>
        </div>
        <div class="cl"></div>
    	';
	}
	
}
?>