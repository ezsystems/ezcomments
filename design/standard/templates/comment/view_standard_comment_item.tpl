<div class="ezcomments-comment-view-comment" id="ezcomments_comment_view_commentitem">
            <div class="ezcomments-comment-view-commenttitle">
                <span>#{$base_index|sum($index)|sum(1)}</span>
                <span>{$comment.title|wash}</span>
            </div>
            <div class="ezcomments-comment-view-commentbody">
                {$comment.text|wash|nl2br}
            </div>
            <div class="ezcomments-comment-view-commentbottom">
                <span>
                    {if $comment.url|eq( '' )}
                        {$comment.name|wash}
                    {else}
                        <a href="{$comment.url|wash}">
                            {$comment.name|wash}
                        </a>
                    {/if}
                    {'on'|i18n('extension/ezcomments/view')}
                    {$comment.created|l10n( 'shortdatetime' )}
                </span>
            </div>
            {def $can_edit=fetch( 'comment', 'has_access_to_function', hash( 'function', 'edit',
                                                                       'contentobject', $contentobject,
                                                                       'language_code', $language_code,
                                                                       'comment', $comment
                                                                        ) )}
            {def $can_delete=fetch( 'comment', 'has_access_to_function', hash( 'function', 'delete',
                                                                       'contentobject', $contentobject,
                                                                       'language_code', $language_code,
                                                                       'comment', $comment
                                                                        ) )}
            {if or( $can_edit, $can_delete )}
                <div class="ezcomments-comment-view-commenttool">
                    {if $can_edit}
                        <span>
                            <a href={concat('/comment/edit/',$comment.id)|ezurl}>
                                {'Edit'|i18n('extension/ezcomments/view')}
                            </a>
                        </span>
                    {/if}
                    {if $can_delete}
                        <span>
                            <a href={concat('/comment/delete/',$comment.id)|ezurl}>
                                {'Delete'|i18n('extension/ezcomments/view')}
                            </a>
                        </span>
                    {/if}
                </div>
            {/if}
            {undef $can_edit $can_delete}
</div>
<br />