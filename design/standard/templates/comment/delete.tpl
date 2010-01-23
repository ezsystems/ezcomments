{ezcss_require( 'comment.css' )}
<form action="{'/comment/delete'|ezurl}" method="post" name="CommentDelete">
    <div class="ezcom-delete" >
        <input type="hidden" name="CommentID" value="{$comment_id}" />
        <input type="hidden" name="RedirectURI" value={$redirect_uri} />
        {if is_set($error_message)}
            <div class="error-message">
                <p>
                    {$error_message}
                </p>
            </div>
        {/if}
        <div class="message-confirmation" id="ezcomments_comment_delete">
            <p>
                {'Delete comment?'|i18n( 'extension/ezcomments/delete' )}
            </p>
            <input type="submit" value="{'Delete'|i18n( 'extension/ezcomments/action' )}" class="button" name="DeleteCommentButton" />
            <input type="submit" value="{'Cancel'|i18n( 'extension/ezcomments/action' )}" class="button" name="CancelButton" />
        </div>
    </div>
</form>