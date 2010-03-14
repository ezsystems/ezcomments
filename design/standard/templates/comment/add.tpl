{ezcss_require( 'comment.css' )}
<div class="ezcom-add-result">
  {if is_set($error_message)}
    <div class="message-error">
        <p>
            {$error_message}
        </p>

        {if is_set( $validation_messages )}
            {foreach $validation_messages as $field => $message }
                <p><strong>{$field}:</strong><br /> {$message}</p>
            {/foreach}
        {/if}
        
        <input type="button" onclick="history.back();" class="button" value="{'Back'|i18n( 'ezcomments/comment/view/action' )}" />
    </div>
  {/if}
  {if and( is_set( $success ), $success|eq( 1 ) )}
    <div class="message-feedback">
        <p>
            {'Your comment has been posted.'|i18n( 'ezcomments/comment/add' )}
        </p>
        {if is_set( $success_message )}
        <p>
            {$success_message}
        </p>
        {/if}
        <form action={'comment/add'|ezurl} method="get" name="CommentAdded">
            <input type="hidden" name="RedirectURI" value="{$redirect_uri}" />
            <input type="submit" class="button" name="BackButton" value="{'Back'|i18n( 'ezcomments/comment/action') }" />
        </form>
    </div>
  {/if}
</div>