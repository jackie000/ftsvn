jQuery.fn.exp=function(){
    
}

$.fn.extend({
    
    country:function( sourceData, currentId ){
        
    },
    
    state:function( url,countryId, currentId ){
        var element = $(this);
        element.html("");
        element.parent().parent().children("dt").children("a").attr("value", 0 );
        element.parent().parent().children("dt").children("a").children("span").html( please_select );
        $.getJSON( url, function(result){
            if( result != 0 ){
                $.each( result, function(i, field){
                    if( field.zone_id == currentId ){
                        element.parent().parent().children("dt").children("a").children("span").html( field.zone_name );
                        element.parent().parent().children("dt").children("a").attr("value", field.zone_id );
                    }
                    element.append("<li><a href=\"javascript:void(0);\" value=\""+ field.zone_id +"\" key=\""+ field.zone_code +"\">"+ field.zone_name +"</a></li>");
                });
                
                $(".account_information .input .state dd ul li a").click(function(){
                    var text = $(this).html();
                    var values = $(this).attr("value");
                    $(this).parent().parent().parent().parent().children("dt").children("a").children("span").html(text);
                    $(this).parent().parent().parent().parent().children("dt").children("a").attr("value",values);
                    $("#state_id").attr("value", values);
                    $(this).parent().parent().parent().parent().children("dd").children("ul").hide();
                });
                
            }else{
                element.parent().parent().children("dt").children("a").children("span").html( choice_below );
                element.parent().parent().children("dt").children("a").attr("value", -1);
            }
        });
    }
    
    
});