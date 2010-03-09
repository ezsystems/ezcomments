{def $base_url=ezini( 'SiteSettings', 'SiteURL' )}
{set $base_url=concat( 'http://', $base_url )}
<p>

{'Hi,'|i18n( 'ezcomments/comment/activationnotification' )} 
<br />
<p>
{'There are updates from'|i18n( 'ezcomments/comment/activationnotification' )} <a href={$contentobject.main_node.url_alias}>{$contentobject.name}. </a>
</p>
<br />
<p>
{'To reply the content, please visit'|i18n( 'ezcomments/comment/activationnotification' )} 
<a href="{concat( $base_url, '/', $contentobject.main_node.url_alias )}">
  {concat( $base_url, '/', $contentobject.main_node.url_alias )}
</a>
<br />
{'For setting your subscription, please visit'|i18n( 'ezcomments/comment/activationnotification' )} 
<a href="{concat( $base_url, '/comment/setting/', $subscriber.hash_string )}">
    {concat( $base_url, '/comment/setting/', $subscriber.hash_string )}
</a>
<br /><br />
</p>

{undef $base_url}