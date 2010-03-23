{ezcss_require( 'comment.css' )}
<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">
<div id="ezcom_setting" class="ezcom-setting">
    {if $subscriber.hash_string}
        <div class="ezcom-setting-mail">
            <p>
                {$subscriber.email}
            </p>
        </div>
    {/if}
    
    {if is_set( $subscriber )|not()}
        <div class="message-error">
            <p>
                 {'The subscriber doesn\'t exist.'|i18n('ezcomments/comment/setting')}
            </p>
        </div>
    {elseif $total_count|eq( 0 )}
        <div class="message-feedback">
                <p>
                     {'You are not subscribing to any content.'|i18n('ezcomments/comment/setting')}
                </p>
            </div>
    {else}
        {def $number_perpage = ezini( 'NotificationSettings', 'NumberPerPage', 'ezcomments.ini' )}
        {if $number_perpage|eq( '-1' )|not}
            {def $total_page = $total_count|div( $number_perpage )|ceil}
            {if $total_page|ge( 1 )}
                {def $page_prefix='comment/setting'}
                {if $subscriber.hash_string}
                    {set $page_prefix=concat( $page_prefix, '/', $subscriber.hash_string )}
                {/if}
                <div class="ezcom-page">
                    {'%current_page/%total_page'|i18n( 'ezcomments/comment/view/page', '', hash( '%current_page', $current_page, '%total_page', $total_page ) )}
                    {if $current_page|gt( 1 )}
                         <span>
                          <a title="{"Previous page"|i18n( 'ezcomments/comment/view/page' )}" href={concat( $page_prefix, '/' ,$current_page|sub( 1 ) )|ezurl}><</a>
                         </span>
                    {/if}
                    {if $current_page|lt($total_page)}
                         <span>
                          <a title="{"Next page"|i18n('ezcomments/comment/view/page')}" href={concat( $page_prefix, '/', $current_page|sum( 1 ) )|ezurl}>></a> 
                         </span>
                    {/if}
                </div>
                {undef $page_prefix}
            {/if}
            {undef $total_page}
        {/if}
        {undef $number_perpage}
        {if is_set( $update_success )}
            <div class="message-feedback">
                <p>{'You have updated comment settings.'|i18n( 'ezcomments/comment/setting' )}</p>
                <input type="button" class="button" value="{'OK'|i18n( 'ezcomments/comment/setting' )}" onclick="this.parentNode.className = 'hide';" />
            </div>
        {/if}
            <form method="post" action="#">
                <input type="hidden" name="SubscriberID" value="{$subscriber.id}" />
                <div class="ezcom-setting-head">
                    <div class="ezcom-setting-select">
                        <span>
                            {'Select'|i18n( 'ezcomments/comment/setting' )}
                        </span>
                    </div>
                    <div class="ezcom-setting-content">
                        <span>
                            {'Content'|i18n( 'ezcomments/comment/setting' )}
                        </span>
                    </div>
                    <div class="ezcom-setting-count">
                        <span>
                            {'Subscription started'|i18n( 'ezcomments/comment/setting' )}
                        </span>
                    </div>
                </div>
                {foreach $subscription_list as $subscription}
                {def $contentobject=$subscription.contentobject}
                    <div class="ezcom-setting-row">
                        <div class="ezcom-setting-select">
                            <label>
                                <input type="checkbox" name="Checkbox{$subscription.id}"
                                   checked="checked" />
                                {'Subscribed'|i18n( 'ezcomments/comment/setting' )}
                                <input type="hidden" name="CheckboxName[]" value="Checkbox{$subscription.id}" />
                            </label>
                        </div>
                        <div class="ezcom-setting-count">
                            <span>
                                {$subscription.subscription_time|l10n(shortdatetime)}
                            </span>
                        </div>
                        <div class="ezcom-setting-content">
                            <span>
                                <a href={$contentobject.main_node.url_alias|ezurl}>
                                    {$contentobject.name}
                                </a>
                            </span>
                        </div>
                    </div>
                    {undef $contentobject}
                {/foreach}
                <div class="ezcom-tool">
                    <p>
                        <input type="submit" value="{'Save'|i18n( 'ezcomments/comment/setting' )}" name="SaveButton" class="button" />
                        <input type="reset" value="{'Reset'|i18n( 'ezcomments/comment/setting' )}" class="button" />
                    </p>
                </div>
             </form>
        {/if}
</div>
</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>