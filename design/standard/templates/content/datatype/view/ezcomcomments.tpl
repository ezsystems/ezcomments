{def $enabled = $attribute.data_int $shown = $attribute.data_float}
{if $shown|eq(1)}
{ezcss_require( 'content_view.css' )}
{if has_access_to_limitation( 'ezjscore', 'call', hash( 'FunctionList', 'ezcomments_enabled' ) )}
{include name="view_page" contentobject_id=$attribute.contentobject_id enabled=$enabled language_id=$attribute.language_id uri="design:comment/view_main.tpl"}
{/if}
{/if}
{undef $enabled $shown}
