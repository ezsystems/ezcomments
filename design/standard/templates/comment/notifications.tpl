{* On demand require some custom css *}
{ezcss_require( 'notifications.css' )}

<div id="ezcomments_comments">
    <div>
        <table id="ezcomments_comment_list" class="ezcomments-comment-list">
        </table>
    </div>
    <div id="ezcomments_comment_page" class="ezcomment-comment-page" currentpage="1">
        <span>Page</span>
        <span id="ezcomments_comment_page_index">1/4</span>
        <span class="ezcomments-comment-page-button">
            <a href="#"> |< </a>
            <a href="#"> < </a>
            <a href="#"> > </a>
            <a href="#"> >| </a>
        </span>
        
        <span id="ezcomments_comment_page_number" currentpage="1"></span>
    </div>
    <div id="ezcomments_comment_tool" class="ezcomments-comment-tool">
        <input type="checkbox" onClick="ezcomments_selectAll(this);" />
        <span>Select</span>
        <input type="button" value="Set Notification" />
    </div>
</div>

{ezscript_require( array( 'ezjsc::yui3', 'ezjsc::yui3io' ) )}
<script type="text/javascript">
<!--
{literal}
YUI( YUI3_config ).use('node', 'io-ez', function( Y )
{
    Y.on( "contentready", function( e )
    {
        Y.io.ez( 'comment::get_notification_comment_list', {
            data: 'arg1=Good afternoon!',
            on: {success: function( id,r )
                { 
                    if ( r.responseJSON.error_text )
                        Y.get( '#ezcomments_comment_list' ).setContent( r.responseJSON.error_text );
                    else
                    {
                        var resContent = r.responseJSON.content;
                        var resObject = eval("(" + resContent + ")"); 
                        fillComments( resObject.comments, Y.get( '#ezcomments_comment_list' ) );
                        fillCommentPage( resObject.total_count, Y.get( '#ezcomments_comment_page_number' ) );
                        //.setContent( resObject );
                    }
                }
            }
        });
    }, '#ezcomments_comment_list' );
});


function fillComments( comments, uiComment)
{
   var output = "<tr class=\"ezcomments-comment-list-header\"><td><th>Comment</th><th>Object Name</th></tr>";
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
       output += "<td class=\"ezcomments-comment-list-objectname\">" + row["object_name"] + "</td>";
       output +="</tr>";
   }
   
   uiComment.setContent(output); 
}

function fillCommentPage( count, uiCommentPage )
{
    uiCommentPage.setContent( count );
}

function jumpPage()
{
    
}

function ezcomments_selectAll(checkbox)
{
    alert(checkbox.checked);
    alert(Y);
}
{/literal}
// -->
</script>
