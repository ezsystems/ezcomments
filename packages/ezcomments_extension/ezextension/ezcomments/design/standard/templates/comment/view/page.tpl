{def $page_prefix=concat( 'comment/view/', $contentobject.id, '/' )}
{if $total_count|gt( 0 )}
    <div id="ezcomments_comment_view_page" class="ezcom-page">
         <span>{'Total comments:'|i18n( 'ezcomments/comment/view/page' )}</span>
         <span>{$total_count}</span><span>, </span>
         <span>{'Pages:'|i18n( 'ezcomments/comment/view/page' )}</span>
         {if $current_page|gt( 1 )}
             <span>
              <a title="{"Previous page"|i18n( 'ezcomments/comment/view/page' )}" href={concat( $page_prefix ,$current_page|sub( 1 ) )|ezurl}><</a>&nbsp;
             </span>
         {/if}
         {def $page_length=2}
         {if $current_page|sub( $page_length )|le( 1 )}
            {if $current_page|sub( 1 )|gt( 0 )}
                {for 1 to $current_page|sub( 1 ) as $page_item}
                    <a href={concat( $page_prefix, $page_item )|ezurl}>{$page_item}</a>
                {/for}
            {/if}
         {else}
            <a href={concat( $page_prefix,"1" )|ezurl}>1</a>
            {if $current_page|sub( $page_length )|gt( 2 )}
            ...
            {/if}
            {for $current_page|sub( $page_length ) to $current_page|sub( 1 ) as $page_item}
                <a href={concat( $page_prefix, $page_item )|ezurl}>{$page_item}</a>
            {/for}
         {/if}
         {$current_page}
         {if $current_page|sum( $page_length )|ge( $total_page )}
             {if $current_page|sum( 1 )|le( $total_page )}
                 {for $current_page|sum( 1 ) to $total_page as $page_item}
                    <a href={concat( $page_prefix, $page_item )|ezurl}>{$page_item}</a>
                 {/for}
             {/if}
         {else}
            {for $current_page|sum( 1 ) to $current_page|sum( $page_length ) as $page_item}
                <a href={concat( $page_prefix, $page_item )|ezurl}>{$page_item}</a>
            {/for}
            {if $total_page|sub( $current_page, $page_length )|gt( 1 )}
            ...
            {/if}
            <a href={concat( $page_prefix,$total_page )|ezurl}>{$total_page}</a>
         {/if}
         
         {undef $page_length}
         {*
         {for 1 to $total_page as $page_item}
            {if $page_item|eq($current_page)}
                {$page_item}
            {else}
                <a href={concat( $page_prefix,$page_item )|ezurl}>{$page_item}</a>
            {/if}
         {/for}
         *}
         {if $current_page|lt($total_page)}
             <span>
              <a title="{"Next page"|i18n('ezcomments/comment/view/page')}" href={concat( $page_prefix,$current_page|sum( 1 ) )|ezurl}>></a> 
             </span>
         {/if}
     </div>
    {undef $page_prefix}
{/if}