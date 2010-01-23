<div class="ezcom-view-comment" id="ezcomments_comment_view_commentitem">
            <div class="ezcom-comment-index">
                <span><a name="{concat( 'c', $index|sum(1) )}"></a>
                #{$base_index|sum($index)|sum(1)}
                </span>
            </div>
            <div class="ezcom-comment-title">
                <span>
                    {$comment.title|wash}
                </span>
            </div>
            <div class="ezcom-comment-body">
                <p>
                  {$comment.text|wash|nl2br}
                </p>
            </div>
            <div class="ezcom-comment-author">
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
                <div class="ezcom-comment-tool">
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