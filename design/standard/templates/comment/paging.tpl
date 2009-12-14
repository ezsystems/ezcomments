 <div id="ezcomments_comment_page" class="ezcomment-comment-page" currentpage="1" totalnumber="10" totalpages="4" numberperpage="3">
        <span>Total comments </span>
        <span id="ezcomments_comment_page_totalnumber"></span>
        <span>,Page</span>
        <span id="ezcomments_comment_page_index">
        <span id="currentpage"></span>
        /
        <span id="totalpage"></span>
        <span id="ezcomments_comment_page_buttons" class="ezcomments-comment-page-button">
            <a href="#" id="ezcomments_comment_page_first" action="first">&nbsp;|<&nbsp;</a>
            <a href="#" id="ezcomments_comment_page_previous" action="previous">&nbsp;<&nbsp;</a>
            <a href="#" id="ezcomments_comment_page_next" action="next">&nbsp;>&nbsp;</a>
            <a href="#" id="ezcomments_comment_page_last" action="last">&nbsp;>|&nbsp;</a>
        </span>
</div>

<script type="text/javascript">
<!--
{literal}
  
YUI( YUI3_config ).use('node', 'json-stringify', 'json-stringify', 'io-ez', 'event-custom-complex', function( Y )
{
    //paging event test
    var ezcommentsPageEvent = function()
    {
        this.publish("commentloaded",
            {
                emitFacade: true,
                defaultFn: function(e) {
 
                }
            }
            );
    }
   
    Y.augment(ezcommentsPageEvent,Y.EventTarget);
    
    ezcommentsComment.events.addTarget( new ezcommentsPageEvent() );
 
    //register default page painting
     var ezcommentsFillCommentPage = function()
    {
        var argObject = ezcommentsComment.argObject;
        var count = ezcommentsComment.currentData.total_count;
        
        var commentPage = Y.get( '#ezcomments_comment_page' );
        var currentPage = Y.get('#ezcomments_comment_page #currentpage');
        commentPage.setAttribute( 'currentpage', argObject.targetPage );
        currentPage.setContent( argObject.targetPage );
        
  
        var numberPerPage = commentPage.getAttribute( 'numberperpage' );
        var totalPageNumber = Math.ceil( count / parseInt(numberPerPage) );

        var totalPage = Y.get('#ezcomments_comment_page #totalpage');
        totalPage.setContent( totalPageNumber );
        
        
        var totalNumber = Y.get('#ezcomments_comment_page_totalnumber');
        totalNumber.setContent( count );
    }
    ezcommentsComment.events.on("paintpage",ezcommentsFillCommentPage);
 
     // jump page
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
       var argObject = new Object();
       argObject.offset = offset;
       
       argObject.length = numberPerPage;
       argObject.targetPage = targetPage;
       ezcommentsComment.argObject = argObject;
       ezcommentsComment.refresh();
    }
    
    var pagingButtons = Y.all('#ezcomments_comment_page_buttons a');
    pagingButtons.on('click',ezcomments_jumpPage);
});

{/literal}
// -->
</script>
