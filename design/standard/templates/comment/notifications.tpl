{ezcss_require( 'notifications.css' )}

{ezscript_require( array( 'ezjsc::yui3', 'ezjsc::yui3io' ) )}

<script type="text/javascript">
<!--
{literal}
var ezcommentsComment =new Object();
ezcommentsComment.currentData = null;
ezcommentsComment.argObject = null;
ezcommentsComment.events = null;
ezcommentsComment.refresh =null;
ezcommentsComment.repaint = null;
ezcommentsComment.showMessage = null;

//1. register events
YUI( YUI3_config ).use('node', 'io-ez', 'event-custom-complex', function( Y )
{
    var ezcommentsCommentEvent = function()
    {
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
    ezcommentsComment.events = new ezcommentsCommentEvent();;
});
{/literal}
// -->
</script>


<div id="ezcomments_comments" class="ezcomments-comments">
    <div class="ezcomments-comments-title">
        <span>Comment Settings</span>
    </div>
    {include name="ezcomments_tabs" uri="design:comment/tabs.tpl"}
    <div id="ezcomments_comment_message" style="text-align:center">
    </div>
    {include name="ezcomments_filter" uri="design:comment/filter.tpl"}
    {include name="ezcomments_filter" uri="design:comment/paging.tpl"}
    {include name="ezcomments_filter" uri="design:comment/comment_list.tpl"}
    {include name="ezcomments_filter" uri="design:comment/tools.tpl"}
</div>

<script type="text/javascript">

<!--
{literal}
YUI( YUI3_config ).use('node', 'json-stringify', 'io-ez', 'event-custom-complex', function( Y )
{
    //1. load page data
    var ezcommentsRequestData = function( )
    {
        var args = "";
        var argObject = ezcommentsComment.argObject ;
        if( argObject != 'undefined' && argObject != null )
        {
            args = Y.JSON.stringify(argObject);
        }
        Y.io.ez( 'comment::get_notification_comment_list', {
            data: 'args='+args,
            on: {success: function( id,r )
                { 
                    if ( r.responseJSON.error_text )
                        Y.get( '#ezcomments_comment_message' ).setContent( r.responseJSON.error_text );
                    else
                    {
                        var resContent = r.responseJSON.content;
                        var resObject = Y.JSON.parse( resContent );
                        ezcommentsComment.events.fire("commentloaded", resObject);
                        ezcommentsComment.currentData = resObject;
                        ezcommentsFillData();
                    }
                }
            }
        });
    }
   
    var ezcommentsFillData = function()
    {
        var currentData = ezcommentsComment.currentData;
        if( currentData == null )
        {
            ezcommentsShowMessage( 'Fill data error in this page.' );
        }
        else
        {
            ezcommentsComment.events.fire("paintcomment");
            ezcommentsComment.events.fire("paintpage");
        }
    }
    
    
    var ezcommentsShowMessage = function( message )
    {
        messageDiv = Y.get( '#ezcomments_comment_message' );
        messageHead = "";
        messageButton = "<div><input id=\"ezcomment_comment_message_ok\" class=\"button\" type=\"button\" value=\"OK\" /></div>";
        text="<br /><center><div class=\"ezcomments-comment-message\">"+ messageHead + message +messageButton + "</div></center><br />";
        messageDiv.setContent( text );
        Y.get('#ezcomment_comment_message_ok').on('click', function(e){
              messageDiv.setContent("");
        });
    }
    
    
    var ezcommentsInit = function()
    {
        var argObject = new Object();
        argObject.offset = 0;
        argObject.length = 3;
        argObject.targetPage = 1;
        ezcommentsComment.argObject = argObject;
        
        ezcommentsComment.events.fire("commentready",'#ezcomments_comments');
        ezcommentsRequestData();
    }
    

  
    var fillComments = function()
    {
       var comments = ezcommentsComment.currentData.comments;
       var uiComment =  Y.get( '#ezcomments_comment_list' );
       var output = "<tr class=\"ezcomments-comment-list-header\"><td>Notified</td><td>Comment</td><td>Content</td><td>Post Time</td></tr>";
       for( var i in comments)
       {
           var row = comments[i];
           if( i%2 == 1 )
           {
             output +="<tr class=\"ezcomments-comment-list-row-odd\"  commentid=\"" + row['id'] + "\">";
           }
           else
           {
             output +="<tr class=\"ezcomments-comment-list-row-even\" commentid=\"" + row['id'] + "\">";
           }
           
           output +="<td class=\"ezcomments-comment-list-notification\">";
           var notification = row["notification"];
           if( notification == 1)
           {
              output += "<input type=\"checkbox\" checked />";
           }
           else
           {
             output += "<input type=\"checkbox\" />";
           }
           output += "</td>";
           output += "<td>" + row["text"] + "</td>";
           output += "<td class=\"ezcomments-comment-list-objectname\"><a href=\"../" + row['content_url']+"\">" + row["object_name"] + "</a></td>";
           var postTime = new Date();
           postTime.setTime( row["time"] );
           output += "<td>" + postTime.toLocaleDateString() + "</td>";
           output +="</tr>";
         }
        uiComment.setContent(output); 
    }
    
   ezcommentsComment.refresh = ezcommentsRequestData;
   ezcommentsComment.repaint = ezcommentsFillData;
   ezcommentsComment.showMessage = ezcommentsShowMessage;
   // register events
   ezcommentsComment.events.on("paintcomment",fillComments);   
   Y.on( "contentready", ezcommentsInit, '#ezcomments_comments' );
            
});

{/literal}
// -->
</script>
