{ezcss_require( 'delete.css' )}
<form action="{'/comment/delete'|ezurl}" method="post">
    <input type="hidden" name="ezcomments_comment_delete_commentid" value="{$comment_id}" />
    <input type="hidden" name="ezcomments_comment_delete_redirecturi" value={$redirect_uri} />
    {if is_set($error_message)}<div>{$error_message}</div>{/if}
    <div class="ezcomments-comment-delete" id="ezcomments_comment_delete">
        <div class="ezcomments-comment-delete-message">{'Delete comment?'|i18n('extension/ezcomments/delete')}</div>
        <div>
            <input type="submit" value="{'Delete'|i18n('extension/ezcomments/action')}" class="button" name="DeleteCommentButton"/>
            <input type="submit" value="{'Cancel'|i18n('extension/ezcomments/action')}" class="button" name="CancelButton"/>
        </div>
    </div>
</form>