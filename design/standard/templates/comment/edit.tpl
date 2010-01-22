{ezcss_require( 'edit.css' )}
{if $can_edit}
    <div class="ezcomments-comment-edit-message">
    <p>
        {if is_set($message)}
            {$message}
        {/if}
    </p>
    </div>
    <form method="post" action={concat( '/comment/edit/', $comment.id )|ezurl} name="CommentEdit">
        <input type="hidden" name="ezcomments_comment_redirect_uri" value="{$redirect_uri}" />
        <div class="ezcomments-comment-edit" id="ezcomments_comment_edit">
                <table>
                    <tr>
                        <td class="ezcomments-comment-edit-moduletitle" colspan="2">
                            {'Edit comment'|i18n( 'extension/ezcomments/edit' )}
                        </td>
                    </tr>
                    <tr>
                        <td class="ezcomments-comment-edit-left">{'Title:'|i18n( 'extension/ezcomments/commentform' )}</td>
                        <td>
                            <input type="text" class="ezcomments-comment-edit-title" maxlength="100" id="ezcomments_comment_edit_title" name="CommentTitle" value="{$comment.title|wash}" />
                        </td>
                    </tr>
                    <tr>
                        <td>{'Name:'|i18n( 'extension/ezcomments/commentform' )}</td>
                        <td>
                            <input type="text" class="ezcomments-comment-edit-name" maxlength="50" id="ezcomments_comment_edit_name" name="CommentName" disabled="true" value="{$comment.name|wash}" />
                        </td>
                    </tr>
                    <tr>
                        <td>{'Website:'|i18n( 'extension/ezcomments/commentform' )}</td>
                        <td>
                            <input type="text" class="ezcomments-comment-edit-website" maxlength="100" id="ezcomments_comment_edit_website" name="CommentWebsite" value="{$comment.url|wash}" />
                        </td>
                    </tr>
                    <tr>
                        <td>{'Email:'|i18n( 'extension/ezcomments/commentform' )}</td>
                        <td>
                            <input type="text" maxlength="100" class="ezcomments-comment-edit-email" id="ezcomments_comment_edit_email" name="CommentEmail" disabled="true" value="{$comment.email|wash}" /> 
                        </td>
                    </tr>
                    <tr>
                        <td>{'Content:'|i18n( 'extension/ezcomments/commentform' )}</td>
                        <td>
                            <textarea class="ezcomments-comment-edit-textarea" id="ezcomments_comment_edit_content" name="CommentContent">{$comment.text|wash}</textarea>
                            <span class="ezcomments-comment-edit-mandatorymessage">*</span>
                        </td>
                    </tr>
                    <tr>
                        <td>{'Notified:'|i18n( 'extension/ezcomments/commentform' )}</td>
                        <td>
                            <input type="checkbox" id="ezcomments_comment_edit_notified" name="CommentNotified" {if $comment.notification}checked{/if} />
                        </td>
                    </tr>
                    <tr>
                        <td class="ezcomments-comment-edit-message" id="ezcomments_comment_edit_message" colspan="2" />
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="submit" value="{'Update comment'|i18n('extension/ezcomments/action' )}" class="button" id="ezcomments_comment_edit_submit" name="UpdateCommentButton" />
                            <input type="submit" value="{'Cancel'|i18n('extension/ezcomments/action' )}" class="button" id="ezcomments_comment_edit_cancel" name="CancelButton" />
                        </td>
                    </tr>
                </table>
        </div>
    </form>
{else}
    <div class="ezcomments-comment-edit-message">
    <p>
        {'You don\'t have access to edit here!'|i18n( 'extension/ezcomments/edit' )}
    </p>
    </div>
{/if}