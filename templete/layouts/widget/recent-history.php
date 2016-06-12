<?php
$history = ClsFactory::instance( "ClsSignin" )->getHistory( 6, $productsId );

if( $history ) {
    $clsPm = ClsFactory::instance( "ClsProductsMethod" );
?>
<div class="bought_together">
    <div class="title_section div_left"><?php echo USER_RECENTLY_VIEWED;?></div>
    <div class="cl"></div>
</div>
<div class="together">
    <table>
        <tr>
            <?php
            foreach( $history as $k => $v ) {
                $product = ClsProductsFactory::instance( $v );
                $base = $product->getBase();
                //$productsLinks = ClsFactory::instance( "ClsSeo" )->getProductsLink( $base );
            	echo "<td>
                        <div class=\"also_bought\">". ClsFactory::instance( "ClsSeo" )->getProductsSeoImages( $v, 150 ) ."</div>
                        <div class=\"also_bought_dec\">
                            <dl>
                                <dt>" . ClsFactory::instance( "ClsSeo" )->getProductsLink( $base, false ) . "</dt>
                            </dl>
                        </div>
                    </td>";
            }
            ?>
        </tr>
    </table>
</div>
<?php
}
?>