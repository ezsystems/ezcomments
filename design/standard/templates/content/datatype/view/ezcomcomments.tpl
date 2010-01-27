{def $enabled = $attribute.content.enable_comment
     $shown = $attribute.content.show_comments}
{if $shown|eq(1)}
    {ezcss_require( 'comment.css' )}
    {def $defaultMode = ezini( 'GlobalSettings', 'DefaultViewMode', 'ezcomments.ini' )}
    {if $defaultMode|eq('ajax')}
        {if has_access_to_limitation( 'ezjscore', 'call', hash( 'FunctionList', 'ezcomments_enabled' ) )}
            {include name="view_page" contentobject_id=$attribute.contentobject_id enabled=$enabled language_id=$attribute.language_id uri="design:comment/ajax/view_main.tpl"}
        {/if}
     {elseif $defaultMode|eq('standard')}
            {include name="view_page" contentobject_attribute=$attribute uri="design:comment/view/view_embedded.tpl"}
     {else}
     {/if}
     {undef $defaultMode}
{/if}
{undef $enabled $shown}
