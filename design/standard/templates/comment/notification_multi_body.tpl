{def $base_url=ezini( 'SiteSettings', 'SiteURL' )}
{set $base_url=concat( 'http://', $base_url )}
<p>

Hi, 
<br />
<p>
There are updates from <a href={$contentobject.main_node.url_alias}>{$contentobject.name}. </a>
</p>
<br />
<p>
For reply the content, please visit 
<a href="{concat( $base_url, '/', $contentobject.main_node.url_alias )}">
  {concat( $base_url, '/', $contentobject.main_node.url_alias )}
</a>
<br />
For setting your subscription, please visit 
<a href="{concat( $base_url, '/comment/setting/', $subscriber.hash_string )}">
    {concat( $base_url, '/comment/setting/', $subscriber.hash_string )}
</a>
<br /><br />
Email sent to {$subscriber.email}
</p>

{undef $base_url}