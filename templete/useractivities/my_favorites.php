<script language="javascript">
$(document).ready(function() {
    
    $("input[class^=addtocart]").click(function(){
	    window.location.href = $(this).attr("url");
	});
	
    $("#check_all").click(function(){
        $(":checkbox").attr("checked","checked");
    });
    
    $("#uncheck_all").click(function(){
        $(":checkbox").removeAttr("checked");
    });
    
    $("#delete_favorites").click(function(){
        $("#frm").submit();
    });
	
});
</script>
<?php
$currency = ClsFactory::instance( "ClsCurrency" );
$clsSeo = ClsFactory::instance("ClsSeo");
$clsPm = ClsFactory::instance( "ClsProductsMethod" );
?>
<div id="category_top_image" class="cwidth">
    <img src="/images/category_top_image.jpg" width="980">
</div>

<div id="breadcrumb" class="cwidth">
    <ul>
        <li class="title"><?php echo "<a href=\"" . Hqw::getApplication()->getComponent("Request")->getHostInfo() . "\">" . ucwords( Hqw::getApplication()->getComponent("Request")->getHostInfo(true) ) . "</a>";?></li>
        <li class="space">></li>
        <li class="title"><a href="<?php echo Hqw::getApplication()->createUrl('UserActivities/index');?>"><?php echo USER_MY_ACCOUNT;?></a></li>
        <li class="space">></li>
        <li class="cur title"><?php echo USER_MY_FAVORITES;?></li>

    </ul>
    <div class="cl"></div>
</div>

<div id="account" class="cwidth">
    <div class="account_menu">
        <?php
        $this->contentWidget( "/layouts/ua/account_menu" );
        $favoritesResult = $pd->getData();
        ?>
        
        <div class="account_index div_left">
            <?php
            if( $successTips != "" ) {
            	$this->contentWidget( "/layouts/ua/tips", array( 'type'=>'_tips_success', 'msg'=>$successTips ) );
            }elseif( $errorTips != "" ){
                $this->contentWidget( "/layouts/ua/tips", array( 'type'=>'_tips_error', 'msg'=>$errorTips ) );
            }
            ?>
            <div class="order_list" style="border-top:solid 1px #EDD28B;">
                
                <?php
                if( $favoritesResult ) {
                ?>
                <form id="frm" method="POST" action="<?php echo Hqw::getApplication()->createUrl('UserActivities/my_favorites', array('type'=>'process') );?>">
                <table width="100%">
                    <?php
                    foreach( $favoritesResult as $k=>$v ){
                        
                        $pts = ClsProductsFactory::instance( $v['products_id'] );
                	    
                	    $salesPrice = $currency->getCurrencyValues( $pts->getSalesPrice() );
                        $marketPrice = $currency->getCurrencyValues( $pts->getMarketPrice() );
                        $savePrice = $currency->getCurrencyValues( $pts->getSavePrice() );
                        $savePercent = $pts->getSavePercent();
                	    $link = $clsSeo->getProductsLink($v);
                    ?>
                    <tr>
                        <td><label><input type="checkbox" name="check[<?php echo $v['products_id'];?>]"> <?php echo $v['products_code'];?></label></td>
                        <td>
                            <div class="p_list">
                            <?php echo $clsSeo->getProductsSeoImages( $v['products_id'], 90 );?>
                            <div><?php echo $clsSeo->getProductsSeoLink( $v['products_id'] );?></div>
                            </div>
                        </td>
                        <td>
                            <span class="save"><?php echo "{$currency->getCurrencySign()}{$salesPrice}"?></span>
                        </td>
                        <td>
                            <input type="button" title="<?php echo $link['title'];?>" url="<?php echo $link['href'];?>" class="addtocart" value="<?php echo CART_ADD_TO_CART;?>">
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </table>
                <table style="border:none;height:25px;line-height:25px;" width="100%">
                    <tr>
                        <td class="check_all" style="border:none;padding-left:50px;">
                        <a href="javascript:void(0);" id="check_all"><?php echo USER_ACT_CHECK_ALL;?></a>
                         / 
                        <a href="javascript:void(0);" id="uncheck_all"><?php echo USER_ACT_UNCHECK_ALL;?></a>
                        <span class="act_split"><i><?php echo USER_ACT_WITH_SELECTED;?></i></span>
                        <a href="javascript:void(0)" id="delete_favorites"><span class="act_delete"><?php echo USER_ACT_DELETE;?></span></a>
                        </td>
                        <td colspan="3" class="gen_pagin" style="border:none;padding:0px;">
                        <div class="pagination">
                        <?php
                        $links = array( 'href'=>Hqw::getApplication()->createUrl('UserActivities/my_favorites'), 'title'=>USER_MY_FAVORITES );
                        echo ClsFactory::instance( "ClsPagination" )->createPagination( $links, $pd->getPagination() );
                        ?>
                        </div>
                        </td>
                    </tr>
                </table>
                </form>
                <?php
                }else{
                ?>
                <table width="100%">
                    <tr>
                        <td align="center"> <?php echo USER_FAVORITES_DONOT_HAVE;?> </td>
                    </tr>
                </table>
                <?php
                }
                ?>
                
                
            </div>
        </div>
    </div>
    <div class="cl"></div>
</div>