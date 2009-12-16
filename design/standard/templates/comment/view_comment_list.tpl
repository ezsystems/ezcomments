
<div id="ezcomments_comment_view_comment">
</div>
{include name="view_page_ui" uri="design:comment/view_comment_list_ui.tpl"}
<script type="text/javascript">
<!--
{literal}
YUI( YUI3_config ).use('node', 'json-stringify', 'io-ez', 'event-custom-complex', function( Y )
{

   // Initialize data when after loading UI
   ezcommentsCommentView.events.on("load",function(e){
        var argObject = new Object();
        argObject.offset = 0;
        argObject.length = 5;
        argObject.targetPage = 1;
        argObject.numberPerPage = 5;
        argObject.oid=parseInt(Y.get("#ezcomments_comment_oid").getAttribute("value"));
        ezcommentsCommentView.currentData.request = argObject;
      
       if(ezcommentsCommentView.events.fire("initdata"))
       {
            var args="";
            args = Y.JSON.stringify( ezcommentsCommentView.currentData.request);
            Y.io.ez( 'comment::get_view_comment_list', {
                    data: 'args='+args,
                    on: {success: function( id,r )
                        { 
                            if ( r.responseJSON.error_text )
                                Y.get( '#ezcomments_comment_message' ).setContent( r.responseJSON.error_text );
                            else
                            {
                                var resContent = r.responseJSON.content;
                                var resObject = Y.JSON.parse( resContent );
                                ezcommentsCommentView.currentData.result = resObject;
                                ezcommentsCommentView.currentData.request = argObject;
                                ezcommentsCommentView.events.fire("commentloaded");
                            }
                        }
                    }
                });
                
        }        
    });
    // invoke callback "commentlist:paint" from another template
    ezcommentsCommentView.events.on("commentloaded",function(e){
        var commentContainer = Y.get('#ezcomments_comment_view_comment');
        var result = ezcommentsCommentView.currentData.result;
        var request = ezcommentsCommentView.currentData.request;
        ezcommentsCommentView.events.fire("commentlist:paint",commentContainer, result, request);
    });
    
});

{/literal}
// -->
</script>
