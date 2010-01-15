{ezcss_require( 'view.css' )}
{if $objectattribute.data_float|eq(1)}
{*outputting content starts*}
<h2>
    <a href={$contentobject.main_node.url_alias|ezurl}>{$contentobject.name}</a>
</h2>
{*outputting content ends*}

{*outputting pages starts*}
    {def $page_prefix=concat( "comment/view/standard/", $contentobject.id, "/", $language_id ,"/")}
    <div id="ezcomments_comment_view_page" class="ezcomments-comment-view-page">
       <p>
         <span>{'Total comments:'|i18n( 'extension/ezcomments/view/page' )}</span>
         <span>{$total_count}</span> <span>, </span>
         <span>{'Pages:'|i18n( 'extension/ezcomments/view/page' )}</span>
         {if $current_page|gt( 1 )}
             <span>
              <a title="{"Previous page"|i18n( 'extension/ezcomments/view/page' )}" href={concat( $page_prefix ,$current_page|sub( 1 ) )|ezurl()}>
               <
              </a> &nbsp;
             </span>
         {/if}
         {def $pageLength=2}
         {if $current_page|sub( $pageLength )|le( 1 )}
            {if $current_page|sub( 1 )|gt( 0 )}
                {for 1 to $current_page|sub( 1 ) as $page_item}
                    <a href={concat( $page_prefix, $page_item )|ezurl()}>{$page_item}</a>
                {/for}
            {/if}
         {else}
            <a href={concat( $page_prefix,"1" )|ezurl()}>1</a>
            {if $current_page|sub( $pageLength )|gt( 2 )}
            ...
            {/if}
            {for $current_page|sub( $pageLength ) to $current_page|sub( 1 ) as $page_item}
                <a href={concat( $page_prefix, $page_item )|ezurl()}>{$page_item}</a>
            {/for}
         {/if}
         {$current_page}
         {if $current_page|sum( $pageLength )|ge( $total_page )}
             {if $current_page|sum( 1 )|le( $total_page )}
                 {for $current_page|sum( 1 ) to $total_page as $page_item}
                    <a href={concat( $page_prefix, $page_item )|ezurl()}>{$page_item}</a>
                 {/for}
             {/if}
         {else}
            {for $current_page|sum( 1 ) to $current_page|sum( $pageLength ) as $page_item}
                <a href={concat( $page_prefix, $page_item )|ezurl()}>{$page_item}</a>
            {/for}
            {if $total_page|sub( $current_page, $pageLength )|gt( 1 )}
            ...
            {/if}
            <a href={concat( $page_prefix,$total_page )|ezurl()}>{$total_page}</a>
         {/if}
         
         {undef $pageLength}
         {*
         {for 1 to $total_page as $page_item}
            {if $page_item|eq($current_page)}
                {$page_item}
            {else}
                <a href={concat( $page_prefix,$page_item )|ezurl()}>{$page_item}</a>
            {/if}
         {/for}
         *}
         {if $current_page|lt($total_page)}
             <span>
              <a title="{"Next page"|i18n('extension/ezcomments/view/page')}" href={concat( $page_prefix,$current_page|sum( 1 ) )|ezurl()}>
               >
              </a> 
             </span>
         {/if}
         &nbsp;
       </p>
     </div>
    {undef $page_prefix}
    {* outputting comments starts*}
    {for 0 to $comments|count|sub( 1 ) as $index}
        {include name="CommentItem" comment=$comments.$index index=$index base_index=$current_page|sub( 1 )|mul( $number_per_page ) uri="design:comment/view_standard_comment_item.tpl"}
    {/for}
    {* outputting comments ends *}
    
    {* adding comment form *}
    {if $objectattribute.data_int}
        {include name="AddComment" uri="design:comment/add_comment.tpl" redirect_uri=concat('comment/view/standard/',$contentobject.id,'/',$language_id) contentobject_id=$contentobject.id language_id=$language_id}
    {/if}
    {* adding comment ends *}
{/if}