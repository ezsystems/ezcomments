{*1.fetch the comment*}
{*fetch the count*}
{def $contentobject_id=$contentobject_attribute.contentobject_id}
{def $language_id=$contentobject_attribute.language_id}
{def $sortField = ezini( 'ezcommentsSettings', 'DefaultEmbededSortField', 'ezcomments.ini' )}
{def $sortOrder = ezini( 'ezcommentsSettings', 'DefaultEmbededSortOrder', 'ezcomments.ini' )}
{def $length = ezini( 'ezcommentsSettings', 'DefaultEmbededCount', 'ezcomments.ini' )}

{def $total_count=fetch( comment, comment_count, hash( 'contentobject_id', $contentobject_id, 'language_id', $language_id ) )}
{*fetch the comments*}
{def $comments=fetch( comment, comment_list, hash( 'contentobject_id', $contentobject_id, 'language_id', $language_id, 'sort_field', $sortField, 'sort_order', $sortOrder, 'length' ,$length ) )}

{*2.render the comment*}
{if $comments|count|gt(0)}
    {for 0 to $comments|count|sub( 1 ) as $index}
            {include name="comment_item" comment=$comments.$index index=$index base_index=0 uri="design:comment/view_standard_comment_item.tpl"}
    {/for}
    <div class="ezcomments-comment-view-all">
      {if $total_count|gt(count($comments))}
          <a href={concat('/comment/view/standard/',$contentobject_id,'/',$language_id)|ezurl}>
            {concat('View all %total_count comments')|i18n('extension/ezcomments/view',,hash( '%total_count', $total_count ))}
          </a>
      {else}    
            {'Total %total_count comments'|i18n('extension/ezcomments/view',, hash( '%total_count', $total_count ))}
      {/if}
    </div>
{/if}
{undef $comments $total_count $length $sortOrder $sortField}
{*3.show adding comment form*}