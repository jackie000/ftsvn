<?php
$this->contentWidget( "/layouts/breadcrumb/products", array('productBase'=>$productBase, 'type'=>"Questions & Answers") );
?>
<?php
$productQuestions = $pd->getData();
$currency = ClsFactory::instance( "ClsCurrency" );
$salesPrice = $currency->getCurrencyValues( $pts->getSalesPrice() );
$marketPrice = $currency->getCurrencyValues( $pts->getMarketPrice() );
$savePrice = $currency->getCurrencyValues( $pts->getSavePrice() );
$savePercent = $pts->getSavePercent();
$productsLink = ClsFactory::instance( "ClsSeo" )->getProductsLink( $productBase );
$clsPm = ClsFactory::instance( "ClsProductsMethod" );
?>
<div class="content_area">
    <div class="rating div_left" style="padding-bottom:20px;border:1px solid #ddd;width:240px;">
        <div class="also_bought" style="padding-left:30px;">
            <a href="<?php echo $productsLink['href'];?>" alt="<?php echo $productsLink['alt'];?>" title="<?php echo $productsLink['title'];?>"><img alt="<?php echo $productsLink['alt'];?>" title="<?php echo $productsLink['title'];?>" src="<?php echo $clsPm->thumbnailImage( $productBase['products_images'], 150 );?>" width="150"></a>
        </div>
        <div class="also_bought_dec">
            <a href="<?php echo $productsLink['href'];?>" alt="<?php echo $productsLink['alt'];?>" title="<?php echo $productsLink['title'];?>"><?php echo $productsLink['name'];?></a>
        </div>
        <div class="also_bought_dec n_p price">
            <?php echo $currency->getCurrency();?> <?php echo $currency->getCurrencySign();?><?php echo $salesPrice;?>
        </div>
        
        <dl>
        <dt>Average Rating:</dt>
        <dd class="sw bsw s_bstar_<?php echo ClsProductsFactory::instance( $productBase['products_id'] )->getProductsRating();?>"><span></span>  
        <b><?php echo ClsFactory::instance("ClsProductsMethod")->getReviewsRatingFormat( $productStatus['review_rating'] );?> </b>
        (<a title="<?php echo $reviewsLink['title'];?>" alt="<?php echo $reviewsLink['alt'];?>" href="<?php echo $reviewsLink['href'];?>" ><?php echo $productStatus['review'];?> reviews</a>) </dd>
        <dd class="write_review"><a class="subt" href="#"><em>Write a review</em></a></dd>
        </dl>
    </div>
    <?php
    
    $questionsLink = ClsFactory::instance( "ClsSeo" )->getProductsInteractLink( $productBase, "questions" );
    ?>
    <link rel="stylesheet" type="text/css" href="/js/validationEngine.jquery.css" />
    <script language="javascript" src="/js/jquery.validationEngine.js"></script>
    <script language="javascript">
    $(document).ready(function() {
        $("[class^=validate]").validationEngine({
    		success :  function(){
    		    $("#frm").submit();
    		},
    		failure : function() {}
    	})
    });
    </script>
    <div class="div_left" style="width:810px;margin-left:6px;border-top:none;">
        <div class="review_list_title" style="border:1px solid #ddd;border-top:2px solid #AD3231;">
            <div style="margin:10px auto;padding: 10px 20px;border-right:1px dotted #ddd;float:left;width:240px;">
                <form method="POST" id="frm" name="frm" action="<?php echo $questionsLink['href'];?>">
                <dl>
                    <dt> questions: </dt>
                    <dd><input type="text" class="validate[required] w50" name="keywords" _required="Please enter your query！" value="<?php echo $params['keywords'];?>" style="height:18px;line-height:18px;outline: medium none;padding: 0 5px 0 3px;vertical-align:middle;"> <input name="submit" type="submit" value="Search" class="bvbutton"></dd>
                </dl>
                </form>
            </div>
            <div style="margin:10px 0px;padding:10px 0px 10px 10px;float:left;width:505px;">
                温馨提示:因厂家更改产品包装、产地或者更换随机附件等没有任何提前通知，且每位咨询者购买情况、提问时间等不同，为此以下回复仅对提问者3天内有效，其他网友仅供参考！若由此给您带来不便请多多谅解，谢谢！
            </div>
            <div class="cl"></div>
        </div>
        <?php
        if( $productQuestions ) {
            
        ?>
            <div class="reviews_list" style="border:none;">
            <?php
            foreach( $productQuestions as $k => $v ) {
                $userQuestion = ClsUserFactory::instance( $v['questioner_user_id'] )->getBase();
            ?>
                <table width="100%" class="fqa">
                <tr>
                    <td class="item"><h2>Q:</h2></td>
                    <td class="content"><?php echo $v['mark'];?></td>
                    <td class="customer">By <b><?php echo $userQuestion['username'];?></b></td>
                    <td class="date"><?php echo $v['odate'];?></td>
                </tr>
                <tr>
                    <td class="item"><h2>A:</h2></td>
                    <td class="content" style="color:#ff6600;"><?php echo $v['answers_mark'];?></td>
                    <td colspan="2" class="date"><?php echo $v['answers_odate'];?></td>
                </tr>
             </table>
            <?php
            }
            ?>
            </div>
            <?php
            echo ClsFactory::instance( "ClsPagination" )->getProductsInteractPage( $productBase, 'questions', $params, $pd->getPagination(), $pd->getTotalItemCount(), $pd->getItemCount() );
            ?>
        <?php
        }else{
        ?>
            <div style="margin-top:10px;padding:15px 0 30px;line-height:25px;border:1px solid #DDDDDD;">
                <div style="padding-left:20px;">
                暂无商品评价！争抢产品评价前5名，前5位评价用户可获得多倍京豆哦！（详见京豆规则）！ <br />
                 只有购买过该商品的用户才能进行评价。  [发表评价]  [最新评价]
                </div>
            </div>
        <?php
        }
        ?>
        
    </div>
    <div class="cl"></div>
</div>
