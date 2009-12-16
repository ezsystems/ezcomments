<div id="ezcomments_comment_view_page" class="ezcomments-comment-view-page">

</div>
<br />
<script type="text/javascript">

<!--
{literal}
YUI( YUI3_config ).use('node', 'json-stringify', 'io-ez', 'event-custom-complex', function( Y )
{
   var jumpPage = function(e)
   {
        if(e.currentTarget.get("tagName").toLowerCase()=="a")
        {
            var targetPage = parseInt(e.currentTarget.getAttribute("topage"));
            var numPerPage = ezcommentsCommentView.currentData.request.numberPerPage;
            var offset= ( targetPage - 1 ) * numPerPage;
            ezcommentsCommentView.currentData.request.offset=offset;
            ezcommentsCommentView.currentData.request.targetPage=targetPage;
            ezcommentsCommentView.refresh();
        }
   }

   //init the current page
   ezcommentsCommentView.events.on("initdata", function(){
   
        var currentLocation = location.href;
        var numberIndex = currentLocation.indexOf("#");
        if(numberIndex!=-1)
        {
            var params = currentLocation.substring(numberIndex+1);
            if(params.match(/^p\/\d+$/))
            {
                var targetPage=params.substring(params.indexOf("p/")+2);
                if(parseInt(targetPage)>=1)
                {
                    ezcommentsCommentView.currentData.request.targetPage=parseInt(targetPage);
                    var numberPerPage = ezcommentsCommentView.currentData.request.numberPerPage;
                    ezcommentsCommentView.currentData.request.offset=(targetPage-1)*numberPerPage;
                }
            }
        }
    
   });

   // Paint page after loading comment
   ezcommentsCommentView.events.on("commentloaded", function(e){
        var total_count = ezcommentsCommentView.currentData.result.total_count;
        var request =  ezcommentsCommentView.currentData.request;
        var currentPage = request.targetPage;
        var numberPerPage = request.numberPerPage;
        var totalPage = Math.ceil(total_count/numberPerPage);
        if(totalPage==1)
        {
            return;
        }
        
        var viewPage = Y.get('#ezcomments_comment_view_page');
        var output = "<p><span>Total comments:</span>";
        output += "<span>"+total_count+",</span> ";
        output += "<span>Pages:</span>";
        
        var pageSpace = 2;
        var pageStr = "";
        if ( (currentPage - pageSpace) > 1 )
        {
            pageStr += "<a topage='1' href='#'>1</a> ";
            if((currentPage-pageSpace-1)>1)
            {
                pageStr+=" ... ";
            }
            for(var i=currentPage - pageSpace;i<currentPage;i++)
            {
                pageStr+=" <a topage='"+i+"' href='#'>" + i + "</a> ";
            }
        }
        else
        {
            for(var i=pageSpace; i>0; i--)
            {
                if((currentPage-i)>0)
                {
                    pageStr+=" <a topage='" + (currentPage-i) +"' href='#'>" + (currentPage-i) + "</a> ";
                }
            }
        }
        pageStr += " "+currentPage+" ";
        if ( (currentPage + pageSpace) >= totalPage )
        {
            for(var i = (currentPage + 1);i <= totalPage ;i++)
            {
                pageStr+=" <a topage='"+ i +"' href='#'>" + i + "</a> ";
            }
        }
        else
        {
            for(var i=currentPage+1; i<=currentPage+pageSpace; i++)
            {
                pageStr +=" <a topage='"+ i +"' href='#'>" + i + "</a> ";
            }
            if((totalPage-currentPage-pageSpace)>1)
            {
                pageStr += " ... "
            }
            pageStr+="<a topage='"+ totalPage +"' href='#'>"+totalPage+"</a>";
        }
        
        if(currentPage>1)
        {
            output += "<span> <a href=\"#\" topage=\""+(currentPage-1)+"\" title=\"Previous page\"><</a> </span>";
        }
        
        output+=pageStr;
        if(currentPage<totalPage)
        {
         output += "<span> <a href=\"#\" title=\"Next page\" topage=\""+(currentPage+1)+"\">></a> </span>";
        }
        
        /*
        if(currentPage!=1)
        {
        output += "<span><a href=\"#\" topage=\"1\" title=\"First page\">|<</a></span>";
        }
        output += "<span>";
        for(var i=1; i<=parseInt(totalPage); i++)
        {
            if(i==currentPage)
            {
              output+=" "+i+" ";
            }
            else
            {
                output+=" <a href=\"#\" topage=\""+i+"\">"+i+"</a> ";
            }
        }
        if(currentPage!=totalPage)
        {
        output += "<span><a href=\"#\" title=\"Last page\" topage=\""+totalPage+"\">>|</a></span>";
        }
        */
        output+="</span></p>";
        viewPage.setContent(output);
        Y.on('click', jumpPage, '#ezcomments_comment_view_page a')
   });
});

{/literal}
// -->
</script>
