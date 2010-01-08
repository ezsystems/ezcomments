{ezcss_require( 'view.css' )}
{if $objectattribute.data_float|eq(1)}
{*outputting content starts*}
<h2>
    <a href={$contentobject.main_node.url_alias|ezurl}>{$contentobject.name}</a>
</h2>
{*outputting content ends*}

{*outputting pages starts*}
    <div id="ezcomments_comment_view_page" class="ezcomments-comment-view-page">
       <p>
         <span>{'Total comments:'|i18n( 'design/standard/ezcomments/view_standard' )}</span>
         <span>{$total_count}</span> <span>, </span>
         <span>{'Pages:'|i18n( 'design/standard/ezcomments/view_standard' )}</span>
         {if $current_page|gt( 1 )}
             <span>
              <a title="{"Previous page"|i18n( 'design/standard/ezcomments/view_standard' )}" href={concat( "comment/view/standard/", $contentobject.id, "/", $current_page|sub( 1 ) )|ezurl()}>
               <
              </a> &nbsp;
             </span>
         {/if}
         {def $pageLength=2}
         {if $current_page|sub( $pageLength )|le( 1 )}
            {if $current_page|sub( 1 )|gt( 0 )}
                {for 1 to $current_page|sub( 1 ) as $page_item}
                    <a href={concat( "comment/view/standard/", $contentobject.id, "/", $page_item )|ezurl()}>{$page_item}</a>
                {/for}
            {/if}
         {else}
            <a href={concat( "comment/view/standard/", $contentobject.id,"/1" )|ezurl()}>1</a>
            {if $current_page|sub( $pageLength )|gt( 2 )}
            ...
            {/if}
            {for $current_page|sub( $pageLength ) to $current_page|sub( 1 ) as $page_item}
                <a href={concat( "comment/view/standard/", $contentobject.id, "/", $page_item )|ezurl()}>{$page_item}</a>
            {/for}
         {/if}
         {$current_page}
         {if $current_page|sum( $pageLength )|ge( $total_page )}
             {if $current_page|sum( 1 )|le( $total_page )}
                 {for $current_page|sum( 1 ) to $total_page as $page_item}
                    <a href={concat( "comment/view/standard/", $contentobject.id, "/", $page_item )|ezurl()}>{$page_item}</a>
                 {/for}
             {/if}
         {else}
            {for $current_page|sum( 1 ) to $current_page|sum( $pageLength ) as $page_item}
                <a href={concat( "comment/view/standard/", $contentobject.id, "/", $page_item )|ezurl()}>{$page_item}</a>
            {/for}
            {if $total_page|sub( $current_page, $pageLength )|gt( 1 )}
            ...
            {/if}
            <a href={concat("comment/view/standard/",$contentobject.id,"/",$total_page)|ezurl()}>{$total_page}</a>
         {/if}
         
         {undef $pageLength}
         {*
         {for 1 to $total_page as $page_item}
            {if $page_item|eq($current_page)}
                {$page_item}
            {else}
                <a href={concat("comment/view/standard/",$contentobject.id,"/",$page_item)|ezurl()}>{$page_item}</a>
            {/if}
         {/for}
         *}
         {if $current_page|lt($total_page)}
             <span>
              <a title="{"Next page"|i18n('design/standard/ezcomments/view_standard')}" href={concat("comment/view/standard/",$contentobject.id,"/",$current_page|sum(1))|ezurl()}>
               >
              </a> 
             </span>
         {/if}
         &nbsp;
       </p>
     </div>
    
    {* outputting comments starts*}
    
    {for 0 to $comments|count|sub( 1 ) as $index}
        <div class="ezcomments-comment-view-comment" id="ezcomments_comment_view_commentitem">
            <div class="ezcomments-comment-view-commenttitle">
                <span>#{$current_page|sub( 1 )|mul( $number_per_page )|sum( $index )|sum(1) }</span>
                <span>{$comments.$index.title|wash()}</span>
            </div>
            <div class="ezcomments-comment-view-commentbody">
                {$comments.$index.text|wash()|nl2br()}
            </div>
            <div class="ezcomments-comment-view-commentbottom">
                <span>
                    {if $comments.$index.url|eq( '' )}
                        {$comments.$index.name|wash()}
                    {else}
                        <a href="{$comments.$index.url}">
                            {$comments.$index.name|wash()}
                        </a>
                    {/if}
                    {'on'|i18n(' design/standard/ezcomments/view_standard' )}
                    {$comments.$index.created|l10n( 'shortdatetime' )}
                </span>
            </div>
            <div class="ezcomments-comment-view-commenttool">
                <span><a href={concat('/comment/edit/',$comments.$index.id)|ezurl}>{'Edit'|i18n('design/standard/ezcomments/view_standard')}</a></span>
                <span><a href=>{'Delete'|i18n('design/standard/ezcomments/view_standard')}</a></span>
            </div>
        </div>
        <br />
    {/for}
    {* outputting comments ends *}
    
    {* outputting adding comment starts*}
    {if $objectattribute.data_int}
        {def $user=fetch( 'user', 'current_user' )}
        {def $anonymousUserID=ezini('UserSettings', 'AnonymousUserID')}
        {def $isAnonymous=$user.contentobject_id|eq($anonymousUserID)}
        <form method="post" action={concat('comment/view/standard/',$contentobject.id)|ezurl()}>
        <div class="ezcomments-comment-view-addcomment" id="ezcomments_comment_view_addcomment">
                <table>
                    <tr>
                        <td class="ezcomments-comment-view-moduletitle" colspan="2">
                            {'Post Comment'|i18n( 'design/standard/ezcomments/view_standard' )}
                        </td>
                    </tr>
                    {if and( $hasError|null()|not(), $hasError|eq( 1 ))}
                        <tr><td class="ezcomments-comment-view-addcomment-message" colspan="2">{$errorMessage}</td></tr>
                    {/if}
                    <tr>
                        <td class="ezcomments-comment-view-addcomment-left">{'Title:'|i18n( 'design/standard/ezcomments/view_standard' )}</td>
                        <td>
                            <input type="text" class="ezcomments-comment-view-addcomment-title" maxlength="100" id="ezcomments_comment_view_addcomment_title" name="ezcomments_comment_view_addcomment_title" />
                        </td>
                    </tr>
                    <tr>
                        <td>{'Name:'|i18n( 'design/standard/ezcomments/view_standard' )}</td>
                        <td>
                            <input type="text" class="ezcomments-comment-view-addcomment-name" maxlength="50" id="ezcomments_comment_view_addcomment_name" name="ezcomments_comment_view_addcomment_name" value="{$comment_name}" />
                            <span class="ezcomments-comment-view-addcomment-mandatorymessage">*</span>
                        </td>
                    </tr>
                    <tr>
                        <td>{'Website:'|i18n( 'design/standard/ezcomments/view_standard' )}</td>
                        <td>
                            <input type="text" class="ezcomments-comment-view-addcomment-website" maxlength="100" id="ezcomments_comment_view_addcomment_website" name="ezcomments_comment_view_addcomment_website" value="{$comment_website}" />
                        </td>
                    </tr>
                    <tr>
                        <td>{'Email:'|i18n( 'design/standard/ezcomments/view_standard' )}</td>
                        <td>
                            <input type="text" maxlength="100" class="ezcomments-comment-view-addcomment-email" id="ezcomments_comment_view_addcomment_email" name="ezcomments_comment_view_addcomment_email" {if $is_anonymous|not()} disabled="true" {/if} value="{$comment_email}" /> 
                            <span class="ezcomments-comment-view-addcomment-mandatorymessage">*</span>
                            <span class="ezcomments-comment-view-addcomment-mandatorymessage">( The Email address will not be shown )</span>
                        </td>
                    </tr>
                    <tr>
                        <td>{'Content:'|i18n( 'design/standard/ezcomments/view_standard' )}</td>
                        <td>
                            <textarea class="ezcomments-comment-view-addcomment-textarea" id="ezcomments_comment_view_addcomment_content" name="ezcomments_comment_view_addcomment_content"></textarea>
                            <span class="ezcomments-comment-view-addcomment-mandatorymessage">*</span>
                        </td>
                    </tr>
                    <tr>
                        <td>{'Notified:'|i18n( 'design/standard/ezcomments/view_standard' )}</td>
                        <td>
                            <input type="checkbox" id="ezcomments_comment_view_addcomment_notified" name="ezcomments_comment_view_addcomment_notified" {if $comment_notified}checked{/if} />
                        </td>
                    </tr>
                    <tr>
                        <td class="ezcomments-comment-view-addcomment-message" id="ezcomments_comment_view_addcomment_message" colspan="2" />
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="submit" value="{'Post Comment'|i18n(' design/standard/ezcomments/view_standard' )}" class="button" id="ezcomments_comment_view_addcomment_post" name="PostCommentButton" />
                            {if $is_anonymous}
                                <input type="checkbox" name="ezcomments_comment_view_addcomment_rememberme" {if $comment_remember}checked="true"{/if} />
                                {'Remember me'|i18n( 'design/standard/ezcomments/view_standard' )}
                            {/if}
                        </td>
                    </tr>
                </table>
        </div>
        </form>
        {undef $user $anonymousID $isAnonymous}
     {/if}
     {* outputting adding comment ends *}
{/if}