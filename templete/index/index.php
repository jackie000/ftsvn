<div id="top">
    <div class="top_div">
        <div class="hot_wedding_dress div_left index_image">
            <div id="focus">
                <ul>
                    <li class="sliderImage">
                        <a href="#"><img src="/images/index_image_silder_1.jpg" width="600" height="270" /></a>
                    </li>
                    <li class="sliderImage">
                        <a href="#"><img src="/images/index_image_silder_2.jpg" width="600" height="270" /></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="special_offers div_right top_a_1"><a href="#"><img src="/images/index_image_top_r.jpg" width="220" height="270" /></a></div>
        <div class="cl"></div>
    </div>
</div>
<div id="body">
    <div id="part1" class="shipping_style cwidth">
        <dl class="cl" style="margin-left:0px;">
            <dt>
            <img src="/images/wedding_dresses_c1.jpg">
            <h2><a href="#">Wedding Dresses</a></h2>
            </dt>
            <dd>» <a href="#"><a href="#">Beach Wedding Dresses</a></dd>
            <dd>» <a href="#">New Arrival Wedding Dresses</a></dd>
            <dd>» <a href="#">Maternity Wedding Dresses</a></dd>
            <dd>» <a href="#">Strapless Wedding Dresses</a></dd>
            <dd>» <a href="#">Mermaid Wedding Dresses</a></dd>
        </dl>
        <dl>
            <dt>
            <img src="/images/party_desses_1.jpg">
            <h2><a href="#">Wedding Party Dresses</a></h2></dt>
            <dd>» <a href="#">Mother of the Bride</a></dd>
            <dd>» <a href="#">Bridesmaid Dresses</a></dd>
            <dd>» <a href="#">Flower Girl Dresses</a></dd>
            <dd>» <a href="#">Wedding Guest Dresses</a></dd>
        </dl>
        <dl class="r_dl">
            <dt>
            <img src="/images/occasion_dresses_1.jpg" width="180" height="138">
            <h2><a href="#">Special Occasion Dresses</a></h2></dt>
            <dd>» <a href="#">Cocktail Dresses</a></dd>
            <dd>» <a href="#">Homecoming Dresses</a></dd>
            <dd>» <a href="#">Quinceanera Dresses</a></dd>
            <dd>» <a href="#">Celebrity Dresses</a></dd>
            <dd>» <a href="#">Graduation Dresses</a></dd>
        </dl>
        <dl>
            <dt>
            <img src="/images/wedding_dresses_e1.jpg" width="180" height="138">
            <h2><a href="#">Prom Dresses</a></h2></dt>
            <dd>» <a href="#">Long Prom Dresses</a></dd>
            <dd>» <a href="#">Short Prom Dresses</a></dd>
            <dd>» <a href="#">Sexy Prom Dresses</a></dd>
            <dd>» <a href="#">High Low Prom Dresses</a></dd>
        </dl>
        <dl>
            <dt>
            <img src="/images/evening_dresses_c1.jpg" width="180" height="138">
            <h2><a href="#">Evening Dresses</a></h2></dt>
            <dd>» <a href="#">2014 Evening Dresses</a></dd>
            <dd>» <a href="#">2014 Spring Fashion Trends</a></dd>
            <dd>» <a href="#">Formal Evening Dresses</a></dd>
            <dd>» <a href="#">Vintage Evening Dresses</a></dd>
        </dl>
    </div>
    <div class="cl"></div>
    
    <div id="part3" class="cwidth">
        <img src="/images/5358.jpg" width="1080">
        <div class="cl"></div>
    </div>
    
    <div id="part5" class="cwidth">
        <dl>
            <dt>Popular Searches</dt>
            <dd class="fp_title">Wedding Dresses,Prom Dresses,Cheap Bridesmaid Dresses,Mother of the Bride Dresses,Homecoming Dresses,Lace Wedding Dresses,Vintage Dresses,Little Black Dresses,Flower Girl Dresses,Special Occasion Dresses,Purple Dresses</dd>
            <dd class="fp_image"><img src="/images/fp_1.jpg" width="554" height="263"></dd>
            <dd class="fp_image_2"><img src="/images/fp_2.jpg" width="300"><br><img src="/images/fp_3.jpg" width="300"></dd>

        </dl>
        <div class="cl"></div>
    </div>

    
    <div id="part4" class="cwidth">
        <div class="ct div_left">
            <dl>
                <dt>Customer Testimonials</dt>
                <dd style="border-right:1px dotted #999999;">
                    <p>"I purchased my dream wedding dress after visiting the Los Angeles Showroom and I LOVED it! Thanks!"</p>
                    By <font>Dannielle Sanderson</font> - From Australia, Sep/19/2014
                </dd>
                <dd style="border-right:1px dotted #999999;">
                    <p>"I just want to let you know...I received the dress. It is so beautiful!!! I am very pleased with my order. Thank for your help!"</p>
                    By <font>Divya Jacob</font> - From United Kingdom, Jun/09/2014
                </dd>
            </dl>
        </div>
        <?php
        
        $pv = Hqw::getApplication()->getModels( "products_reviews" );
        $pvTable = $pv->getTable();
        $pv = $pv->join( Hqw::getApplication()->getModels( "products" ), array( 'on'=>'products_id' ) );
        $pv = $pv->order( "odate DESC" );
        $pv = $pv->order( "rating DESC" );
        $pv = $pv->where( array('products_status'=>1), "AND", Hqw::getApplication()->getModels( "products" ) );
        $pv = $pv->select("products_id,{$pvTable}.rating,{$pvTable}.mark,{$pvTable}.user_id,{$pvTable}.user_name,{$pvTable}.odate");
        $reviewRecommand = $pv->fetch( array('status'=>1) );
        $currency = ClsFactory::instance( "ClsCurrency" );
        $clsSeo = ClsFactory::instance("ClsSeo");
        ?>
        <div class="lr div_right">
        <dl>
            <dt>Reviews</dt>
            <?php
            if( $reviewRecommand ) {
                $pts = ClsProductsFactory::instance( $reviewRecommand['products_id'] );
                $userBase = array();
                if( isset( $reviewRecommand['user_id'] ) && $reviewRecommand['user_id'] != "" && $reviewRecommand['user_id'] != "0" ){
                    $user = ClsUserFactory::instance( $reviewRecommand['user_id'] );
                    $userBase = $user->getBase();
                    $userBase['reviews_by_user'] = $user->getName();
                }else{
                    $userBase['reviews_by_user'] = $reviewRecommand['user_name'];
                }
            ?>
            <dd class="lr_p"><?php echo $clsSeo->getProductsSeoImages( $reviewRecommand['products_id'], 90 );?></dd>
            <dd class="lr_c"><strong><?php echo $clsSeo->getProductsSeoLink( $reviewRecommand['products_id'] );?></strong></dd>
            <dd class="lr_c sw s_star_<?php echo $reviewRecommand['rating'];?>"><span></span><?php echo $pts->getProductsReviewsCount();?></dd>
            <dd class="lr_c"><?php echo $reviewRecommand['mark'];?></dd>
            <dd class="lr_c"><?php echo PRODUCTS_SHOW_REVIEW_BY;?> <font><?php echo $userBase['reviews_by_user'];?></font> - <?php echo PRODUCTS_SHOW_REVIEW_FROM;?> <?php echo isset( $userBase['country'] ) ? $userBase['country'] : "unknow";?>, <?php echo date("M /d / Y H:i:s", strtotime( $reviewRecommand['odate'] ) );?></dd>
            <?php
            }
            ?>
        </dl>
        </div>
        <div class="cl"></div>
    </div>
    <?php
    
    $indexShowProducts = array(9703,9994,9943,10103);
    ?>
    <div id="part6" class="cwidth">
        <?php
        foreach( $indexShowProducts as $v){
            $pts = ClsProductsFactory::instance( $v );
        ?>
        <dl>
            <dt><?php echo $clsSeo->getProductsSeoImages( $v, 245 );?></dt>
            <dd class="list_product_title"><?php echo $clsSeo->getProductsSeoLink( $v );?></dd>
            <dd class="n_p"><span class="normal_price"><?php echo $currency->getCurrency();?> <?php echo $currency->getCurrencySign();?><?php echo $currency->getCurrencyValues( $pts->getMarketPrice() );?></span>  <?php echo $currency->getCurrency();?> <span class="list_money"><?php echo $currency->getCurrencySign();?><?php echo $currency->getCurrencyValues( $pts->getSalesPrice() );?></span></dd>
            <dd class="sw s_star_<?php echo $pts->getProductsRating();?>"><span></span><?php echo $pts->getProductsReviewsCount();?></dd>
        </dl>
        <?php
        }
        ?>
        <div class="cl"></div>
    </div>
</div>