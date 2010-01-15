
{ezscript_require( array( 'ezjsc::yui3', 'ezjsc::yui3io' ) )}

<script type="text/javascript">
<!--
{literal}

var ezcommentsCommentView = new Object();
ezcommentsCommentView.userinfo = null;
ezcommentsCommentView.events = null;
ezcommentsCommentView.currentData = new Object();
ezcommentsCommentView.request = new Object();
ezcommentsCommentView.refresh = null;

//1. register events
YUI( YUI3_config ).use('node', 'io-ez', 'event-custom-complex', function( Y )
{
    var ezcommentsCommentEvent = function()
    {
        this.publish("load",{
              emitFacade: true,
                defaultFn: function(e) {
                    
                }
        });
        this.publish("commentloaded",
            {
                emitFacade: true,
                defaultFn: function(e) {
                    
                }
            }
            );
       this.publish("commentready",
            {
                emitFacade: true,
                defaultFn: function(e) {
                    
                }
            }
            );
       this.publish("paintcomment",
           {
                emitFacade: true,
                defaultFn: function(e) {
                
                }
           }
           );
           
       this.publish("paintpage",
           {
                emitFacade: true,
                defaultFn: function(e) {
                
                }
           }
           );
    }
    Y.augment(ezcommentsCommentEvent,Y.EventTarget);
    ezcommentsCommentView.events = new ezcommentsCommentEvent();;
});

{/literal}
// -->
</script>

{* Fetching translation array START *}
{ezscript('comment::i18n::view::ezcommentsCommentView.languageArray')}
{* Fetching translation array END *}

<script type="text/javascript">
{literal}
 String.prototype.ezi18n = function( context, identifier ){
        var contextArray = ezcommentsCommentView.languageArray[context];
        if( contextArray =='undefined' || contextArray == null )
        {
            return this;
        }
        else
        {
            var result = contextArray[identifier];
            if( result == 'undefined' || result == null )
            {
                return this;
            }
            else
            {
                return result;
            }
        }
    };
{/literal}
</script>

<input type="hidden" id="ezcomments_comment_oid" value="{$contentobject_id}" />
<input type="hidden" id="ezcomments_comment_language" value="{$language_id}" />
<div id="ezcomments_comment_message"></div>
<div id="ezcomments_comment_extension"></div>
{include name="view_page" uri="design:comment/view_page.tpl"}
{include name="view_comment_list" uri="design:comment/view_comment_list.tpl"}
{if $enabled|eq(1)}
{include name="view_addcomment" uri="design:comment/view_add_comment.tpl"}
{else}
<div class="ezcomments-comment-view-disabled"><p>Commenting disabled.</p></div>
{/if}
<script type="text/javascript">

<!--
{literal}
YUI( YUI3_config ).use('node', 'json-stringify', 'io-ez', 'event-custom-complex', function( Y )
{
   Y.on("load", function(e){
        ezcommentsCommentView.events.fire("load");
   },window);
});

{/literal}
// -->
</script>
