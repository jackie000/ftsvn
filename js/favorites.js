$.fn.extend({
    
    
    /*reviews helpful
    */
    favorites:function(){
        
        $(this).click(function(){
            
            if( isSignIn == false ){
                $.signin();
            }else{
                id = $(this).attr("products_id");
                url = $(this).attr("url");
                
                $.isLoading();
        
                $.ajax({
                    type: "POST",
                    url: url,
                    data:"products_id="+id,
                    cache:false,
                    success: function(txt){
                        $.isLoading( "hide" );
                        if( txt == 100 ){
                            
                            $("a[name='cancelFavorites']").show();
                            $("a[name='favorites']").hide();
                            
                            //success, update helpful count
                            num = parseInt( $.trim( $("#favorites_number").html() ) );
                            $("#favorites_number").html( (num+1) );
                            $.message('This product has been Favorite!');
                            $.closeMessage(CLOSE_MESSAGE);
                        }else if( txt == 10 ){
                            //no login
                            $.signin();
                            //show login float div
                        }else if( txt == 0 ){
                            //data error or insert faild
                        }
                    }
                });
            }
            
        });
        
    },
    
    cancelFavorites:function(){
        
        $(this).click(function(){
            
            if( isSignIn == false ){
                $.signin();
            }else{
                id = $(this).attr("products_id");
                url = $(this).attr("url");
                
                ff = $(this);
                
                /*$.isLoading();*/
                $.ajax({
                    type: "POST",
                    url: url,
                    data:"products_id="+id,
                    cache:false,
                    success: function(txt){
                        /*$.isLoading( "hide" );*/
                        if( txt == 100 ){
                            
                            $("a[name='cancelFavorites']").hide();
                            $("a[name='favorites']").show();
                            
                            $.message('This product has canceled Favorite!');
                            $.closeMessage(CLOSE_MESSAGE);
                        }else if( txt == 10 ){
                            //no login
                            $.signin();
                            //show login float div
                        }else if( txt == 0 ){
                            //data error or insert faild
                        }
                    }
                });
            }
            
        });
        
    }
});