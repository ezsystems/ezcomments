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
            ezcommentsCommentView.events.fire("load");
        }
   }

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
            pageStr += "1 ";
            if((currentPage-pageSpace-1)>1)
            {
                pageStr+="...";
            }
            for(var i=currentPage-1;i>(currentPage - pageSpace);i--)
            {
                pageStr+=" " + i + " ";
            }
        }
        else
        {
            for(var i=pageSpace; i>0; i--)
            {
                if((currentPage-i)>0)
                {
                    pageStr+=" " + currentPage-i + " ";
                }
            }
        }
        pageStr += " "+currentPage+" ";
        if ( (currentPage + pageSpace) >= totalPage )
        {
            for(var i = (currentPage + 1);i <= totalPage ;i++)
            {
                pageStr+=" " + i + " ";
            }
        }
        else
        {
            for(var i=currentPage+1; i<=currentPage+pageSpace; i++)
            {
                pageStr +=" " + i + " ";
            }
            if((totalPage-currentPage-pageSpace)>1)
            {
                pageStr += "..."
            }
            pageStr+=totalPage;
        }
        
        output+=pageStr;
        
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
        
        output+="</span></p>";
        viewPage.setContent(output);
        Y.on('click', jumpPage, '#ezcomments_comment_view_page a')
   });
});

{/literal}
// -->
</script>
