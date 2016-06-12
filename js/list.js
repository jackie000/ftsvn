$(document).ready(function() {
    $("input[name^='options']").click(function(){
        var href = $(this).attr("href");
        window.location.href = href;
    });
});