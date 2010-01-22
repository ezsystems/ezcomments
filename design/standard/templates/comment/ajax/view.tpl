{if is_set( $contentobject_id )}
    {if $shown|eq( 1 )}
        {ezcss_require( 'view.css' )}
        {if has_access_to_limitation( 'ezjscore', 'call', hash( 'FunctionList', 'ezcomments_enabled' ) )}
            {include name="CommentContent" contentobject=$contentobject uri="design:comment/view_content.tpl"}
            {include name="view_page" contentobject_id=$contentobject_id language_id=$language_id enabled=$enabled uri="design:comment/ajax/view_main.tpl"}
        {/if}
    {/if}
{/if}