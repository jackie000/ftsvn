<?php
$this->contentWidget( "/layouts/breadcrumb/products", array('productBase'=>$productBase, 'type'=>"reviews") );
?>
<?php
$currency = ClsFactory::instance( "ClsCurrency" );
$salesPrice = $currency->getCurrencyValues( $pts->getSalesPrice() );
$marketPrice = $currency->getCurrencyValues( $pts->getMarketPrice() );
$savePrice = $currency->getCurrencyValues( $pts->getSavePrice() );
$savePercent = $pts->getSavePercent();
$productsLink = ClsFactory::instance( "ClsSeo" )->getProductsLink( $productBase );
$clsPm = ClsFactory::instance( "ClsProductsMethod" );

$reviewsLink = ClsFactory::instance( "ClsSeo" )->getProductsInteractLink( $productBase );

$productsTags = ClsProductsFactory::instance( $productBase['products_id'] )->getProductsTags();
?>
<link rel="stylesheet" href="/js/jRating.jquery.css" type="text/css" />
<link rel="stylesheet" href="/js/jquery.fileupload.css" type="text/css" />
<script language="javascript" src="/js/jRating.jquery.js"></script>
<script src="/js/vendor/jquery.ui.widget.js"></script>
<script src="/js/jquery.iframe-transport.js"></script>
<script src="/js/jquery.fileupload.js"></script>
<script src="/js/jquery.fileupload-process.js"></script>
<script type="text/javascript">
    $(function(){ 
        jQuery.fn.extend({ 
            showWordCount: function() { 
                var _max = $(this).attr('max');
                var _length = $(this).val().length;
                if(_length > _max) {
                    $(this).val($(this).val().substring(0, _max));
                }
                _left = $(this).offset().left;
                _top = $(this).offset().top;
                _width = $(this).width();
                _height = $(this).height();
                $('#div_1').html(_length + '/' + _max);
                $('#div_1').css({
                    'left':_left + _width - 60,
                    'top':_top + _height - 8
                });
            } 
        });
        
        $('textarea').keyup(function(){
            $(this).showWordCount();
        });
        
        $('textarea').showWordCount();
    });
    
    

    $(document).ready(function(){
        $('.review_rating').jRating({
            step:true,
            isDisabled:false,
            length : 5,
            rateMax: 5,
            sendRequest: false,
            onClick:function(){
                var cur = parseInt( $('.jRatingAverage').css('width') );
                var tot = parseInt( $('.jStar').css('width') );
                $('input[name="rating"]').attr( "value", cur / ( tot / 5 ) );
            }
        });
        
        $('.customer_tags_button').hide();
        $('.customer_tags_input').click(function(){
            if( $(this).attr("value") == "<?php echo PRODUCTS_REVIEWS_CUSTOM_TAG;?>" ){
                $(this).attr("value","");
                $('.customer_tags_button').show();
            }
        });
        
        $('.customer_tags_input').blur(function(){
            if( $(this).attr("value") == "" ){
                $(this).attr("value","<?php echo PRODUCTS_REVIEWS_CUSTOM_TAG;?>");
                $('.customer_tags_button').hide();
            }
        });
        
        $('input[name^=tags]').click(function(){
            if( $(this).attr("checked") == true || $(this).attr("checked") == "checked" ){
                $(this).parent().parent().css("border-color","#AD3231");
                $(this).parent().parent().css("border-width","2px");
            }else{
                $(this).parent().parent().css("border-color","#EDD28B");
                $(this).parent().parent().css("border-width","1px");
            }
        });
        
        $(".customer_tags_button").click(function(){
            if( isSignIn == false ){
                $.signin();
            }else{
                var tag = $('.customer_tags_input').attr("value");
                if( tag == "" || tag == "<?php echo PRODUCTS_REVIEWS_CUSTOM_TAG;?>"){
                    $.message('Update Cart product quantity failure !');
                    $.closeMessage(CLOSE_MESSAGE);
                }
                var url = $(this).attr("url");
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {tag:tag},
                    cache:false,
                    success: function(txt){
                        if( parseInt(txt) > 0 ){
                            $('.customer_tags').before("<li style=\"padding:0px;padding-right:10px;\"><span style=\"margin-left:0px;border-color:#AD3231\"><label><input checked=\"checked\" type=\"checkbox\" name=\"tags[" + parseInt(txt) + "]\">" + tag + "</label></span></li>");
                            $('.customer_tags_input').attr("value","<?php echo PRODUCTS_REVIEWS_CUSTOM_TAG;?>");
                        }else{
                            $.message('Update Cart product quantity failure !');
                            $.closeMessage(CLOSE_MESSAGE);
                        }
                    }
                });
                
            }
        });
    });
$(function () {
    url = "<?php echo Hqw::getApplication()->createUrl('index/upload_reviews_files');?>";
    $('#fileupload').fileupload({
        url: url,
        dataType: 'json',
        multipart: true, 
        maxChunkSize: 1000000,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 2000000, // 2 MB
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                if ('error' in file){
                    $.message( file.name + ' ' + file.error);
                    $.closeMessage(CLOSE_MESSAGE);
                }else{
                    $("<p><label><input type='checkbox' name='uploadImages[]' value='"+ file.url +"' checked='checked'> " + file.name + "</label></p>").appendTo('#files');
                }
            });
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
});
</script>
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
        <dt><?php echo PRODUCTS_SHOW_AVERAGE_RATING;?></dt>
        <dd class="sw bsw s_bstar_<?php echo ClsProductsFactory::instance( $productBase['products_id'] )->getProductsRating();?>"><span></span>  
        <b><?php echo ClsFactory::instance("ClsProductsMethod")->getReviewsRatingFormat( $productStatus['review_rating'] );?> </b>
        (<a title="<?php echo $reviewsLink['title'];?>" alt="<?php echo $reviewsLink['alt'];?>" href="<?php echo $reviewsLink['href'];?>" ><?php echo $productStatus['review'];?> <?php echo PRODUCTS_SHOW_CUSTOMER_REVIEWS;?></a>) </dd>
        </dl>
    </div>
    
    <div class="div_left" style="width:810px;margin-left:6px;border-top:none;">
        
        <div class="review_list_title" style="border:1px solid #ddd;border-top:2px solid #AD3231;">
            <form id="frm" method="POST" action="<?php echo Hqw::getApplication()->createUrl('index/write_reviews',array('type'=>'submit_reviews'));?>">
            <div style="padding:10px 20px 0px;">
                <?php
                if( !empty( $errorTips ) ){
                    $this->contentWidget( "/layouts/ua/tips", array( 'type'=>'_tips_error', 'msg'=>$errorTips ) );
                }
                ?>
                <dt><h2><?php echo PRODUCTS_REVIEWS_WRITE_TITLE;?></h2></dt>
                <dd class="write_post">
                    <table width="100%">
                        <input type="hidden" name="productsId" value="<?php echo $productBase['products_id']?>">
                        <input type="hidden" name="rating" value="5">
                        <tr>
                            <td width="100" align="right"><?php echo PRODUCTS_REVIEWS_RATING;?></td>
                            <td style="padding-left:10px;"><div class="review_rating" data-average="5" data-id="3"></div></td>
                        </tr>
                        <tr>
                            <td align="right"><?php echo PRODUCTS_REVIEWS_TAGS;?></td>
                            <td style="padding-left:10px;"><dd class="tags post" style="margin-top:15px;">
                                <ul>
                                    <?php 
                                    if( $productsTags ){
                                        foreach( $productsTags as $k => $v ) {
                                            $t = ClsFactory::instance( "ClsSeo" )->getProductsTags( $v['products_reviews_tags_id'], $v['products_reviews_tags_name'] );
                                        	echo "<li style=\"padding:0px;padding-right:10px;\"><span style=\"margin-left:0px;\"><label><input type=\"checkbox\" name=\"tags[".$v['products_reviews_tags_id']."]\">" . $t['name'] . "</label></span></li>";
                                        }
                                    }
                                    ?>
                                    <li style="padding:0px;" class="customer_tags">
                                    <span style="padding:0px 0px;"><input class="customer_tags_input" value="<?php echo PRODUCTS_REVIEWS_CUSTOM_TAG;?>" type="text"></span>
                                    <span style="padding:0px 0px;margin:0px;" class="btn"><input url="<?php echo Hqw::getApplication()->createUrl('index/write_reviews',array('type'=>'process'));?>" class="customer_tags_button" type="button" value="<?php echo FORM_SUBMIT;?>"></span>
                                    </li>
                                </ul>
                            </dd></td>
                        </tr>
                        <tr>
                            <td align="right" valign="top"><?php echo PRODUCTS_REVIEWS_MESSAGE;?></td>
                            <td style="padding-left:10px;"><div class="post"><textarea style="font-size:11px;" name="mark" rows="8" cols="80" max="500"></textarea>
                            <div id="div_1" class="div_1">0/500</div>
                            </div></td>
                        </tr>
                        <tr>
                            <td align="right" valign="top"><?php echo PRODUCTS_REVIEWS_UPLOAD_IMAGES;?></td>
                            <td style="padding-left:10px;"><div><input type="file" id="fileupload" type="file" name="files[]" multiple></div>
                            <div id="progress" class="progress">
                                <div class="progress-bar progress-bar-success"></div>
                            </div>
                            <div id="files" class="files"></div>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" valign="top"></td>
                            <td style="padding-left:10px;"><div class="post" style="margin-top:15px;"><input type="submit" class="ship_address" value="<?php echo FORM_SUBMIT;?>"></div></td>
                        </tr>
                    </table>
                </dd>
            </div>
            </form>
            
        </div>
    </div>
    <div class="cl"></div>
</div>

<?php
$this->contentWidget( "/layouts/widget/signin" );
$this->contentWidget( "/layouts/widget/message" );
?>