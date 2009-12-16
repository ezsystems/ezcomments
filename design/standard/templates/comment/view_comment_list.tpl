
<div id="ezcomments_comment_view_comment">
</div>
<script type="text/javascript">

<!--
{literal}
YUI( YUI3_config ).use('node', 'json-stringify', 'io-ez', 'event-custom-complex', function( Y )
{
    var argObject = new Object();
    argObject.offset = 0;
    argObject.length = 5;
    argObject.targetPage = 1;
    argObject.numberPerPage = 5;
    argObject.oid=parseInt(Y.get("#ezcomments_comment_oid").getAttribute("value"));
    ezcommentsCommentView.currentData.request = argObject;

    ezcommentsCommentView.events.on("commentlist:afterpaintrow",function(i){
        
    });

   // register load event initialize data
   ezcommentsCommentView.events.on("load",function(e){
    //1. request data from server
    
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
    });
    
    ezcommentsCommentView.events.on("commentloaded",function(e){
        var result = ezcommentsCommentView.currentData.result;
        var request = ezcommentsCommentView.currentData.request;
        var currentPage = request.targetPage;
        var numberPerPage = request.numberPerPage;

        if( result != null && result!="undefined" )
        {
            //1.paint comments
            var comments = Y.get('#ezcomments_comment_view_comment');
            var output = "";
            for(var i in result.comments)
            {
                var index = parseInt(i)+1+parseInt(numberPerPage) * (parseInt(currentPage)-1); 
                var row = (result.comments)[i];
                ezcommentsCommentView.events.fire("commentlist:beforepaintrow",row);
                output += "<div id=\"ezcomments_comment_view_commentitem\" class=\"ezcomments-comment-view-comment\">";
                var title = "";
                if(row['title']!=null)
                {
                    title = row['title'];
                }
                output += "<div class=\"ezcomments-comment-view-commenttitle\"><span>"+"#"+index+"</span><span> "+title+"</span></div>";
                output += "<div class=\"ezcomments-comment-view-commentbody\">"+row['text']+"</div>";
                output += "<div class=\"ezcomments-comment-view-commentbottom\">"+row['author']+" on "+ row['modified'] +"</div>";
                output += "</div><br />";
                ezcommentsCommentView.events.fire("commentlist:afterpaintrow",i);
            }
            comments.setContent(output);
          
        }
        
    });
    
    //2. 
    
   
});

{/literal}
// -->
</script>
