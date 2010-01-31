{* Adding comment START *}
{def $user=fetch( 'user', 'current_user' )}
{def $anonymous_user_id=ezini('UserSettings', 'AnonymousUserID')}
{def $is_anonymous=$user.contentobject_id|eq($anonymous_user_id)}
{def $comment_name='' $comment_website='' $comment_email='' $comment_remember='' $comment_notified=false}
{if $is_anonymous}
    {def $cookies = fetch( 'comment', 'comment_cookie' )}
        {if count( $cookies )|gt(0)}
            {set $comment_name=$cookies.name}
            {set $comment_website=$cookies.website}
            {set $comment_email=$cookies.email}
            {set $comment_notified=$cookies.notified}
            {set $comment_remember='1'}
        {/if}
    {undef $cookies}
{else}
{set $comment_name=$user.login}
{set $comment_email=$user.email}
{set $comment_notified=ezini( 'GlobalSettings', 'EnableNotification', 'ezcomments.ini' )}
{/if}

<form id="ezcom-comment-form" method="post" action={'comment/add'|ezurl} name="CommentAdd">
<input type="hidden" name="ContentObjectID" value="{$contentobject_id}" />
<input type="hidden" name="LanguageID" value="{$language_id}" />
<input type="hidden" name="RedirectURI" value={$redirect_uri} />
<div class="ezcom-add" id="ezcomments_comment_view_addcomment">
        <div class="ezcom-function-title">
            <a name="cadd"></a>
            <h4>
             {'Post comment'|i18n( 'extension/ezcomments/add' )}
            </h4>
        </div>
        <div class="ezcom-field ezcom-field-title">
            <label>
                {'Title:'|i18n( 'extension/ezcomments/commentform' )}
            </label>
            <input type="text" class="box" maxlength="100" id="ezcomments_comment_view_addcomment_title" name="CommentTitle" />
        </div>
        <div class="ezcom-field ezcom-field-name">
            <div class="ezcom-filed-error"></div>
            <label>
                {'Name:'|i18n( 'extension/ezcomments/commentform' )}
            </label>
            <input type="text" class="box" maxlength="50" id="ezcomments_comment_view_addcomment_name" name="CommentName" value="{$comment_name}" />
            <span class="ezcom-field-mandatory">*</span>
            
        </div>
        <div class="ezcom-field ezcom-field-website">
            <label>
                {'Website:'|i18n( 'extension/ezcomments/commentform' )}
            </label>
            <input type="text" class="box" maxlength="100" id="ezcomments_comment_view_addcomment_website" name="CommentWebsite" value="{$comment_website}" />
        </div>
        <div class="ezcom-field ezcom-field-email">
            <label>
                {'Email:'|i18n( 'extension/ezcomments/commentform' )}
            </label>
            {if $is_anonymous|not}
                <input type="text" maxlength="100" class="box" id="ezcomments_comment_view_addcomment_email" disabled="true" value="{$comment_email}" />
                <input type="hidden" name="CommentEmail" value="{$comment_email}" />
            {else}
                <input type="text" maxlength="100" class="box" class="ezcomments-comment-view-addcomment-email" id="ezcomments_comment_view_addcomment_email" name="CommentEmail" value="{$comment_email}" />
            {/if} 
                <span class="ezcom-field-mandatory">*</span>
                <span class="ezcom-field-emailmessage">{'( The Email address will not be shown )'|i18n( 'extension/ezcomments/commentform' )}</span>
        </div>
        <div class="ezcom-field ezcom-field-content">
            <label>
                {'Content:'|i18n( 'extension/ezcomments/commentform' )}
            </label>
            <textarea id="ezcomments_comment_view_addcomment_content" class="box" name="CommentContent"></textarea>
            <span class="ezcom-field-mandatory">*</span>
        </div>
        <div class="ezcom-field ezcom-field-notified">
            <label>
                <input type="checkbox" id="ezcom_field_notified" name="CommentNotified" {if $comment_notified|eq( true )}checked{/if} />
                {'Notified'|i18n( 'extension/ezcomments/commentform' )}
            </label>
        </div>
        {if $is_anonymous}
            <div class="ezcom-field-remember">
                <label>
                    <input type="checkbox" name="CommentRememberme" {if $comment_remember}checked="true"{/if} />
                    {'Remember me'|i18n( 'extension/ezcomments/commentform' )}
                </label>
            </div>
         {/if}
        <div class="ezcom-field">
            <input type="submit" value="{'Post comment'|i18n( 'extension/ezcomments/action' )}" class="button" id="ezcom-post-button" name="PostCommentButton" />
            <input type="reset" class="button" value="{'Reset form'|i18n( 'extension/ezcomments/action' )}" />
        </div>
</div>
</form>

{ezscript_require( array( 'ezjsc::yui3', 'ezjsc::yui3io', 'ezcomments.js' ) )}

<script type="text/javascript">
eZComments.cfg = {ldelim}
                    postbutton: '#ezcom-post-button',
                    postform: '#ezcom-comment-form',
                    postlist: '#ezcom-comment-list',
                    postcontainer: '#ezcom-comment-list',
                    sessionprefix: '{ezini('Session', 'SessionNamePrefix', 'site.ini')}', 
                    fields: {ldelim} 
                                name: '#ezcomments_comment_view_addcomment_name',
                                email: '#ezcomments_comment_view_addcomment_email' 
                            {rdelim}
                 {rdelim}

// eZComments.init();
</script>

{undef $comment_name $comment_website $comment_email $comment_notified $comment_remember}
{undef $user $anonymous_user_id $is_anonymous}
{* Adding comment END *}