{def $user=fetch( 'user', 'current_user' )}
{def $anonymous_user_id=ezini( 'UserSettings', 'AnonymousUserID' )}
    {if $user.contentobject_id|eq( $anonymous_user_id )|not()}
    <input type="hidden" id="ezcomments_comment_view_addcomment_defname" value="{$user.login}" />
    <input type="hidden" id="ezcomments_comment_view_addcomment_defemail" value="{$user.email}" />
    <input type="hidden" id="ezcomments_comment_view_addcomment_isanonymous" value="false" />
    {else}
    <input type="hidden" id="ezcomments_comment_view_addcomment_isanonymous" value="true" />
    {/if}
{undef $user $anonymous_user_id}

{def $default_notified=ezini( 'CommentSettings', 'DefaultNotified', 'ezcomments.ini' )}
    <input type="hidden" id="ezcomments_comment_view_addcomment_defnotified" value="{$default_notified}" />
{undef $default_notified}

<div id="ezcomments_comment_view_addcomment" class="ezcomments-comment-view-addcomment">
        <table>
            <tr><td colspan="2" class="ezcomments-comment-view-moduletitle">
                <script type="text/javascript">document.write('Post comment'.ezi18n('add','post_comment'));</script>
            </td></tr>
            <tr>
                <td class="ezcomments-comment-view-addcomment-left">
                    <script type="text/javascript">document.write('Title:'.ezi18n('form','title'));</script>
                </td>
                <td><input type="text" id="ezcomments_comment_view_addcomment_title" maxlength="100" class="ezcomments-comment-view-addcomment-title" /></td>
            </tr>
            <tr>
                <td>
                 <script type="text/javascript">document.write('Name:'.ezi18n('form','name'));</script>
                </td>
                <td><input type="text" id="ezcomments_comment_view_addcomment_name" maxlength="50" class="ezcomments-comment-view-addcomment-name" /></td>
            </tr>
            <tr>
                <td>
                    <script type="text/javascript">document.write('Website:'.ezi18n('form','website'));</script>
                </td>
                <td><input type="text" id="ezcomments_comment_view_addcomment_website"  maxlength="100" class="ezcomments-comment-view-addcomment-website" /></td>
            </tr>
            <tr>
                <td>
                    <script type="text/javascript">document.write('Email:'.ezi18n('form','email'));</script>
                </td>
                <td><input type="text" id="ezcomments_comment_view_addcomment_email" class="ezcomments-comment-view-addcomment-email" maxlength="100" />
                     <script type="text/javascript">document.write(' ( The Email address will not be shown ) '.ezi18n('form','email_mandatory_message'));</script>
                </td>
            </tr>
            <tr>
                <td>
                    <script type="text/javascript">document.write('Content:'.ezi18n('form','content'));</script>
                </td>
                <td><textarea id="ezcomments_comment_view_addcomment_content" class="ezcomments-comment-view-addcomment-textarea"></textarea></td>
            </tr>
            <tr>
                <td>
                    <script type="text/javascript">document.write('Notified:'.ezi18n('form','notified'));</script>
                </td>
                <td><input id="ezcomments_comment_view_addcomment_notified" type="checkbox" /></td>
            </tr>
            <tr>
                <td colspan="2" id="ezcomments_comment_view_addcomment_message" class="ezcomments-comment-view-addcomment-message">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                <script type="text/javascript">document.write("<input type=\"button\" id=\"ezcomments_comment_view_addcomment_post\" class=\"button\" value=\""+
                "Post comment".ezi18n('action','add_comment')+"\" />");</script></td>
            </tr>
        </table>
</div>
{include name="view_page_ui" uri="design:comment/ajax/view_add_comment_extension.tpl"}

<script type="text/javascript">

<!--
{literal}
YUI( YUI3_config ).use('node', 'json-stringify', 'cookie', 'io-ez', 'event-custom-complex', function( Y )
{
    ezcommentsCommentView.events.on('load',function(e){
        if(ezcommentsCommentView.request.user == null)
        {
            //var user = ezcommentsCommentView.currentData.user;
            ezcommentsCommentView.request.user = true;
            var userInfo = new Object();
            var defName = Y.get("#ezcomments_comment_view_addcomment_defname");
            var defEmail = Y.get("#ezcomments_comment_view_addcomment_defemail");
            var defWebsite = Y.get("#ezcomments_comment_view_addcomment_defwebsite");
            var defNotified = Y.get("#ezcomments_comment_view_addcomment_defnotified");
            if(defName!="undefined" && defName!=null )
            {
                userInfo.name = defName.get("value");
            }
            else
            {
                userInfo.name = "";
            }
            if(defEmail!="undefined" && defEmail!=null )
            {
                userInfo.email = defEmail.get("value");
            }
            else
            {
                userInfo.email = "";
            }
            if(defWebsite!="undefined" && defWebsite!=null )
            {
                userInfo.website = defWebsite.get("value");
            }
            else
            {
                userInfo.website = "";
            }
            if(defNotified!="undefined" && defNotified.get("value")=="true" )
            {
                userInfo.notified = true;
            }
            else
            {
                userInfo.notified = false;
            }
            ezcommentsCommentView.userInfo = userInfo;
        }
        Y.get('#ezcomments_comment_view_addcomment_post').on('click',addComment);
    });
    
    ezcommentsCommentView.addComment = new Object();
    
    //show message in adding comment
    var showAddingMessage = function(message)
    {
        var messageContent = "<p>"+message+"</p><p><input type='button' value='OK' id='ezcomments_comment_view_addcomment_messagebutton' class='button' /></p>";
        Y.get( '#ezcomments_comment_view_addcomment_message' ).setContent( messageContent );
        Y.get('#ezcomments_comment_view_addcomment_messagebutton').on('click',function(){
            Y.get( '#ezcomments_comment_view_addcomment_message' ).setContent("");
        });
    }
    ezcommentsCommentView.addComment.showMessage = showAddingMessage;
    
    //clear the message about adding comment
    var clearAddingMessage = function()
    {
        var message = Y.get( '#ezcomments_comment_view_addcomment_message' );
        if( message.get('innerHTML')!="" )
        {
            message.setContent("");
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
            // 1. init input form from user and cookies
            if( user.name != null )
            {
                if(user.name=="" && Y.Cookie.exists('ezcommentsName') )
                {
                    cookieName = Y.Cookie.get('ezcommentsName');
                    Y.get("#ezcomments_comment_view_addcomment_name").set('value', cookieName);
                }
                else
                {
                    Y.get("#ezcomments_comment_view_addcomment_name").set('value', user.name);
                }                
            }
            
            if( user.website != null )
            {
                if(user.website=="" && Y.Cookie.exists('ezcommentsWebsite') )
                {
                    cookieWebsite = Y.Cookie.get('ezcommentsWebsite');
                    Y.get("#ezcomments_comment_view_addcomment_website").set('value', cookieWebsite);
                }
                else
                {
                    Y.get("#ezcomments_comment_view_addcomment_website").set('value',user.website);
                }
            }
            
            if( user.email != null )
            {
                if(user.email=="" && Y.Cookie.exists('ezcommentsEmail') )
                {
                    cookieEmail = Y.Cookie.get('ezcommentsEmail');
                    Y.get("#ezcomments_comment_view_addcomment_email").set('value', cookieEmail);
                }
                else
                {
                    var emailInput = Y.get("#ezcomments_comment_view_addcomment_email");
                    emailInput.set('value',user.email);
                    if( user.email!="undefined" && user.email!="" )
                    {
                        emailInput.set('disabled',true);
                    }
                }
            }
            
            if( user.notified != null )
            {
                var notifiedInput = Y.get("#ezcomments_comment_view_addcomment_notified");
                if( Y.Cookie.exists('ezcomments_notified') )
                {
                    cookieNotified = Y.Cookie.get('ezcomments_notified');
                    if( cookieNotified == true )
                    {
                         notifiedInput.set('checked',true);
                    }
                }
                else
                if( user.notified == true )
                { 
                    notifiedInput.set('checked',true);
                }
            }
            
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
        argObject.language = parseInt(Y.get("#ezcomments_comment_language").get("value"));
        argObject.notified = Y.get("#ezcomments_comment_view_addcomment_notified").get("checked");
        argObject.vertified = null;
        ezcommentsCommentView.events.fire("addcomment:vertify", argObject)
        //1.vertify field
        if(argObject.vertified)
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
                            var isObject = false;
                            try
                            {
                                var resObject = Y.JSON.parse(resContent);
                                if( resObject.type=="ezcomments_error")
                                {
                                    isObject = true;
                                    showAddingMessage(resObject.code+":"+resObject.message);
                                }
                            }
                            catch(e)
                            {
                            }
                            if(!isObject)
                            {
                                showAddingMessage(resContent);
                                //remember the cookie
                                var isAnonymous = Y.get( "#ezcomments_comment_view_addcomment_isanonymous" ).get( "value" );
                                if( isAnonymous=="false" )
                                {
                                    Y.Cookie.set("ezcommentsName", argObject.name);
                                    Y.Cookie.set("ezcommentsWebsite", argObject.website);
                                    Y.Cookie.set("ezcommentsEmail", argObject.email);
                                    Y.Cookie.set("ezcommentsNotified", argObject.notified);
                                }
                                //3.refresh data , jump to first page
                                ezcommentsCommentView.refresh();
                            }
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