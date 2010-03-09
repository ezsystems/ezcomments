{def $base_url=ezini( 'SiteSettings', 'SiteURL' )}
{set $base_url=concat( 'http://', $base_url )}
<p>

Hi,
 <br />
<u>{$comment.name}</u> posted a new comment on <a href="{$content_object.main_node.url_alias}">{$contentobject.name}. </a>
</p>
<p>
<b>Comment summary</b>:<br>
{$comment.text}
<br />
<br />
<p>
To reply the content, please visit 
<a href="{concat( $base_url, '/', $content_object.main_node.url_alias )}">
  {concat( $base_url, '/', $content_object.main_node.url_alias )}
</a>
<br />
For setting your subscription, please visit 
<a href="{concat( $base_url, '/comment/setting/', $subscriber.hash_string )}">
    {concat( $base_url, '/comment/setting/', $subscriber.hash_string )}
</a>
<br /><br />
</p>

{undef $base_url}