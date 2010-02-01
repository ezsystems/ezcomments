<div>
    {if is_set( $error_message )}
            {$error_message|i18n( 'extension/ezcomments/activate' )}
    {else}
            <form action="comment/activate" method="post">
                    <input type="hidden" name="RedirectURI" value={concat( '/comment/setting/', $subscriber.hash_string )} />
                    <div class="message-feedback">
                    <p>
                        {'The subscription was activated!'|i18n( 'extension/ezcomments/activate' )}
                    </p>
                    <input type="submit" name="RedirectButton" value="{'Go to settings'|i18n( 'extension/ezcomments/activate' )}" class="button" />
                    </div>
            </form>
    {/if}
</div>