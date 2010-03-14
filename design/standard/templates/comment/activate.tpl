<div>
    {if is_set( $error_message )}
        <div class="message-error">
            <p>
                {$error_message}
            </p>
        </div>
    {else}
        <form action={'/comment/activate'|ezurl} method="post">
                <input type="hidden" name="RedirectURI" value={concat( '/comment/setting/', $subscriber.hash_string )|ezurl( , 'full' )} />
                <div class="message-feedback">
                <p>
                    {'The subscription is activated!'|i18n( 'ezcomments/comment/activate' )}
                </p>
                <input type="submit" name="RedirectButton" value="{'Go to settings'|i18n( 'ezcomments/comment/activate' )}" class="button" />
                </div>
        </form>
    {/if}
</div>