<script type="text/javascript">
<!--
{literal}
YUI( YUI3_config ).use('node','event-custom-complex','overlay','io-ez','json-stringify', function( Y )
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
                output += "<div class=\"ezcomments-comment-view-commenttool\">"+
                                "<span>"+
                                    "<a href=\"javascript:;\" id=\"ezcomments_comment_view_edit\" commentid=\""+row['id']+"\">"+"Edit".ezi18n('view','edit')+"</a>"+
                                "</span> "+ 
                                "<span>"+
                                    "<a id=\"ezcomments_comment_view_delete\" href=\"javascript:;\" commentid=\""+row['id']+"\">"+"Delete".ezi18n('view','delete')+"</a>"+
                                "</span>"+
                            "</div>";
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
        
        Y.all('#ezcomments_comment_view_delete').on('click', function( e ){
            var commentID = e.currentTarget.getAttribute('commentid').trim();
            //show the delete form
            showDeleteForm( commentID );
            registerDeleteEvent( commentID );
        });
    });
    
    var showEditForm = function( commentID )
    {
        /*
        var comments = ezcommentsCommentView.currentData.result.comments;
        var comment = null;
        for( var i in comments)
        {
            if(comments[i]['id']==commentID)
            {
                comment = comments[i];
            }
        }*/
        //get comment
        Y.io.ez( 'comment::get_comment', {
                data: 'commentID='+commentID,
                on: {success: function( id,r )
                    { 
                        var comment = Y.JSON.parse(r.responseJSON.content);
                        showEditCommentForm( comment );
                        registerUpdateEvent( commentID );
                    }
                }
            });
     }
     
    var showDeleteForm = function( commentID )
    {
        var output = "<div id=\"ezcomments_comment_extension_delete\" class=\"ezcomments-comment-extension-delete\">";
        output += "<div>"+"Delete comment?".ezi18n('delete','confirmation_message')+"</div>";
        output += "<div><input type=\"button\" id=\"ezcomments_comment_extension_delete_submit\" class=\"button\" value=\""+"Delete".ezi18n('action','delete')+"\" /> <input id=\"ezcomments_comment_extension_delete_cancel\" type=\"button\" class=\"button\" value=\""+"Cancel".ezi18n('action','cancel')+"\" />";
        output += "</div>";
        outputToPanel( output );
    }
    
    var showEditCommentForm = function( comment )
    {
        var output = "<div id=\"ezcomments_comment_extension_edit\" class=\"ezcomments-comment-extension-edit\">";
        output += "<table>";
        output += "<tr><td>"+"Edit comment".ezi18n('edit','edit_comment')+"</td></tr>";
        output += "<tr><td class=\"ezcomments-comment-view-edit-left\">"+"Title:".ezi18n('form','title')+"</td><td><input type=\"text\" class=\"ezcomments-comment-extension-edit-title\" id=\"ezcomments-comment-extension-edit-title\" value=\""+comment.title+"\" /></td></tr>";
        output += "<tr><td>"+"Website:".ezi18n('form','website')+"</td><td><input type=\"text\" class=\"ezcomments-comment-extension-edit-website\" id=\"ezcomments-comment-extension-edit-website\" value=\""+comment.website+"\" /></td></tr>";
        output += "<tr><td>"+"Content:".ezi18n('form','content')+"</td><td><textarea class=\"ezcomments-comment-extension-edit-content\" id=\"ezcomments-comment-extension-edit-content\">"+comment.content+"</textarea></td></tr>";
        output += "<tr><td>"+"Notified:".ezi18n('form','notified')+"</td><td><input type=\"checkbox\" id=\"ezcomments-comment-extension-edit-notified\" /></td></tr>";
        output += "<tr><td colspan=\"2\"><input type=\"button\" id=\"ezcomments_comment_extension_edit_update\" class=\"button\" value=\""+"Update comment".ezi18n('action','update_comment')+"\" />";
        output += " <input type=\"button\" id=\"ezcomments_comment_extension_edit_cancel\" class=\"button\" value=\""+"Cancel".ezi18n('action','cancel')+"\" />";
        output += "</td></tr>";
        output += "</table>";
        output += "</div>";
        outputToPanel( output );
     }
     
     var outputToPanel = function( output )
     {
        var extensionDiv = Y.get('#ezcomments_comment_extension');
        extensionDiv.addClass('ezcomments-comment-extension');
        var width = parseInt(extensionDiv.getStyle('width'));
        var height = parseInt(extensionDiv.getStyle('height'));
        var windowWidth = window.innerWidth;
        var windowHeight = window.innerHeight;
        extensionDiv.setX( (windowWidth-width)/2+window.scrollX );
        extensionDiv.setY( (windowHeight-height)/2+window.scrollY );
        extensionDiv.setContent(output);
    }
        
    var registerUpdateEvent = function( commentID )
    { 
        Y.get('#ezcomments_comment_extension_edit_update').on('click', function(e){
            var editArg = new Object();
            editArg.commentID = commentID;
            editArg.title = Y.get("#ezcomments-comment-extension-edit-title").get("value");
            editArg.website = Y.get("#ezcomments-comment-extension-edit-website").get("value");
            editArg.content = Y.get("#ezcomments-comment-extension-edit-content").get("value");
            var args = Y.JSON.stringify(editArg);
            Y.io.ez( 'comment::update_comment', {
                data: 'args='+args,
                on: {success: function( id,r )
                    { 
                        var result = Y.JSON.parse(r.responseJSON.content);
                        if( result.result )
                        {
                            ezcommentsCommentView.refresh();
                            removeUpdateForm();
                            
                        }
                        else
                        {
                            alert( result.message );
                        }
                    }
                }
            });
        });
        Y.get('#ezcomments_comment_extension_edit_cancel').on('click', removeUpdateForm);
      }
    
    var registerDeleteEvent = function( commentID )
    {
        Y.get('#ezcomments_comment_extension_delete_submit').on('click', function(e){
            Y.io.ez( 'comment::delete_comment', {
                data: 'commentID='+commentID,
                on: {success: function( id,r )
                    { 
                        ezcommentsCommentView.refresh();
                        removeDeleteForm();
                    }
                }
            });
        });
        Y.get('#ezcomments_comment_extension_delete_cancel').on('click', function()
        {
            removeDeleteForm();
        });
    }
    
    function removeUpdateForm()
    {
        Y.get('#ezcomments_comment_extension_edit').remove();
        Y.get('#ezcomments_comment_extension').removeClass('ezcomments-comment-extension');
    }
    
    function removeDeleteForm()
    {
        Y.get('#ezcomments_comment_extension_delete').remove();
        Y.get('#ezcomments_comment_extension').removeClass('ezcomments-comment-extension');
    }
});

{/literal}
// -->
</script>