<script type="text/javascript">
<!--
{literal}
YUI( YUI3_config ).use('node','event-custom-complex','overlay','io-ez', function( Y )
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
                var outputAuthor = row['author'];
                if(row['website']!="undefined" && row['website']!=null && row['website']!="")
                {
                    outputAuthor = "<a href=\""+row['website']+"\">"+outputAuthor+"</a>";
                }
                output += "<div class=\"ezcomments-comment-view-commentbottom\"><span>"+outputAuthor+" on "+ row['created'] +"&nbsp;</span></div>";
                //todo: check the permission
                output += "<div class=\"ezcomments-comment-view-commenttool\"><span><a href=\"javascript:;\" id=\"ezcomments_comment_view_edit\" commentid=\""+row['id']+"\">Edit</a></span> <span><a id=\"ezcomments_comment_view_delete\" href=\"\">Delete</a></span></div>";
                output += "</div><br />";
            }
            commentContainer.setContent(output);
            ezcommentsCommentView.events.fire("commentlist:afterpaint");
        }
    });
    
    ezcommentsCommentView.events.on('commentlist:afterpaint', function( e )
    {
        Y.all('#ezcomments_comment_view_edit').on('click', function( e ){
            var commentID = e.currentTarget.getAttribute('commentid').trim();
            //show the edit form
            showEditForm( commentID );
        });
    });
    
    function showEditForm( commentID )
    {
        var comments = ezcommentsCommentView.currentData.result.comments;
        var comment = null;
        for( var i in comments)
        {
            if(comments[i]['id']==commentID)
            {
                comment = comments[i];
            }
        }
        var output = "<div id=\"ezcomments_comment_extension_edit\" class=\"ezcomments-comment-extension-edit\">";
        output += "<table>";
        output += "<tr><td>Edit Comment</td></tr>";
        output += "<tr><td class=\"ezcomments-comment-view-edit-left\">Title:</td><td><input type=\"text\" class=\"ezcomments-comment-extension-edit-title\" value=\""+comment['title']+"\" /></td></tr>";
        output += "<tr><td>Website:</td><td><input type=\"text\" class=\"ezcomments-comment-extension-edit-website\" value=\""+comment['website']+"\" /></td></tr>";
        output += "<tr><td>Comment:</td><td><textarea class=\"ezcomments-comment-extension-edit-content\">"+comment['text']+"</textarea></td></tr>";
        output += "<tr><td>Notified:</td><td><input type=\"checkbox\" /></td></tr>";
        output += "<tr><td colspan=\"2\"><input type=\"button\" id=\"ezcomments_comment_extension_edit_update\" class=\"button\" value=\"Update Comment\" />";
        output += " <input type=\"button\" id=\"ezcomments_comment_extension_edit_cancel\" class=\"button\" value=\"Cancel\" />";
        output +="</td></tr>";
        output += "</table>";
        output += "</div>";
        var extensionDiv = Y.get('#ezcomments_comment_extension');
        extensionDiv.addClass('ezcomments-comment-extension');
        var width = parseInt(extensionDiv.getStyle('width'));
        var height = parseInt(extensionDiv.getStyle('height'));
        var windowWidth = window.innerWidth;
        var windowHeight = window.innerHeight;
        extensionDiv.setX( (windowWidth-width)/2+window.scrollX );
        extensionDiv.setY( (windowHeight-height)/2+window.scrollY );
        extensionDiv.setContent(output);
        Y.get('#ezcomments_comment_extension_edit_update').on('click', function(e){
            Y.io.ez( 'comment::update_comment', {
                data: 'args=',
                on: {success: function( id,r )
                    { 
                        alert(r.responseJSON.content);
                        removeForm();
                    }
                }
            });
        });
        
        Y.get('#ezcomments_comment_extension_edit_cancel').on('click', removeForm);
        
        function removeForm()
        {
            Y.get('#ezcomments_comment_extension_edit').remove();
            Y.get('#ezcomments_comment_extension').removeClass('ezcomments-comment-extension');
        }
    }
});

{/literal}
// -->
</script>