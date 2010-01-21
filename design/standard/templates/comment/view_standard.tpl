{if $objectattribute.data_float}
{ezcss_require( 'view.css' )}
    {include name="CommentContent" contentobject=$contentobject uri="design:comment/view_standard_content.tpl"}
    
    {include name="CommentPage" contentobject=$contentobject language_id=$language_id total_count=$total_count total_page=$total_page current_page=$current_page uri="design:comment/view_standard_page.tpl"}
    
    {if $comments|count|gt( 0 )}
        {for 0 to $comments|count|sub( 1 ) as $index}
            {include name="CommentItem" comment=$comments.$index index=$index base_index=$current_page|sub( 1 )|mul( $number_per_page ) uri="design:comment/view_standard_comment_item.tpl"}
        {/for}
    {else}
        <div class="ezcomments-comment-view-nocomment">
            <p>
                {'There is no comment!'|i18n( 'extension/ezcomments/view' )}
            </p>
        </div>
    {/if}
    {if $objectattribute.data_int}
        {def $can_add = fetch( 'comment', 'has_access_to_function', hash( 'function', 'add',
                                                                           'contentobject', $contentobject,
                                                                           'language_code', $language_code,
                                                                            ) )}
        {if $can_add}
            {include name="AddComment" uri="design:comment/add_comment.tpl" redirect_uri=concat( 'comment/view/standard/', $contentobject.id, '/', $language_id ) contentobject_id=$contentobject.id language_id=$language_id}
        {else}
                <div class="ezcomments-comment-view-no-permission">
                        <p>
                            {'You don\'t have access to post comment here!'|i18n( 'extension/ezcomments/view' )}
                        </p>
                </div>
        {/if}
        {undef $can_add}
    {/if}
{/if}