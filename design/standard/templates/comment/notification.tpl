<p>
<u>{$comment.name}</u> posted a new comment on <a href="">{$content_object.name}. </a>
</p>
<p>
<b>Comment summary</b>:<br>
{$comment.text}
<br />
<br />
For reply the content, please visit {$content_object.main_node.url_alias|ezurl(,'full')}
For setting your subscription, please visit {'/comment/setting'|ezurl(,'full')}

</p>
<pre>
-----------------------------
Thank you for choosing eZ Publish!
Best Regards
eZ Systems
<a href="http://ez.no">http://ez.no</a>
</pre>