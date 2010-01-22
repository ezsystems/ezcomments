{* Adding comment START *}
{def $user=fetch( 'user', 'current_user' )}
{def $anonymous_user_id=ezini('UserSettings', 'AnonymousUserID')}
{def $is_anonymous=$user.contentobject_id|eq($anonymous_user_id)}
{def $comment_name='' $comment_website='' $comment_email='' $comment_remember='' $comment_notified=true}
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
{set $comment_notified=ezini( 'CommentSettings', 'DefaultNotified', 'ezcomments.ini' )}
{/if}
<form method="post" action={'comment/add'|ezurl} name="CommentAdd">
<input type="hidden" name="ContentObjectID" value="{$contentobject_id}" />
<input type="hidden" name="LanguageID" value="{$language_id}" />
<input type="hidden" name="RedirectURI" value={$redirect_uri} />
<div class="ezcomments-comment-view-addcomment" id="ezcomments_comment_view_addcomment">
        <table>
            <tr>
                <td class="ezcomments-comment-view-moduletitle" colspan="2">
                    {'Post comment'|i18n( 'extension/ezcomments/add' )}
                </td>
            </tr>
            <tr>
                <td class="ezcomments-comment-view-addcomment-left">{'Title:'|i18n( 'extension/ezcomments/commentform' )}</td>
                <td>
                    <input type="text" class="ezcomments-comment-view-addcomment-title" maxlength="100" id="ezcomments_comment_view_addcomment_title" name="CommentTitle" />
                </td>
            </tr>
            <tr>
                <td>{'Name:'|i18n( 'extension/ezcomments/commentform' )}</td>
                <td>
                    <input type="text" class="ezcomments-comment-view-addcomment-name" maxlength="50" id="ezcomments_comment_view_addcomment_name" name="CommentName" value="{$comment_name}" />
                    <span class="ezcomments-comment-view-addcomment-mandatorymessage">*</span>
                </td>
            </tr>
            <tr>
                <td>{'Website:'|i18n( 'extension/ezcomments/commentform' )}</td>
                <td>
                    <input type="text" class="ezcomments-comment-view-addcomment-website" maxlength="100" id="ezcomments_comment_view_addcomment_website" name="CommentWebsite" value="{$comment_website}" />
                </td>
            </tr>
            <tr>
                <td>{'Email:'|i18n( 'extension/ezcomments/commentform' )}</td>
                <td>
                   {if $is_anonymous|not}
                     <input type="text" maxlength="100" class="ezcomments-comment-view-addcomment-email" id="ezcomments_comment_view_addcomment_email" disabled="true" value="{$comment_email}" />
                     <input type="hidden" name="CommentEmail" value="{$comment_email}" />
                   {else}
                      <input type="text" maxlength="100" class="ezcomments-comment-view-addcomment-email" id="ezcomments_comment_view_addcomment_email" name="CommentEmail" value="{$comment_email}" />
                   {/if} 
                    <span class="ezcomments-comment-view-addcomment-mandatorymessage">*</span>
                    <span class="ezcomments-comment-view-addcomment-mandatorymessage">{'( The Email address will not be shown )'|i18n( 'extension/ezcomments/commentform' )}</span>
                </td>
            </tr>
            <tr>
                <td>{'Content:'|i18n( 'extension/ezcomments/commentform' )}</td>
                <td>
                    <textarea class="ezcomments-comment-view-addcomment-textarea" id="ezcomments_comment_view_addcomment_content" name="CommentContent"></textarea>
                    <span class="ezcomments-comment-view-addcomment-mandatorymessage">*</span>
                </td>
            </tr>
            <tr>
                <td>{'Notified:'|i18n( 'extension/ezcomments/commentform' )}</td>
                <td>
                    <input type="checkbox" id="ezcomments_comment_view_addcomment_notified" name="CommentNotified" {if $comment_notified|eq( true )}checked{/if} />
                </td>
            </tr>
            <tr>
                <td class="ezcomments-comment-view-addcomment-message" id="ezcomments_comment_view_addcomment_message" colspan="2" />
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" value="{'Post comment'|i18n( 'extension/ezcomments/add' )}" class="button" id="ezcomments_comment_view_addcomment_post" name="PostCommentButton" />
                    {if $is_anonymous}
                        <input type="checkbox" name="CommentRememberme" {if $comment_remember}checked="true"{/if} />
                        {'Remember me'|i18n( 'extension/ezcomments/commentform' )}
                    {/if}
                </td>
            </tr>
        </table>
</div>
</form>
{undef $comment_name $comment_website $comment_email $comment_notified $comment_remember}
{undef $user $anonymous_user_id $is_anonymous}
{* Adding comment END *}