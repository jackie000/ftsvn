<dl class="scbottom">
    <dt class="option_title"><?php echo LIST_HOT_SALE;?></dt>
</dl>
<?php
$products = Hqw::getApplication()->getModels( "products_status" );
$products = $products->join( Hqw::getApplication()->getModels( "products" ), array( 'on'=>'products_id' ) );
$products = $products->order( "sales_total DESC" );
$products = $products->limit( 5 );
$products = $products->where( array('products_status'=>1), "AND", Hqw::getApplication()->getModels( "products" ) );

$result = $products->fetchAll();
if( count( $result ) > 0 ) {
    $clsSeo = ClsFactory::instance("ClsSeo");
    $currency = ClsFactory::instance( "ClsCurrency" );
    foreach( $result as $k => $v ) {
        $pts = ClsProductsFactory::instance( $v['products_id'] );
        $savePercent = $pts->getSavePercent();
        
        echo "
        <dl class=\"scbottom\">
            <dt class=\"list_product_title\">{$clsSeo->getProductsSeoLink( $v['products_id'] )}</dt>
            <dt>{$clsSeo->getProductsSeoImages( $v['products_id'], 180 )}</dt>
            <dd class=\"n_p\"><span class=\"normal_price\">{$currency->getCurrency()} {$currency->getCurrencySign()}{$currency->getCurrencyValues($pts->getMarketPrice())}</span> {$currency->getCurrency()} <span class=\"list_money\">{$currency->getCurrencySign()}{$currency->getCurrencyValues($pts->getSalesPrice())}</span></dd>
            <dd class=\"sw s_star_" . ClsProductsFactory::instance( $v['products_id'] )->getProductsRating() . "\"><span></span>" . $pts->getProductsReviewsCount() . "</dd>
        </dl>
        ";
    }
}
?>
