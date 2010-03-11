{def $base_url=ezini( 'SiteSettings', 'SiteURL' )}
{set $base_url=concat( 'http://', $base_url )}
{def $object_url=concat( $base_url, '/', $contentobject.main_node.url_alias, '/(language)/', $contentobject.current_language )}
<p>

{'Hi,'|i18n( 'ezcomments/comment/activationnotification' )}
 <br /><br />
<u>{$comment.name}</u> {'posted a new comment on'|i18n( 'ezcomments/comment/activationnotification' )} <a href="{$object_url}">{$contentobject.name}</a>.
</p>
<p>
<b>{'Comment summary'|i18n( 'ezcomments/comment/activationnotification' )}</b>:<br>
{$comment.text}
<br />
<br />
<p>
{'To reply the content, please visit'|i18n( 'ezcomments/comment/activationnotification' )} 
<a href="{$object_url}">
  {$object_url}
</a>
<br />
{'For setting your subscription, please visit'|i18n( 'ezcomments/comment/activationnotification' )} 
{if $subscriber.hash_string}
    <a href="{concat( $base_url, '/comment/setting/', $subscriber.hash_string )}">
        {concat( $base_url, '/comment/setting/', $subscriber.hash_string )}
    </a>
{else}
    <a href="{concat( $base_url, '/comment/setting' )}">
        {concat( $base_url, '/comment/setting' )}
    </a>
    {'(You need to login)'|i18n( 'ezcomments/comment/activationnotification' )}
{/if}
<br /><br />
</p>

{undef $base_url $object_url}