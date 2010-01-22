{ezcss_require( 'view.css' )}
<div class="ezcomments-comment-view-addcomment-message">
  {if is_set($error_message)}
    {$error_message}
    <input type="button" onclick="history.back();" class="button" value="{'Back'|i18n( 'view/action' )}" />
  {/if}
  {if $success|eq( 1 )}
    {'Posting succeeds!'|i18n( 'extension/ezcomments/add' )}
    <form action="{'comment/add'|ezurl}" method="post" name="CommentAdded">
        <input type="hidden" name="RedirectURI" value="{$redirect_uri}" />
        <input type="submit" class="button" name="BackButton" value="{'Back'|i18n( 'extension/ezcomments/action') }" />
    </form>
  {/if}
</div>