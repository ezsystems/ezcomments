{def $enabled = $attribute.data_int $shown = $attribute.data_float}

{* Default enabled in database is null. 
if data_int equals 1, then enable commenting,
 if data_int equals 0, then disable commenting,
 if data_int equals null, follow default setting *}

{if $enabled|is_null()}
    {if ezini( 'GlobalSettings', 'DefaultEnabled', 'ezcomments.ini' )|eq( 'true' )}
        <input type="checkbox" id="ezcomcomment_enabled_{$attribute.id}" name="{$attribute_base}_ezcomcomments_enabled_{$attribute.id}" checked="true" />
    {else}
        <input type="checkbox" id="ezcomcomment_enabled_{$attribute.id}" name="{$attribute_base}_ezcomcomments_enabled_{$attribute.id}" />
    {/if} 
{else}
    {if $enabled|eq( 1 )}
        <input type="checkbox" id="ezcomcomment_enabled_{$attribute.id}" name="{$attribute_base}_ezcomcomments_enabled_{$attribute.id}" checked="true" />
    {elseif $enabled|eq( 0 )}
        <input type="checkbox" id="ezcomcomment_enabled_{$attribute.id}" name="{$attribute_base}_ezcomcomments_enabled_{$attribute.id}" />
    {/if}
{/if}
Enable commenting

{* Default shown in database is 0. 
if data_float equals 1, then show comments,
 if data_float equals -1, then not show comments,
 if data_float equals 0 or others, follow default setting *}

{if $shown|eq( 1 )}
    <input type="checkbox" id="ezcomcomment_shown_{$attribute.id}" name="{$attribute_base}_ezcomcomments_shown_{$attribute.id}" checked="true" />
{elseif $shown|eq( -1 )}
    <input type="checkbox" id="ezcomcomment_shown_{$attribute.id}" name="{$attribute_base}_ezcomcomments_shown_{$attribute.id}" />
{else}
    {if ezini( 'GlobalSettings', 'DefaultShown', 'ezcomments.ini' )|eq( 'true' )}
        <input type="checkbox" id="ezcomcomment_shown_{$attribute.id}" name="{$attribute_base}_ezcomcomments_shown_{$attribute.id}" checked="true" />
    {else}
        <input type="checkbox" id="ezcomcomment_shown_{$attribute.id}" name="{$attribute_base}_ezcomcomments_shown_{$attribute.id}" />
    {/if}
{/if}
Show comments
{undef $enabled}