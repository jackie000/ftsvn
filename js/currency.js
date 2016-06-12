$.fn.extend({
    
    currency:function(){
        $(this).click(function(){
            code = $(this).attr("currency_code");
            url = $(this).attr("url");
            $.ajax({
                type: "POST",
                url: url,
                data:"code="+code,
                cache:false,
                success: function(txt){
                    location.reload();
                }
            });
        });
    }
    
});