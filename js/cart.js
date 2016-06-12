$(function(){
    jQuery.extend({
        
        updateCheckedItem: function( url, checkboxDom ){
            var data = {};
            checkboxDom.each(function(){
               if( $(this).is(":checked") ){
                   data[$(this).attr("name")] = 'Y';
               }else{
                   data[$(this).attr("name")] = 'N';
               }
            });
            
            $.isLoading();
            $.ajax({
                type: "POST",
                url: url,
                data: $.param(data),
                cache:false,
                success: function(txt){
                    setTimeout( function(){
                        $.isLoading( "hide" );
                    },LOADING_TIME );
                    
                    if( txt ){
                        res = txt.split("###");
                        
                        $("[show='count']").html( res[1] );
                        $("[show='subtotal']").html( res[0] );
                    }
                }
            });
        }
        
    });
});

$.fn.extend({
    
    /*cart
    */
    updateQuantity:function(){
        
        $(this).click(function(){
            
            cartId = $(this).attr("cartId");
            quantity = $(this).attr("quantityValue");
            url = $(this).attr("url");
            
            $.isLoading();
            $.ajax({
                type: "POST",
                url: url,
                data:"cartId="+cartId+"&quantity="+quantity,
                cache:false,
                success: function(txt){
                    setTimeout( function(){
                        $.isLoading( "hide" );
                    },LOADING_TIME );
                    if( txt ){
                        $("a[cartId='"+cartId+"']").parent().hide();
                        
                        var count = 0;
                        $("input[id^='quantity_']").each(function(){
                            value = $(this).val();
                            var num = /^\d+$/;
                            if( !num.test( value ) ){
                                value = 1;
                            }
                            count = count + Number(value);
                        });
                        res = txt.split("###");
                        $("[show='count']").html( res[1] );
                        $("[show='subtotal']").html( res[0] );
                        
                    }else{
                        $.message('Update Cart product quantity failure !');
                        $.closeMessage(CLOSE_MESSAGE);
                        //data error or insert faild
                    }
                }
            });
        });
        
    },
    
    removeItem:function(){
        
        $(this).click(function(){
            cartId = $(this).attr("remove");
            url = $(this).attr("url");
            $.isLoading();
            $.ajax({
                type: "POST",
                url: url,
                data:"cartId="+cartId,
                cache:false,
                success: function(txt){
                    setTimeout( function(){
                        $.isLoading( "hide" );
						if( txt == "0" ){
							$.message('Delete Cart product failure !');
							$.closeMessage(CLOSE_MESSAGE);

						}else if( txt == "empty" ){
							$.message('Your shopping cart is empty.');
							$(".cartContent").hide();
							$(".empty_tips").show();
							$.closeMessage(CLOSE_MESSAGE);
						}else{
							
							var count = 0;
							$("input[id^='quantity_']").each(function(){
								value = $(this).val();
								var num = /^\d+$/;
								if( !num.test( value ) ){
									value = 1;
								}
								count = count + Number(value);
							});
							
							$(".cartId" + cartId).remove();
							res = txt.split("###");
                            $("[show='count']").html( res[1] );
                            $("[show='subtotal']").html( res[0] );
						}
                    },LOADING_TIME );
                }
            });
            
            
        });
    },
    
    checkedAllItem: function( url, checkboxDom ){
        $(this).change(function(){
            if( $(this).is(":checked") ){
                checkboxDom.attr("checked", "checked");
            }else{
                checkboxDom.attr("checked", false);
            }
            
            //change products checkout_selected
            $.updateCheckedItem( url, checkboxDom );
        });
    },
    
    checkedItem: function( url, checkboxDom, checkboxAllDom ){
        $(this).change(function(){
           if( $(this).is(":checked") == false ){
               checkboxAllDom.attr("checked", false);
           }else{
               checkboxAllDom.attr("checked", "checked");
               checkboxDom.each(function(){
                   if( $(this).is(":checked") == false ){
                       checkboxAllDom.attr("checked", false);
                   }
               });
           }
           
           //change products checkout_selected
           $.updateCheckedItem( url, checkboxDom );
           
        });
    },
});
