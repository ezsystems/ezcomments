{*1.fetch the comment*}
{*fetch the count*}
{def $contentobject_id=$contentobject_attribute.contentobject_id}
{def $language_id=$contentobject_attribute.language_id}
{def $sortField = ezini( 'GlobalSettings', 'DefaultEmbededSortField', 'ezcomments.ini' )}
{def $sortOrder = ezini( 'GlobalSettings', 'DefaultEmbededSortOrder', 'ezcomments.ini' )}
{def $length = ezini( 'GlobalSettings', 'DefaultEmbededCount', 'ezcomments.ini' )}

{def $total_count=fetch( comment, comment_count, hash( 'contentobject_id', $contentobject_id, 'language_id', $language_id ) )}
{*fetch the comments*}
{def $comments=fetch( comment, comment_list, hash( 'contentobject_id', $contentobject_id, 'language_id', $language_id, 'sort_field', $sortField, 'sort_order', $sortOrder, 'length' ,$length ) )}

{* rendering the comment starts *}
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
    <br />
{/if}
{* rendering the comment ends *}

{* adding comment form starts *}
{if $contentobject_attribute.data_int}
        {include name="AddComment" uri="design:comment/add_comment.tpl" redirect_uri=$contentobject_attribute.object.main_node.url_alias contentobject_id=$contentobject_id language_id=$language_id}
{/if}
{* adding comment form ends *}

{undef $comments $total_count $length $sortOrder $sortField}
{undef $contentobject_id $language_id}