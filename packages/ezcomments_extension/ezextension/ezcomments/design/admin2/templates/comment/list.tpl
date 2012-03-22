{def $remove_comments_allowed = fetch(user, has_access_to, hash(module, comment, function, removecomments))}
{def $edit_allowed = fetch(user, has_access_to, hash(module, comment, function, edit))}

<form action={'comment/list'|ezurl} method="post" name="commentlist">
<div class="context-block comment-list">
	<div class="box-header">
		<h1 class="context-title">{'Comments list'|i18n( 'ezcomments/comment/list' )}</h1>
        <div class="header-mainline"></div>
    </div>

    <div class="box-content">
        <table class="list" cellspacing="0">
            <tbody>
                <tr>
                    {if $remove_comments_allowed}<th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Select all comments'|i18n('ezcomments/comment/list')}" onclick="ezjs_toggleCheckboxes( document.commentlist, 'DeleteIDArray[]' ); return false;" /></th>{/if}
                    <th>{'Comment'|i18n('ezcomments/comment/list')}</th>
                    <th>{'Node'|i18n('ezcomments/comment/list')}</th>
                    <th>{'Modified'|i18n('ezcomments/comment/list')}</th>
                    <th style="text-align:right">{'IP address'|i18n('ezcomments/comment/list')}</th>
                    {if $edit_allowed}<th class="tight">&nbsp;</th>{/if}
                </tr>

                {def $comments_count = fetch(comment, comment_count)}
                {def $comments = fetch(comment, comment_list_by_content_list, hash(offset, $view_parameters.offset, length, 10))}

                {foreach $comments as $comment sequence array('bglight', 'bgdark') as $sequence}
                    <tr class="{$sequence}">
                        {if $remove_comments_allowed}<td><input type="checkbox" value="{$comment.id}" name="DeleteIDArray[]" /></td>{/if}
                        <td>{$comment.text|wash|shorten(200)}</td>
                        <td><a href={$comment.contentobject.main_node.url_alias|ezurl}>{$comment.contentobject.main_node.name|wash}</a></td>
                        <td>{$comment.modified|l10n(shortdatetime)}</td>
                        <td class="number" align="right">{$comment.ip}</td>
                        {if $edit_allowed}<td><a href={concat('comment/edit/', $comment.id)|ezurl}><img src={'edit.gif'|ezimage} alt="{'Edit comment'|i18n('ezcomments/comment/list')}" /></a></td>{/if}
                    </tr>
                {/foreach}
            </tbody>
        </table>

        <div class="context-toolbar">
            {include name=navigator
                uri='design:navigator/google.tpl'
                page_uri='comment/list'
                item_count=$comments_count
                view_parameters=$view_parameters
                item_limit=10}
        </div>
    </div>

    {if and($comments_count|gt(0), $remove_comments_allowed)}
        <div class="controlbar">
            <div class="block">
                <div class="button-left"><input type="submit" value="{'Remove selected'|i18n('ezcomments/comment/list')}" name="RemoveCommentsButton" class="button" /></div>
            </div>
            <div class="break"></div>
        </div>
    {/if}
</div>
</form>