<script language="javascript">
$(document).ready(function() {
     $(".public_message_modal").height($(document).height()).width($(document).width()).appendTo("body").hide();
     $('.public_message').hide();
     
     $(".msgClose").click(function(){
         $.closeMessage()
     });
});
</script>
<div class="public_message_modal"></div>
<div class="public_message" id="public_message">
    <div class="message">
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr style="background-color: #8F1402;">
                <td class="title"><h3><?php echo MESSAGE_PROMPT_TIPS;?></h3></td>
                <td colspan="2" style="text-align:right;padding-right:10px;"><a href="javascript:void(0);" class="msgClose"><?php echo MESSAGE_CLOSE;?></a></td>
            </tr>
            <tr><td colspan="3" style="padding-top:20px;"></td></tr>
            <tr><td colspan="3" style="line-height25px;"  class="messageContent"></td></tr>
            <tr><td colspan="3" style="padding-top:20px;"></td></tr>
        </table>
    </div>
</div>