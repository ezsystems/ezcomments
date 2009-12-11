{* On demand require some custom css *}
{ezcss_require( 'notifications.css' )}

<div id="ezcomments_comments" class="ezcomments-comments">
    <div class="ezcomments-comments-title">
        <span>Comment Settings</span>
    </div>
    <div class="ezcomments-comments-filter">
        <p>
         <a href="#" style="background-color:#cccccc;width:150px">All</a>
         <a href="#">Notified</a>
         Filter: <input type="text" class="ezcomments-textinput" /> <input type="button" value="submit" class="button" />
        </p>
    </div>
    <div id="ezcomments_comment_message" style="text-align:center">
        
    </div>
    <div>
        <table id="ezcomments_comment_list" class="ezcomments-comment-list">
        </table>
    </div>
    
    <div id="ezcomments_comment_page" class="ezcomment-comment-page" currentpage="1" totalnumber="10" totalpages="4" numberperpage="3">
        <span>Page</span>
        <span id="ezcomments_comment_page_index">
        <span id="currentpage">1</span>
        /
        <span id="totalpage">4</span>
        <span id="ezcomments_comment_page_buttons" class="ezcomments-comment-page-button">
            <a href="#" id="ezcomments_comment_page_first" action="first"> &nbsp;|< &nbsp; </a>
            <a href="#" id="ezcomments_comment_page_previous" action="previous"> < &nbsp;</a>
            <a href="#" id="ezcomments_comment_page_next" action="next"> > &nbsp;</a>
            <a href="#" id="ezcomments_comment_page_last" action="last"> >| &nbsp;</a>
        </span>
    </div>
    <div id="ezcomments_comment_tool" class="ezcomments-comment-tool">
        <input type="checkbox" id="ezcomments_comment_select_all" /><span>Enable/Disable all notifications</span>
        <input type="button" class="button" id="ezcomments_comment_save" value="Save" />
    </div>
</div>

{ezscript_require( array( 'ezjsc::yui3', 'ezjsc::yui3io' ) )}
<script type="text/javascript">
<!--
{literal}
YUI( YUI3_config ).use('node', 'json-stringify', 'io-ez', function( Y )
{
    var currentData = null;
    var argObject = null;
    var ezcommentsRequestData = function( )
    {
        var args = "";
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
                        currentData = resObject;
                        
                        ezcommentsFillData();
                    }
                }
            }
        });
    }
    
    var ezcommentsFillData = function()
    {
        if( currentData == null )
        {
            ezcommentsShowMessage( 'Fill data error in this page.' );
        }
        else
        {
            fillComments( currentData.comments, Y.get( '#ezcomments_comment_list' ) );
            ezcommentsFillCommentPage( currentData.total_count, argObject);
        }
    }
    
    var ezcommentsShowMessage = function( message )
    {
        messageDiv = Y.get( '#ezcomments_comment_message' );
        messageHead = "";
        messageButton = "<div><input id=\"ezcomment_comment_message_ok\" class=\"button\" type=\"button\" value=\"OK\" /></div>";
        text="<center><div class=\"ezcomments-comment-message\">"+ messageHead + message +messageButton + "</div></center><br>";
        messageDiv.setContent( text );
        Y.get('#ezcomment_comment_message_ok').on('click', function(e){
              messageDiv.setContent("");
        });

    }
    
    var ezcommentsFillCommentPage = function( count, argObject )
    {
        var commentPage = Y.get( '#ezcomments_comment_page' );

        var currentPage = Y.get('#ezcomments_comment_page #currentpage');        
        commentPage.setAttribute( 'currentpage', argObject.targetPage );
        currentPage.setContent( argObject.targetPage );
        
        var numberPerPage = commentPage.getAttribute( 'numberperpage' );
        var totalPageNumber = Math.ceil( count / parseInt(numberPerPage) );

        var totalPage = Y.get('#ezcomments_comment_page #totalpage');
        totalPage.setContent( totalPageNumber );
        
    }
    
    Y.on( "contentready", ezcommentsRequestData, '#ezcomments_comment_list' );
    
    var ezcomments_jumpPage = function( e )
    {
        var action = e.currentTarget.getAttribute("action");
        var currentPage = parseInt( Y.one('#ezcomments_comment_page').getAttribute('currentpage') );
        var totalNumber = parseInt( Y.one('#ezcomments_comment_page').getAttribute('totalnumber') );
        var totalPages = parseInt( Y.one('#ezcomments_comment_page').getAttribute('totalpages') );
        var numberPerPage = parseInt( Y.one('#ezcomments_comment_page').getAttribute('numberperpage') );
        switch ( action )
        {
            case "first":
                if ( currentPage > 1 )
                {
                    jumpToPage( 1 );
                }
                break;
            case "previous":
                if ( currentPage > 1 )
                {
                    jumpToPage( currentPage - 1, numberPerPage );
                }
                break;
            case "next":
                if ( currentPage < totalPages )
                {
                    jumpToPage( currentPage + 1, numberPerPage );
                }
                break;
            case "last":
                if ( currentPage < totalPages )
                {
                    jumpToPage( totalPages, numberPerPage );
                }
                break;
            default:
                //to do: if action is number
                jumpToPage( action, numberPerPage );
                break;
        }
    }
    
    var jumpToPage = function( targetPage, numberPerPage )
    {
       var offset = parseInt( targetPage - 1 ) * numberPerPage;
       argObject = new Object();
       argObject.offset = offset;
       argObject.length = numberPerPage;
       argObject.targetPage = targetPage;
       ezcommentsRequestData( );
    }
    
    var pagingButtons = Y.all('#ezcomments_comment_page_buttons a');
    pagingButtons.on('click',ezcomments_jumpPage);
    
    
    var ezcommentsSelectAll = function( e )
    {
        var checkBox = e.target;
        var checked = checkBox.get('checked');
        // get all the checkbox in comment list
        var trs = Y.all('#ezcomments_comment_list tr');
        for( var i=0;i < trs.size(); i++)
        {
            if( i!=0 )
            {
                var obj = trs.item( i ).one( 'input' );
                obj.set( 'checked', checked );
            }
        }
        /*
        if(checked)
        {
            checkBox.next().setContent( 'Select None' );
        }
        else
        {
            checkBox.next().setContent( 'Select All' );
        }
        */
    }
    Y.get('#ezcomments_comment_select_all').on('click',ezcommentsSelectAll);
    
    var ezcommentsSave = function( e )
    {
        //get changed item
        var commentsTR = Y.all( '#ezcomments_comment_list tr' );
        var argArray = new Array();
        for( var i in currentData.comments )
        {
            var row = (currentData.comments)[i];
            var uiValue = commentsTR.item(parseInt(i) + 1).one( 'input' ).get( 'checked' );
            uiValueInt = (uiValue) ? 1 : 0; 
            if ( uiValueInt != row['notification'] )
            {
                var obj = new Object();
                obj.id = row['id'];
                obj.notification = uiValueInt;
                argArray.push( obj );
            }
        } 
        var args=Y.JSON.stringify(argArray);
        Y.io.ez( 'comment::update_notification_comment', {
            data: 'args='+args,
            on: {success: function( id,r )
                { 
                    if ( r.responseJSON.error_text )
                    {
                        ezcommentsShowMessage( r.responseJSON.error_text );
                        ezcommentsFillData();
                    }   
                    else
                    {
                        var resContent = r.responseJSON.content;
                        ezcommentsShowMessage( resContent );
                    }
                }
            }
        });
    }
    
    
    
    Y.get('#ezcomments_comment_save').on('click',ezcommentsSave);
    
});


function fillComments( comments, uiComment)
{
   var output = "<tr class=\"ezcomments-comment-list-header\"><td></td><td>Comment</td><td>Content</td><td>Post Time</td></tr>";
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
       output += "<td class=\"ezcomments-comment-list-objectname\"><a href=\"#\">" + row["object_name"] + "</a></td>";
       var postTime = new Date();
       postTime.setTime( row["time"] );
       output += "<td>" + postTime.toLocaleDateString() + "</td>";
       output +="</tr>";
   }
   
   uiComment.setContent(output); 
}

{/literal}
// -->
</script>
