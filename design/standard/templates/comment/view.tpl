{if $shown|eq(1)}
{ezcss_require( 'view.css' )}
{include name="view_page" contentobject_id=$contentobject_id enabled=$enabled uri="design:comment/view_main.tpl"}
{/if}