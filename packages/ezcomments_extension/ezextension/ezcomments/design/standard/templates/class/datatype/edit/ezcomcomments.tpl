{def $fields = ezini( 'FormSettings', 'AvailableFields', 'ezcomments.ini' )}
{if $fields|contains( 'recaptcha' )}
{def $public_key = ezini( 'RecaptchaSetting', 'PublicKey', 'ezcomments.ini' )
     $private_key = ezini( 'RecaptchaSetting', 'PrivateKey', 'ezcomments.ini' )}
        {if or( $public_key|eq( '' ), $private_key|eq( '' ) )}
    <div class="message-warning">
            <h4>{'eZ Comments warning: the reCAPTCHA key is not set up properly.'|i18n( 'ezcomments/class/edit' )}</h4>
            <p>
                {'Please get a reCAPTCHA key from <a href="https://www.google.com/recaptcha/admin/create" target="_blank">https://www.google.com/recaptcha/admin/create</a> then set it up in eZ Comments, or disable CAPTCHA feature.'|i18n( 'ezcomments/class/edit')}<br />
                {'For more details please visit <a href="http://projects.ez.no/ezcomments" target="_blank">http://projects.ez.no/ezcomments</a>.'|i18n( 'ezcomments/class/edit' )}
            </p>
    </div>
        {/if}

{undef $public_key $private_key}
{/if}
{undef $fields}