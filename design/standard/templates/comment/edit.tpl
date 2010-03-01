{ezcss_require( 'comment.css' )}
{if $can_edit}
    {if is_set( $error_message )}
        <div class="message-error">
           <p>
                {$error_message}
           </p>
           {if is_set( $validation_messages )}
             {foreach $validation_messages as $field => $message }
                <p><strong>{$field}:</strong><br /> {$message}</p>
             {/foreach}
           {/if}
        </div>
    {/if}
    <form method="post" action={concat( '/comment/edit/', $comment.id )|ezurl} name="CommentEdit">
        <input type="hidden" name="ezcomments_comment_redirect_uri" value="{$redirect_uri}" />
        <div class="ezcom-edit" id="ezcomments_comment_edit">
                <div class="ezcom-function-title">
                    <h4>
                        {'Edit comment'|i18n( 'ezcomments/comment/edit' )}
                    </h4>
                </div>
                <div class="ezcom-field ezcom-field-title">
                    <label>
                        {'Title:'|i18n( 'ezcomments/comment/add/form' )}
                    </label>
                    <input type="text" class="box" maxlength="100" id="ezcomments_comment_edit_title" name="CommentTitle" value="{$comment.title|wash}" />
                </div>
                <div class="ezcom-field ezcom-field-name">
                    <label>
                        {'Name:'|i18n( 'ezcomments/comment/add/form' )}
                    </label>
                     <input type="text" class="box" maxlength="50" id="ezcomments_comment_edit_name" disabled="true" name="CommentName" value="{$comment.name|wash}" />
                </div>
                <div class="ezcom-field ezcom-field-website">
                    <label>
                        {'Website:'|i18n( 'ezcomments/comment/add/form' )}
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
                        {'Email:'|i18n( 'ezcomments/comment/add/form' )}
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
                        {'Content:'|i18n( 'ezcomments/comment/add/form' )}
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
                        {'Notified'|i18n( 'ezcomments/comment/add/form' )}
                    </label>
                </div>
                <div class="ezcom-field">
                    <input type="submit"
                           value="{'Update comment'|i18n('ezcomments/comment/action' )}"
                           class="button"
                           id="ezcomments_comment_edit_submit"
                           name="UpdateCommentButton" />
                    <input type="submit" 
                           value="{'Cancel'|i18n('ezcomments/comment/action' )}" 
                           class="button" 
                           id="ezcomments_comment_edit_cancel" 
                           name="CancelButton" />
                </div>
        </div>
    </form>
{else}
    <div class="message-error">
        <p>
            {'You don\'t have access to edit.'|i18n( 'ezcomments/comment/edit' )}
        </p>
    </div>
{/if}