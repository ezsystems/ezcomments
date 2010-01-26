{def $base_url=ezini( 'SiteSettings', 'SiteURL' )}
{let $base_url=concat( 'http://', $base_url )}
<p>
Dear {$subscriber.email} 
<br />
<p>
There are updates from <a href={$contentobject.main_node.url_alias}>{$contentobject.name}. </a>
</p>
<br />
<p>
For reply the content, please visit 
<a href="{concat( $base_url, '/', $content_object.main_node.url_alias )}">
  {concat( $base_url, '/', $content_object.main_node.url_alias )}
</a>
<br />
For setting your subscription, please visit 
<a href="{concat( $base_url, '/comment/setting/', $subscriber.hash_string )}">
    {concat( $base_url, '/comment/setting/', $subscriber.hash_string )}
</a>
</p>
<pre>
-----------------------------
Best Regards
eZ Systems
<a href="http://ez.no">http://ez.no</a>
</pre>
{undef $base_url}