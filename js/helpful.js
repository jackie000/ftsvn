$.fn.extend({
    
    
    /*reviews helpful
    */
    helpful:function(){
        
        $(this).click(function(){
            
            if( isSignIn == false ){
                $.signin();
            }else{
                id = $(this).attr("products_reviews_id");
                helpful = $(this).attr("helpful");
                url = $(this).attr("url");
                $.isLoading();
        
                $.ajax({
                    type: "POST",
                    url: url,
                    data:"products_reviews_id="+id+"&helpful="+helpful,
                    cache:false,
                    success: function(txt){
                        $.isLoading( "hide" );
                        if( txt == 100 ){
                            //success, update helpful count
                            num = parseInt( $.trim( $("[helpful='"+id+"_"+helpful+"']").html() ) );
                            $("[helpful='"+id+"_"+helpful+"']").html( (num+1) );
                            $.message('Thank you and provide suggestions!');
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