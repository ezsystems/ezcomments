{if not( is_set( $self_edit ) )}
    {def $self_edit=false()}
{/if}

{if not( is_set( $self_delete ) )}
    {def $self_delete=false()}
{/if}

<div class="ezcom-view-comment">
            <div class="attribute-byline ezcom-comment-top">
                {if $comment.name}
                    <div class="ezcom-comment-author">
                        <p class="author">
                            {if $comment.url|eq( '' )}
                                {$comment.name|wash}
                            {else}
                                <a href="{$comment.url|wash}">
                                    {$comment.name|wash}
                                </a>
                            {/if}
                        </p>
                        <span>
                         {'wrote:'|i18n('ezcomments/comment/view')}
                        </span>
                    </div>
                {/if}
                <div class="ezcom-comment-time">
                    <p class="date">
                        {$comment.created|l10n( 'shortdatetime' )}
                    </p>
                </div>
            </div>
            {if $comment.title}
                <div class="ezcom-comment-title">
                        <span>
                            {$comment.title|wash}
                        </span>
                </div>
            {/if}
            <div class="ezcom-comment-body">
                <p>
                  {$comment.text|wash|nl2br}
                </p>
            </div>
            {def $can_edit=fetch( 'comment', 'has_access_to_function', hash( 'function', 'edit',
                                                                             'contentobject', $contentobject,
                                                                             'language_code', $language_code,
                                                                             'comment', $comment,
                                                                             'scope', 'role',
                                                                             'node', $node ) )
                 $can_delete=fetch( 'comment', 'has_access_to_function', hash( 'function', 'delete',
                                                                               'contentobject', $contentobject,
                                                                               'language_code', $language_code,
                                                                               'comment', $comment,
                                                                               'scope', 'role',
                                                                               'node', $node ) )
                 $user_display_limit_class=concat( ' class="limitdisplay-user limitdisplay-user-', $comment.user_id, '"' )}
                 
            {if or( $can_edit, $can_self_edit, $can_delete, $can_self_delete )}
                <div class="ezcom-comment-tool">
                    {if or( $can_edit, $can_self_edit )}
                        {if and( $can_self_edit, not( $can_edit ) )}
                            {def $displayAttribute=$user_display_limit_class}
                        {else}
                            {def $displayAttribute=''}
                        {/if}
                        <span{$displayAttribute}>
                            <a href={concat( '/comment/edit/', $comment.id )|ezurl}>{'Edit'|i18n('ezcomments/comment/view')}</a>
                        </span>
                        {undef $displayAttribute}
                    {/if}
                    {if or( $can_delete, $can_self_delete )}
                        {if and( $can_self_delete, not( $can_delete ) )}
                            {def $displayAttribute=$user_display_limit_class}
                        {else}
                            {def $displayAttribute=''}
                        {/if}
                        <span {$displayAttribute}>
                            <a href={concat( '/comment/delete/',$comment.id )|ezurl}>
                                {'Delete'|i18n('ezcomments/comment/view')}
                            </a>
                        </span>
                        {undef $displayAttribute}
                    {/if}
                </div>
            {/if}
            {undef $can_edit $can_delete}
    <div class="separator">
        <div class="separator-design">
    </div>
</div>
</div>