{ezcss_require( 'comment.css' )}
<div class="ezcom-add-result">
  {if or( is_set($error_message), is_set( $validation_messages ) )}
    <div class="message-error">
        <p>
            {$error_message}
        </p>

        {foreach $validation_messages as $field => $message }
            <p><strong>{$field}:</strong><br /> {$message}</p>
        {/foreach}
        
        <input type="button" onclick="history.back();" class="button" value="{'Back'|i18n( 'view/action' )}" />
    </div>
  {/if}
  {if and( is_set( $success ), $success|eq( 1 ) )}
    <div class="message-error">
        <p>
            {'Posting succeeds!'|i18n( 'extension/ezcomments/add' )}
        </p>
        <form action="{'comment/add'|ezurl}" method="get" name="CommentAdded">
            <input type="hidden" name="RedirectURI" value={$redirect_uri} />
            <input type="submit" class="button" name="BackButton" value="{'Back'|i18n( 'extension/ezcomments/action') }" />
        </form>
    </div>
  {/if}
</div>