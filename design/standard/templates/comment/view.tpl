{if is_set( $contentobject_id )}
{if $shown|eq( 1 )}
{ezcss_require( 'view.css' )}
{if has_access_to_limitation( 'ezjscore', 'call', hash( 'FunctionList', 'ezcomments_enabled' ) )}
<p>
<h2>
<a href={$contentobject.main_node.url_alias|ezurl}>{$contentobject.name}</a>
</h2>
</p>
{include name="view_page" contentobject_id=$contentobject_id language_id=$language_id enabled=$enabled uri="design:comment/view_main.tpl"}
{/if}
{/if}
{/if}