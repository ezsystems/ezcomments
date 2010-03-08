{* Adding comment START *}
{def $user=fetch( 'user', 'current_user' )}
{def $anonymous_user_id=ezini('UserSettings', 'AnonymousUserID' )}
{def $is_anonymous=$user.contentobject_id|eq( $anonymous_user_id )}
{def $comment_notified=ezini( 'GlobalSettings', 'EnableNotification', 'ezcomments.ini' )}
{def $fields = ezini( 'FormSettings', 'AvailableFields', 'ezcomments.ini' )}
{def $fieldRequiredText = '<span class="ezcom-field-mandatory">*</span>'}

<form id="ezcom-comment-form" method="post" action={'comment/add'|ezurl} name="CommentAdd">
<input type="hidden" name="ContentObjectID" value="{$contentobject_id}" />
<input type="hidden" name="CommentLanguageID" value="{$language_code}" />
<input type="hidden" name="RedirectURI" value={$redirect_uri} />

<div class="ezcom-add" id="ezcomments_comment_view_addcomment">
        <div class="ezcom-function-title">
            <h4>
             {'Post comment'|i18n( 'ezcomments/comment/add/form' )}
            </h4>
        </div>

        {if $fields|contains( 'title' )}
        {def $titleRequired = ezini( 'title', 'Required', 'ezcomments.ini' )|eq( 'true' )}
            <div class="ezcom-field ezcom-field-title">
                <label>
                    {'Title:'|i18n( 'ezcomments/comment/add/form' )}{if $titleRequired}{$fieldRequiredText}{/if}
                </label>
                <input type="text" class="box" maxlength="100" id="ezcomments_comment_view_addcomment_title" name="CommentTitle" />
            </div>
        {undef $titleRequired}
        {/if}

        {if $fields|contains( 'name' )}
        {def $nameRequired = ezini( 'name', 'Required', 'ezcomments.ini' )|eq( 'true' )}
            <div class="ezcom-field ezcom-field-name">
                <div class="ezcom-filed-error"></div>
                <label>
                    {'Name:'|i18n( 'ezcomments/comment/add/form' )}{if $nameRequired}{$fieldRequiredText}{/if}
                </label>
                <input type="text" class="box" maxlength="50" id="ezcomments_comment_view_addcomment_name" name="CommentName" />
            </div>
        {undef $nameRequired}
        {/if}

        {if $fields|contains( 'website' )}
        {def $websiteRequired = ezini( 'website', 'Required', 'ezcomments.ini' )|eq( 'true' )}
            <div class="ezcom-field ezcom-field-website">
                <label>
                    {'Website:'|i18n( 'ezcomments/comment/add/form' )}{if $websiteRequired}{$fieldRequiredText}{/if}
                </label>
                <input type="text" class="box" maxlength="100" id="ezcomments_comment_view_addcomment_website" name="CommentWebsite" />
            </div>
        {undef $websiteRequired}
        {/if}

        {if $fields|contains( 'email' )}
        {def $emailRequired = ezini( 'email', 'Required', 'ezcomments.ini' )|eq( 'true' )}
            <div class="ezcom-field ezcom-field-email">
                <label>
                    {'Email:'|i18n( 'ezcomments/comment/add/form' )}{if $emailRequired}{$fieldRequiredText}{/if}&nbsp;<span class="ezcom-field-emailmessage">{'(The email address will not be shown)'|i18n( 'ezcomments/comment/add/form' )}</span>
                </label>
                {if $is_anonymous|not}
                    <input type="text" maxlength="100" class="box" id="ezcomments_comment_view_addcomment_email" disabled="true" />
                    <input type="hidden" name="CommentEmail" />
                {else}
                    <input type="text" maxlength="100" class="box" id="ezcomments_comment_view_addcomment_email" name="CommentEmail" />
                {/if} 
            </div>
        {undef $emailRequired}
        {/if}

        <div class="ezcom-field ezcom-field-content">
            <label>
                {'Content:'|i18n( 'ezcomments/comment/add/form' )}{$fieldRequiredText}
            </label>
            <textarea id="ezcomments_comment_view_addcomment_content" class="box" name="CommentContent"></textarea>
        </div>

        {if $fields|contains( 'notificationField' )}
            {* When email is enabled or email is enabled but user logged in *}
            {if or( $fields|contains( 'email' ), and( $fields|contains( 'email' )|not, $is_anonymous|not ) )}
                <div class="ezcom-field ezcom-field-notified">
                    <label>
                        <input type="checkbox" id="ezcom_field_notified" name="CommentNotified" {if $comment_notified|eq('true')}checked="checked"{/if} />
                        {'Notify me of new comments'|i18n( 'ezcomments/comment/add/form' )}
                    </label>
                </div>
            {/if}
        {/if}
        {if $is_anonymous}
            <div class="ezcom-field ezcom-field-remember">
                <label>
                    <input type="checkbox" name="CommentRememberme" checked="checked" />
                    {'Remember me'|i18n( 'ezcomments/comment/add/form' )}
                </label>
            </div>
        {/if}
        <div class="ezcom-field">
            <input type="submit" value="{'Add comment'|i18n( 'ezcomments/comment/add/form' )}" class="button" id="ezcom-post-button" name="AddCommentButton" />
            <input type="reset" class="button" value="{'Reset form'|i18n( 'ezcomments/comment/add/form' )}" />
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
                    sortorder: '{ezini('GlobalSettings', 'DefaultEmbededSortOrder', 'ezcomments.ini')}',
                    fields: {ldelim} 
                                name: '#ezcomments_comment_view_addcomment_name',
                                email: '#ezcomments_comment_view_addcomment_email' 
                            {rdelim}
                 {rdelim}

eZComments.init();
</script>

{undef $comment_notified $fields}
{undef $user $anonymous_user_id $is_anonymous}
{* Adding comment END *}