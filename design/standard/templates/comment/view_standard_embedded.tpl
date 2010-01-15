{def $contentobject_id=$contentobject_attribute.contentobject_id}
{def $language_id=$contentobject_attribute.language_id}
{def $sort_field=ezini( 'GlobalSettings', 'DefaultEmbededSortField', 'ezcomments.ini' )}
{def $sort_order=ezini( 'GlobalSettings', 'DefaultEmbededSortOrder', 'ezcomments.ini' )}
{def $default_shown_length=ezini( 'GlobalSettings', 'DefaultEmbededCount', 'ezcomments.ini' )}

{* Fetch comment count *}
{def $total_count=fetch( 'comment', 'comment_count', hash( 'contentobject_id', $contentobject_id, 'language_id', $language_id ) )}

{* Fetch comments *}
{def $comments=fetch( 'comment', 'comment_list', hash( 'contentobject_id', $contentobject_id, 'language_id', $language_id, 'sort_field', $sort_field, 'sort_order', $sort_order, 'length' ,$default_shown_length ) )}

{* Comment item START *}
{if $comments|count|gt( 0 )}
    {for 0 to $comments|count|sub( 1 ) as $index}
            {include name="CommentItem" comment=$comments.$index index=$index base_index=0 uri="design:comment/view_standard_comment_item.tpl"}
    {/for}
    <div class="ezcomments-comment-view-all">
      {if $total_count|gt( count( $comments ) )}
          <a href={concat( '/comment/view/standard/', $contentobject_id, '/', $language_id )|ezurl}>
            {concat( 'View all %total_count comments' )|i18n( 'extension/ezcomments/view', , hash( '%total_count', $total_count ) )}
          </a>
      {else}
        {'Total %total_count comments'|i18n( 'extension/ezcomments/view', , hash( '%total_count', $total_count ) )}
      {/if}
    </div>
    <br />
{/if}
{* Comment item END *}

{* Adding comment form START *}
{if $contentobject_attribute.data_int}
        {include name="AddComment" uri="design:comment/add_comment.tpl" redirect_uri=$contentobject_attribute.object.main_node.url_alias contentobject_id=$contentobject_id language_id=$language_id}
{/if}
{* Adding comment form END *}

{undef $comments $total_count $default_shown_length $sort_order $sort_field}
{undef $contentobject_id $language_id}