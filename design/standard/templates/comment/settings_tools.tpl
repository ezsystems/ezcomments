<div id="ezcomments_comment_tool" class="ezcomments-comment-tool">
   <input type="checkbox" id="ezcomments_comment_select_all" /><span>Notify all comments</span>
   <input type="button" class="button" id="ezcomments_comment_save" value="Save" />
</div>
<script type="text/javascript">
<!--
{literal}
  
YUI( YUI3_config ).use('node', 'json-stringify', 'json-stringify', 'io-ez', 'event-custom-complex', function( Y )
{
    //1. select all
    var ezcommentsSelectAll = function( e )
    {
        var checkBox = e.target;
        var checked = checkBox.get('checked');
        // get all the checkbox in comment list
        var trs = Y.all('#ezcomments_comment_list tr');
        for( var i=0;i < trs.size(); i++)
        {
            var obj = Y.all( '#ezcomments_comment_list tr input' ).item(i);
            obj.set( 'checked', checked );
        }
    }
    Y.get('#ezcomments_comment_select_all').on('click',ezcommentsSelectAll);
    
    
      //2. save page
    var ezcommentsSave = function( e )
    {
        //get changed item
        var currentData = ezcommentsComment.currentData;
         
        var commentsTR = Y.all( '#ezcomments_comment_list tr' );
        var argArray = new Array();
        for( var i in currentData.comments )
        {
            var row = (currentData.comments)[i];
            var uiValue = Y.all( '#ezcomments_comment_list tr input' ).item(i).get( 'checked' );
            uiValueInt = (uiValue) ? 1 : 0; 
            if ( uiValueInt != row['notification'] )
            {
                var obj = new Object();
                obj.id = row['id'];
                obj.notification = uiValueInt;
                argArray.push( obj );
            }
        } 
        var argObject = new Object();
        argObject.rows = argArray;
        if(Y.get("#ezcomments_comment_hashstring")!=null)
        {
          argObject.hashString = Y.get("#ezcomments_comment_hashstring").get('value');
        }
        var args=Y.JSON.stringify(argObject);
        Y.io.ez( 'comment::update_notification_comment', {
            data: 'args='+args,
            on: {success: function( id,r )
                { 
                    if ( r.responseJSON.error_text )
                    {
                        ezcommentsComment.showMessage( r.responseJSON.error_text );
                        ezcommentsComment.repaint();
                    }   
                    else
                    {
                        var resContent = r.responseJSON.content;
                        ezcommentsComment.showMessage( resContent );
                    }
                }
            }
        });
    }
    
    Y.get('#ezcomments_comment_save').on('click',ezcommentsSave);
    
});
{/literal}
// -->
</script>