{ezcss_require( 'comment.css' )}
{if $can_edit}
    {def $fields = ezini( 'FormSettings', 'AvailableFields', 'ezcomments.ini' )}
    {def $fieldRequiredText = '<span class="ezcom-field-mandatory">*</span>'}
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
                
                {if $fields|contains( 'title' )}
                {def $titleRequired = ezini( 'title', 'Required', 'ezcomments.ini' )|eq( 'true' )}
                    <div class="ezcom-field ezcom-field-title">
                        <label>
                            {'Title:'|i18n( 'ezcomments/comment/add/form' )}
                        </label>
                        <input type="text" class="box" maxlength="100" id="ezcomments_comment_edit_title" name="CommentTitle" value="{$comment.title|wash}" />
                    </div>
                {undef $titleRequired}
                {/if}
                
                {if $fields|contains( 'name' )}
                {def $nameRequired = ezini( 'name', 'Required', 'ezcomments.ini' )|eq( 'true' )}
                    <div class="ezcom-field ezcom-field-name">
                        <label>
                            {'Name:'|i18n( 'ezcomments/comment/add/form' )}{if $nameRequired}{$fieldRequiredText}{/if}
                        </label>
                         <input type="text" class="box" maxlength="50" id="ezcomments_comment_edit_name" name="CommentName" value="{$comment.name|wash}" />
                    </div>
                {undef $nameRequired}
                {/if}
                
                {if $fields|contains( 'website' )}
                {def $websiteRequired = ezini( 'website', 'Required', 'ezcomments.ini' )|eq( 'true' )}
                    <div class="ezcom-field ezcom-field-website">
                        <label>
                            {'Website:'|i18n( 'ezcomments/comment/add/form' )}{if $websiteRequired}{$fieldRequiredText}{/if}
                        </label>
                        <input type="text"
                               class="box"
                               maxlength="100"
                               id="ezcomments_comment_edit_website"
                               name="CommentWebsite"
                               value="{$comment.url|wash}" />
                    </div>
                {undef $websiteRequired}
                {/if}
                
                {if $fields|contains( 'email' )}
                {def $emailRequired = ezini( 'email', 'Required', 'ezcomments.ini' )|eq( 'true' )}
                    <div class="ezcom-field ezcom-field-email">
                        <label>
                            {'Email:'|i18n( 'ezcomments/comment/add/form' )}{if $emailRequired}{$fieldRequiredText}{/if}
                        </label>
                        <input type="text"
                               class="box"
                               id="ezcomments_comment_edit_email"
                               name="CommentEmail"
                               disabled="true"
                               value="{$comment.email|wash}" />
                    </div>
                {undef $emailRequired}
                {/if}
                
                <div class="ezcom-field ezcom-field-content">
                    <label>
                        {'Content:'|i18n( 'ezcomments/comment/add/form' )}{$fieldRequiredText}
                    </label>
                    <textarea class="box" id="ezcomments_comment_edit_content" name="CommentContent">{$comment.text|wash}</textarea>
                </div>
                {if $fields|contains( 'notificationField' )}
                    {if $fields|contains( 'email' )}
                        <div class="ezcom-field ezcom-field-notified">
                            <label>
                                <input type="checkbox"
                                       id="ezcomments_comment_edit_notified"
                                       name="CommentNotified"
                                       {if $notified}checked{/if} />
                                {'Notified of new comments'|i18n( 'ezcomments/comment/add/form' )}
                            </label>
                        </div>
                    {/if}
                {/if}
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