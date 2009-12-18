{def $user=fetch( 'user', 'current_user' )}
    <input type="hidden" id="ezcomments_comment_view_addcomment_defname" value="{$user.login}" />
    <input type="hidden" id="ezcomments_comment_view_addcomment_defemail" value="{$user.email}" />
{/def}

<div id="ezcomments_comment_view_addcomment" class="ezcomments-comment-view-addcomment">
        <table>
            <tr><td colspan="2" class="ezcomments-comment-view-moduletitle">
                Post Comment
            </td></tr>
            <tr>
                <td class="ezcomments-comment-view-addcomment-left">Title:</td>
                <td><input type="text" id="ezcomments_comment_view_addcomment_title" maxlength="100" class="ezcomments-comment-view-addcomment-title" /></td>
            </tr>
            <tr>
                <td>Name:</td>
                <td><input type="text" id="ezcomments_comment_view_addcomment_name" maxlength="50" class="ezcomments-comment-view-addcomment-name" /></td>
            </tr>
            <tr>
                <td>Website:</td>
                <td><input type="text" id="ezcomments_comment_view_addcomment_website"  maxlength="100" class="ezcomments-comment-view-addcomment-website" /></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><input type="text" id="ezcomments_comment_view_addcomment_email" class="ezcomments-comment-view-addcomment-email" maxlength="100" /> ( The Email address will not be shown ) </td>
            </tr>
            <tr>
                <td>Content:</td>
                <td><textarea id="ezcomments_comment_view_addcomment_content" class="ezcomments-comment-view-addcomment-textarea"></textarea></td>
            </tr>
            <tr>
                <td>Notified:</td>
                <td><input id="ezcomments_comment_view_addcomment_notified" type="checkbox" /></td>
            </tr>
            <tr>
                <td colspan="2" id="ezcomments_comment_view_addcomment_message" class="ezcomments-comment-view-addcomment-message">
                </td>
            </tr>
            <tr>
                <td colspan="2"><input type="button" id="ezcomments_comment_view_addcomment_post" class="button" value="Post Comment" /></td>
            </tr>
        </table>
</div>
{include name="view_page_ui" uri="design:comment/view_add_comment_extension.tpl"}

<script type="text/javascript">

<!--
{literal}
YUI( YUI3_config ).use('node', 'json-stringify', 'io-ez', 'event-custom-complex', function( Y )
{
    ezcommentsCommentView.events.on('load',function(e){
        if(ezcommentsCommentView.request.user == null)
        {
            //var user = ezcommentsCommentView.currentData.user;
            ezcommentsCommentView.request.user = true;
            var userInfo = new Object();
            var defName = Y.get("#ezcomments_comment_view_addcomment_defname");
            var defEmail = Y.get("#ezcomments_comment_view_addcomment_defemail");
            if(defName!="undefined" && defName!=null )
            {
                userInfo.name = defName.get("value");
            }
            if(defEmail!="undefined" && defEmail!=null )
            {
                userInfo.email = defEmail.get("value");
            }
                        
            userInfo.notified = true;
            ezcommentsCommentView.userInfo = userInfo;
        }
        Y.get('#ezcomments_comment_view_addcomment_post').on('click',addComment);
    });
    
    ezcommentsCommentView.addComment = new Object();
    
    //show message in adding comment
    var showAddingMessage = function(message)
    {
        Y.get( '#ezcomments_comment_view_addcomment_message' ).setContent( message );
    }
    ezcommentsCommentView.addComment.showMessage = showAddingMessage;
    
    //clear the message about adding comment
    var clearAddingMessage = function()
    {
        var message = Y.get( '#ezcomments_comment_view_addcomment_message' );
        if( message.get('innerHTML')!="" )
        {
            message.set("innerHTML","");
        }
    }
    ezcommentsCommentView.addComment.clearMessage = clearAddingMessage;
    
    ezcommentsCommentView.events.on('commentloaded',function(e){
        var user = ezcommentsCommentView.userInfo;
        var addCommentContainer = Y.get('#ezcomments_comment_view_addcomment');
        if(ezcommentsCommentView.events.fire("addcomment:initui", addCommentContainer, user))
        {
            Y.get("#ezcomments_comment_view_addcomment_title").set('value','');
            Y.get("#ezcomments_comment_view_addcomment_content").set('value','');
            clearAddingMessage();
            // 1. init input form from user
            if( user.name != null )
            {
                Y.get("#ezcomments_comment_view_addcomment_name").set('value',user.name);
            }
            
            if( user.website != null )
            {
                Y.get("#ezcomments_comment_view_addcomment_website").set('value',user.website);
            }
            
            if( user.email != null )
            {
                var emailInput = Y.get("#ezcomments_comment_view_addcomment_email");
                emailInput.set('value',user.email);
                if( user.email!="undefined" && user.email!=null && user.email!="" )
                {
                    emailInput.set('disabled',true);
                }
            }
            
            if( user.notified != null )
            {
                if( user.notified == true )
                {
                    var notifiedInput = Y.get("#ezcomments_comment_view_addcomment_notified");
                    notifiedInput.set('checked',true);
                }
            }
            
            // 2. init input form from cookies
        }        
    });
    
    var addComment = function(e)
    {
        var argObject = new Object();
        argObject.title = Y.get("#ezcomments_comment_view_addcomment_title").get("value");
        argObject.name = Y.get("#ezcomments_comment_view_addcomment_name").get("value");
        argObject.website = Y.get("#ezcomments_comment_view_addcomment_website").get("value");
        argObject.email = Y.get("#ezcomments_comment_view_addcomment_email").get("value");
        argObject.content = Y.get("#ezcomments_comment_view_addcomment_content").get("value");
        argObject.oid = parseInt(Y.get("#ezcomments_comment_oid").get("value"));
        
        if( Y.get("#ezcomments_comment_view_addcomment_notified").get("value") == "on" )
        {
            argObject.notified = true;
        }
        else
        {
            argObject.notified = false;
        }

        //1.vertify field
        if(ezcommentsCommentView.events.fire("addcomment:vertify", argObject))
        {
            if(ezcommentsCommentView.events.fire("addcomment:beforerequest", argObject))
            {
                //2.send post data
                
                var args="";
                args = Y.JSON.stringify(argObject);
    
                Y.io.ez( 'comment::add_comment', {
                data: 'args='+args,
                on: {success: function( id,r )
                    { 
                        if ( r.responseJSON.error_text )
                            showAddingMessage( r.responseJSON.error_text );
                        else
                        {
                            var resContent = r.responseJSON.content;
                            showAddingMessage(resContent);
                            //3.refresh data
                            ezcommentsCommentView.refresh();
                        }
                    }
                    }
                });
            }
        }
        
    }
});

{/literal}
// -->
</script>