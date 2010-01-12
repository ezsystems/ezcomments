{def $enabled = $attribute.data_int $shown = $attribute.data_float}
{if $shown|eq(1)}
    {ezcss_require( 'content_view.css' )}
    {def $defaultMode = ezini( 'ezcommentsSettings', 'DefaultViewMode', 'ezcomments.ini' )}
    {if $defaultMode|eq('ajax')}
        {if has_access_to_limitation( 'ezjscore', 'call', hash( 'FunctionList', 'ezcomments_enabled' ) )}
            {include name="view_page" contentobject_id=$attribute.contentobject_id enabled=$enabled language_id=$attribute.language_id uri="design:comment/view_main.tpl"}
        {/if}
     {elseif $defaultMode|eq('standard')}
            {include name="view_page" contentobject_attribute=$attribute uri="design:comment/view_standard_embedded.tpl"}
     {else}
     {/if}
     {undef $defaultMode}
{/if}
{undef $enabled $shown}
