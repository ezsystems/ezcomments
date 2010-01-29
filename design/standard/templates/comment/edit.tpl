{ezcss_require( 'comment.css' )}
{if $can_edit}
    {if is_set($message)}
        <div class="message-error">
        <p>
                {$message}
            
        </p>
        </div>
    {/if}
    <form method="post" action={concat( '/comment/edit/', $comment.id )|ezurl} name="CommentEdit">
        <input type="hidden" name="ezcomments_comment_redirect_uri" value="{$redirect_uri}" />
        <div class="ezcom-edit" id="ezcomments_comment_edit">
                <div class="ezcom-function-title">
                    <h4>
                        {'Edit comment'|i18n( 'extension/ezcomments/edit' )}
                    </h4>
                </div>
                <div class="ezcom-field ezcom-field-title">
                    <label>
                        {'Title:'|i18n( 'extension/ezcomments/commentform' )}
                    </label>
                    <input type="text" class="box" maxlength="100" id="ezcomments_comment_edit_title" name="CommentTitle" value="{$comment.title|wash}" />
                </div>
                <div class="ezcom-field ezcom-field-name">
                    <label>
                        {'Name:'|i18n( 'extension/ezcomments/commentform' )}
                    </label>
                     <input type="text" class="box" maxlength="50" id="ezcomments_comment_edit_name" name="CommentName" disabled="true" value="{$comment.name|wash}" />
                </div>
                <div class="ezcom-field ezcom-field-website">
                    <label>
                        {'Website:'|i18n( 'extension/ezcomments/commentform' )}
                    </label>
                    <input type="text"
                           class="box"
                           maxlength="100"
                           id="ezcomments_comment_edit_website"
                           name="CommentWebsite"
                           value="{$comment.url|wash}" />
                </div>
                <div class="ezcom-field ezcom-field-email">
                    <label>
                        {'Email:'|i18n( 'extension/ezcomments/commentform' )}
                    </label>
                    <input type="text"
                           class="box"
                           id="ezcomments_comment_edit_email"
                           name="CommentEmail"
                           disabled="true"
                           value="{$comment.email|wash}" />
                </div>
                <div class="ezcom-field ezcom-field-content">
                    <label>
                        {'Content:'|i18n( 'extension/ezcomments/commentform' )}
                    </label>
                    <textarea class="box" id="ezcomments_comment_edit_content" name="CommentContent">{$comment.text|wash}</textarea>
                    <span class="ezcom-field-mandatory">*</span>
                </div>
                
                <div class="ezcom-field ezcom-field-notified">
                    <label>
                        <input type="checkbox"
                               id="ezcomments_comment_edit_notified"
                               name="CommentNotified"
                               {if $notified}checked{/if} />
                        {'Notified'|i18n( 'extension/ezcomments/commentform' )}
                    </label>
                </div>
                <div class="ezcom-field">
                    <input type="submit"
                           value="{'Update comment'|i18n('extension/ezcomments/action' )}"
                           class="button"
                           id="ezcomments_comment_edit_submit"
                           name="UpdateCommentButton" />
                    <input type="submit" 
                           value="{'Cancel'|i18n('extension/ezcomments/action' )}" 
                           class="button" 
                           id="ezcomments_comment_edit_cancel" 
                           name="CancelButton" />
                </div>
        </div>
    </form>
{else}
    <div class="message-error">
        <p>
            {'You don\'t have access to edit here!'|i18n( 'extension/ezcomments/edit' )}
        </p>
    </div>
{/if}