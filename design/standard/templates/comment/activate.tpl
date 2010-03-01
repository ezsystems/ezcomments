<div>
    {if is_set( $error_message )}
            {$error_message}
    {else}
            <form action="comment/activate" method="post">
                    <input type="hidden" name="RedirectURI" value={concat( '/comment/setting/', $subscriber.hash_string )} />
                    <div class="message-feedback">
                    <p>
                        {'The subscription is activated!'|i18n( 'ezcomments/comment/activate' )}
                    </p>
                    <input type="submit" name="RedirectButton" value="{'Go to settings'|i18n( 'ezcomments/comment/activate' )}" class="button" />
                    </div>
            </form>
    {/if}
</div>