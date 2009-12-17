{def $enabled = $attribute.data_int $shown = $attribute.data_float}
{if $shown|eq(1)}
{ezcss_require( 'content_view.css' )}
{include name="view_page" contentobject_id=$attribute.contentobject_id enabled=$enabled uri="design:comment/view_main.tpl"}
{/if}
{undef $enabled $shown}