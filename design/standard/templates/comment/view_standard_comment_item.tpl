<div class="ezcomments-comment-view-comment" id="ezcomments_comment_view_commentitem">
            <div class="ezcomments-comment-view-commenttitle">
                <span>#{$base_index|sum($index)|sum(1)}</span>
                <span>{$comment.title|wash()}</span>
            </div>
            <div class="ezcomments-comment-view-commentbody">
                {$comment.text|wash()|nl2br()}
            </div>
            <div class="ezcomments-comment-view-commentbottom">
                <span>
                    {if $comment.url|eq( '' )}
                        {$comment.name|wash()}
                    {else}
                        <a href="{$comment.url}">
                            {$comment.name|wash()}
                        </a>
                    {/if}
                    {'on'|i18n('extension/ezcomments/view')}
                    {$comment.created|l10n( 'shortdatetime' )}
                </span>
            </div>
            <div class="ezcomments-comment-view-commenttool">
                <span><a href={concat('/comment/edit/',$comment.id)|ezurl}>{'Edit'|i18n('extension/ezcomments/view')}</a></span>
                <span><a href={concat('/comment/delete/',$comment.id)|ezurl}>{'Delete'|i18n('extension/ezcomments/view')}</a></span>
            </div>
</div>
<br />