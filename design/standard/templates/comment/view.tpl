{ezcss_require( 'view.css' )}

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
<!--
1.page
-->
<input type="hidden" id="ezcomments_comment_oid" value="{$contentobject_id}">
<div id="ezcomments_comment_message"></div>
{include name="view_page" uri="design:comment/view_page.tpl"}
{include name="view_comment_list" uri="design:comment/view_comment_list.tpl"}
{include name="view_addcomment" uri="design:comment/view_add_comment.tpl"}
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
