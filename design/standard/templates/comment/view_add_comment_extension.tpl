<script type="text/javascript">

<!--
{literal}
YUI( YUI3_config ).use('node','event-custom-complex', function( Y )
{
    // paint comments into comment list
    ezcommentsCommentView.events.on("addcomment:initui",function(addCommentContainer,user){
        return true;
    });
    
    // vertify field
    ezcommentsCommentView.events.on("addcomment:vertify",function(argObject){
        var result = true;
        var message = "";
        if(argObject.name=="")
        {
            result = false;
            message += "Name can not be empty!<br />";
        }
        if(argObject.email=="")
        {
            result = false;
            message += "Email can not be empty!<br />";
        }
        if(argObject.content=="")
        {
            result = false;
            message += "Content can not be empty!<br />";
        }
        
        if(result == false)
        {
            ezcommentsCommentView.addComment.showMessage(message);
            return false;
        }
        else
        {
            return true;
        }
       
    });
    
    // beforerequest event, if returning false, the request will not be executed
    ezcommentsCommentView.events.on("addcomment:beforerequest",function(argObject){
        return true;
    });
});

{/literal}
// -->
</script>