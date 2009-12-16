<script type="text/javascript">

<!--
{literal}
YUI( YUI3_config ).use('node','event-custom-complex', function( Y )
{
    // paint comments into comment list
    ezcommentsCommentView.events.on("commentlist:paint",function(commentContainer,result, request){
        var currentPage = request.targetPage;
        var numberPerPage = request.numberPerPage;

        if( result != null && result!="undefined" )
        {
            var output = "";
            for(var i in result.comments)
            {
                var index = parseInt(i)+1+parseInt(numberPerPage) * (parseInt(currentPage)-1); 
                var row = (result.comments)[i];
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
            }
            commentContainer.setContent(output);
        }
    });
});

{/literal}
// -->
</script>