<?php
$signin = ClsFactory::instance("ClsSignin");
if( ( $currentUser = $signin->getCookieUser() ) !== false ){

    $favorites = Hqw::getApplication()->getModels( "user_favorites" );
	$favorites = $favorites->join( Hqw::getApplication()->getModels( "products" ), array( 'on'=>'products_id' ) );
    $favorites = $favorites->join( Hqw::getApplication()->getModels( "products_description" ), array( 'on'=>'products_id' ) );
	$favorites = $favorites->order( "favorites_date_added DESC" );
	$favorites = $favorites->limit( 5 );
	$favorites = $favorites->where( array('products_status'=>1), "AND", Hqw::getApplication()->getModels( "products" ) );
	
	$query = array( 'user_id'=>$currentUser->getUserId() );
	$result = $favorites->fetchAll( $query );
	if( count( $result ) > 0 ) {
	    $clsSeo = ClsFactory::instance("ClsSeo");
?>

<div id="cart_favorite" class="recommend" style="padding-left:20px;margin-top:25px;">
    <table>
        <tr><td colspan="5" class="title"><?php echo USER_MY_FAVORITES;?></td></tr>
        <tr class="p">
            <?php
            foreach( $result as $k => $v ) {
                $pts = ClsProductsFactory::instance( $v['products_id'] );
                $pts->setBase( $v );
            	echo '<td><div class="other">'.$clsSeo->getProductsSeoImages( $v['products_id'], 90 ).'</div>
            <div class="dec">'.$clsSeo->getProductsSeoLink( $v['products_id'] ).'</div></td>';
            }
            ?>
        </tr>
    </table>
</div>
<?php
    }
}
?>