{def $enabled = $attribute.data_int}
{if $enabled|is_null()}
    {if ezini( 'ezcomCommentSettings', 'DefaultEnabled', 'ezcomcomment.ini' )|eq( 'true' )}
        <input type="checkbox" id="ezcomcomment_enabled_{$attribute.id}" name="{$attribute_base}_ezcomcomment_enabled_{$attribute.id}" checked="true" />
    {else}
        <input type="checkbox" id="ezcomcomment_enabled_{$attribute.id}" name="{$attribute_base}_ezcomcomment_enabled_{$attribute.id}" />
    {/if} 
{else}
    {if $enabled|eq( 1 )}
        <input type="checkbox" id="ezcomcomment_enabled_{$attribute.id}" name="{$attribute_base}_ezcomcomment_enabled_{$attribute.id}" checked="true" />
    {elseif $enabled|eq( 0 )}
        <input type="checkbox" id="ezcomcomment_enabled_{$attribute.id}" name="{$attribute_base}_ezcomcomment_enabled_{$attribute.id}" />
    {/if}
{/if}
Enable Commenting
{undef $enabled}