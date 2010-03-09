{def $base_url=ezini( 'SiteSettings', 'SiteURL' )}
{set $base_url=concat( 'http://', $base_url )}
<p>

{'Hi,'|i18n( 'ezcomments/comment/activationnotification' )}
 <br />
<u>{$comment.name}</u> {'posted a new comment on'|i18n( 'ezcomments/comment/activationnotification' )} <a href="{$content_object.main_node.url_alias}">{$contentobject.name}. </a>
</p>
<p>
<b>{'Comment summary'|i18n( 'ezcomments/comment/activationnotification' )}</b>:<br>
{$comment.text}
<br />
<br />
<p>
{'To reply the content, please visit'|i18n( 'ezcomments/comment/activationnotification' )} 
<a href="{concat( $base_url, '/', $content_object.main_node.url_alias )}">
  {concat( $base_url, '/', $content_object.main_node.url_alias )}
</a>
<br />
{'For setting your subscription, please visit'|i18n( 'ezcomments/comment/activationnotification' )} 
<a href="{concat( $base_url, '/comment/setting/', $subscriber.hash_string )}">
    {concat( $base_url, '/comment/setting/', $subscriber.hash_string )}
</a>
<br /><br />
</p>

{undef $base_url}