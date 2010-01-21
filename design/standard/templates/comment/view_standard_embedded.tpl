{if $contentobject_attribute.data_float}
    {def $contentobject=$contentobject_attribute.object}
    {def $language_id=$contentobject_attribute.language_id}
    {def $language_code=$contentobject_attribute.language_code}
    {def $can_read = fetch( 'comment', 'has_access_to_function', hash( 'function', 'read',
                                                                       'contentobject', $contentobject,
                                                                       'language_code', $language_code,
                                                                        ) )}
    {if $can_read}
        {def $sort_field=ezini( 'GlobalSettings', 'DefaultEmbededSortField', 'ezcomments.ini' )}
        {def $sort_order=ezini( 'GlobalSettings', 'DefaultEmbededSortOrder', 'ezcomments.ini' )}
        {def $default_shown_length=ezini( 'GlobalSettings', 'DefaultEmbededCount', 'ezcomments.ini' )}

        {* Fetch comment count *}
        {def $total_count=fetch( 'comment', 'comment_count', hash( 'contentobject_id', $contentobject.id, 'language_id', $language_id ) )}
        
        {* Fetch comments *}
        {def $comments=fetch( 'comment', 'comment_list', hash( 'contentobject_id', $contentobject.id, 'language_id', $language_id, 'sort_field', $sort_field, 'sort_order', $sort_order, 'length' ,$default_shown_length ) )}
        
        {* Comment item START *}
        {if $comments|count|gt( 0 )}
            {for 0 to $comments|count|sub( 1 ) as $index}
                    {include name="CommentItem" comment=$comments.$index index=$index base_index=0 uri="design:comment/view_standard_comment_item.tpl"}
            {/for}
            <div class="ezcomments-comment-view-all">
              {if $total_count|gt( count( $comments ) )}
                  <a href={concat( '/comment/view/standard/', $contentobject.id, '/', $language_id )|ezurl}>
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
            {def $can_add = fetch( 'comment', 'has_access_to_function', hash( 'function', 'add',
                                                                           'contentobject', $contentobject,
                                                                           'language_code', $contentobject_attribute.language_code,
                                                                            ) )}
            {if $can_add}
                {include name="AddComment" uri="design:comment/add_comment.tpl" redirect_uri=$contentobject_attribute.object.main_node.url_alias contentobject_id=$contentobject.id language_id=$language_id}
            {else}
                <div class="ezcomments-comment-view-no-permission">
                        <p>
                            {'You don\'t have access to post comment here!'|i18n( 'extension/ezcomments/view' )}
                        </p>
                </div>
            {/if}
            {undef $can_add}
        {/if}
        {* Adding comment form END *}
        
        {undef $comments $total_count $default_shown_length $sort_order $sort_field}
    {else}
        <div class="ezcomments-comment-view-no-permission">
                <p>
                    {'You don\'t have access to view comment here!'|i18n( 'extension/ezcomments/view' )}
                </p>
        </div>
    {/if}
    {undef $can_read}
    {undef $contentobject $language_id $language_code}
{/if}