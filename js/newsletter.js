function isEmail(str) { 
    var myReg = eval("/^[a-zA-Z0-9_\.\-]+\@([a-zA-Z0-9\-]+\.)+[a-zA-Z0-9]{2,4}$/");
    if( myReg.test(str) ){
        return true;
    }
    
    return false;
}

$.fn.extend({
    
    newsletter:function(){
        $(this).click(function(){
            var newsletter_email = $("input[name='newsletter_email']").val();
            url = $(this).attr("url");
            if( isEmail(newsletter_email) ){
                $.ajax({
                    type: "POST",
                    url: url,
                    data:"email=" + newsletter_email,
                    cache:false,
                    success: function(txt){
                        alert("In our weekly newsletter, you'll receive tons of wedding inspiration, get helpful tips and tricks, see the hottest trends, and much more, all delivered straight to your inbox");
                    }
                });
            }
        });
    }
});