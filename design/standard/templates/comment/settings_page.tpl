<div id="ezcomments_comment_page" class="ezcomment-comment-page">
    <p>
    <span>Total comments </span>
    <span id="ezcomments_comment_page_totalnumber"></span>
    <span>,Page</span>
    <span id="ezcomments_comment_page_index">
    <span id="currentpage"></span>
    /
    <span id="totalpage"></span>
    <span id="ezcomments_comment_page_buttons" class="ezcomments-comment-page-button">
        <a href="javascript:click(this)" id="ezcomments_comment_page_first" action="first">|<</a> 
        <a href="javascript:click(this)" id="ezcomments_comment_page_previous" action="previous"><</a> 
        <a href="javascript:click(this)" id="ezcomments_comment_page_next" action="next">></a> 
        <a href="javascript:click(this)" id="ezcomments_comment_page_last" action="last">>|</a>
    </span>
    </p>
</div>

<script type="text/javascript">
<!--
{literal}
  
YUI( YUI3_config ).use('node', 'json-stringify', 'json-stringify', 'io-ez', 'event-custom-complex', function( Y )
{
    //register default page painting
     var ezcommentsFillCommentPage = function()
    {
        var argObject = ezcommentsComment.argObject;
        var count = ezcommentsComment.currentData.total_count;
        
        var commentPage = Y.get( '#ezcomments_comment_page' );
        var currentPage = Y.get('#ezcomments_comment_page #currentpage');
        currentPage.setContent( argObject.targetPage );
        
        var numberPerPage = argObject.numberPerPage;
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
        var argObject = ezcommentsComment.argObject;
        var currentPage = argObject.targetPage;
        var totalNumber = ezcommentsComment.currentData.total_count;
        var numberPerPage = argObject.numberPerPage;
        var totalPages = Math.ceil( totalNumber / numberPerPage );
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
                    jumpToPage( currentPage - 1 );
                }
                break;
            case "next":
                if ( currentPage < totalPages )
                {
                    jumpToPage( currentPage + 1 );
                }
                break;
            case "last":
                if ( currentPage < totalPages )
                {
                    jumpToPage( totalPages );
                }
                break;
            default:
                //to do: if action is number
                jumpToPage( action );
                break;
        }
    }
    
    var jumpToPage = function( targetPage )
    {
       var argObject = ezcommentsComment.argObject;
       var offset = ( targetPage - 1 ) * argObject.numberPerPage;
       argObject.offset = offset;
       argObject.length = argObject.numberPerPage;
       argObject.targetPage = targetPage;
       ezcommentsComment.refresh();
    }
    
    var pagingButtons = Y.all('#ezcomments_comment_page_buttons a');
    pagingButtons.on('click',ezcomments_jumpPage);
});

{/literal}
// -->
</script>