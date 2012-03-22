{ezcss_require( 'comment.css' )}
<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">
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
        <input type="hidden" name="ezcomments_comment_redirect_uri" value={$redirect_uri|ezurl( , 'full' )} />
        <div class="ezcom-edit">
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
                        <input type="text" class="box" maxlength="100" name="CommentTitle" value="{$comment.title|wash}" />
                    </div>
                {undef $titleRequired}
                {/if}
                
                {if $fields|contains( 'name' )}
                {def $nameRequired = ezini( 'name', 'Required', 'ezcomments.ini' )|eq( 'true' )}
                    <div class="ezcom-field ezcom-field-name">
                        <label>
                            {'Name:'|i18n( 'ezcomments/comment/add/form' )}{if $nameRequired}{$fieldRequiredText}{/if}
                        </label>
                         <input type="text" class="box" maxlength="50" name="CommentName" value="{$comment.name|wash}" />
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
                               name="CommentEmail"
                               disabled="disabled"
                               value="{$comment.email|wash}" />
                    </div>
                {undef $emailRequired}
                {/if}
                
                <div class="ezcom-field ezcom-field-content">
                    <label>
                        {'Content:'|i18n( 'ezcomments/comment/add/form' )}{$fieldRequiredText}
                    </label>
                    <textarea class="box" name="CommentContent" rows="" cols="">{$comment.text|wash}</textarea>
                </div>
                {if $fields|contains( 'notificationField' )}
                    {if $fields|contains( 'email' )}
                        <div class="ezcom-field ezcom-field-notified">
                            <label>
                                <input type="checkbox"
                                       name="CommentNotified"
                                       {if $notified}checked="checked"{/if} value="1" />
                                {'Notified of new comments'|i18n( 'ezcomments/comment/add/form' )}
                            </label>
                        </div>
                    {/if}
                {/if}
                <div class="ezcom-field">
                    <input type="submit"
                           value="{'Update comment'|i18n('ezcomments/comment/action' )}"
                           class="button"
                           name="UpdateCommentButton" />
                    <input type="submit" 
                           value="{'Cancel'|i18n('ezcomments/comment/action' )}" 
                           class="button" 
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
</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>