{def $enabled=$attribute.content.enable_comment $shown=$attribute.content.show_comments}
<input type="checkbox" id="ezcomcomment_shown_{$attribute.id}" name="{$attribute_base}_ezcomcomments_shown_{$attribute.id}" {if $shown}checked="true"{/if} />
 {'Show comments'|i18n( 'ezcomments/datatype/edit' )}
 <input type="checkbox" id="ezcomcomment_enabled_{$attribute.id}" name="{$attribute_base}_ezcomcomments_enabled_{$attribute.id}" {if $enabled}checked="true"{/if} />
{'Enable commenting'|i18n( 'ezcomments/datatype/edit' )}
{undef $shown $enabled}