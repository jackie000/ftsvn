var LOADING_TIME = 1000;
var CLOSE_MESSAGE = 2000;
$(function(){
    jQuery.extend({
        
        signin:function() {
            $('.public_modal').show();
            $('.public_signin').show();
            $(".public_signin").css({"top":( ( $(window).height() - $(".public_signin").height() ) / 3 )+"px"});
            $(".public_signin").css({"left":( ( $(document).width() - $(".public_signin").width() ) / 2 )+"px"});
        },
        
        closeSignin:function(){
            $('.public_modal').hide();
            $('.public_signin').hide();
        },
        
        message:function(msg) {
            $(".public_message_modal").show();
            $('.public_message').show();
            $(".public_message").css({"top":( ( $(window).height() - $(".public_message").height() ) / 3 )+"px"});
            $(".public_message").css({"left":( ( $(document).width() - $(".public_message").width() ) / 2 )+"px"});
            $(".messageContent").html( msg );
        },
        
        closeMessage:function(t){
            if( t == '' || t == 0){
                $(".public_message_modal").hide();
                $('.public_message').hide();
            }else{
                setTimeout( function(){
                    $(".public_message_modal").hide();
                    $('.public_message').hide();
                },t );
            }
        }
    });
});
