<?php
 
class ezcomFunctionCollection
{
 
 static function fetchCommentList( $contentObjectID, $languageID, $sortField, $sortOrder, $length )
 {
    $sort = array( $sortField=>$sortOrder );
    $result = ezcomComment::fetchByContentObjectID( $contentObjectID, $languageID, $sort, 0, $length );
    return array( 'result'=>$result );
 }
 
static function fetchCommentCount( $contentObjectID, $languageID )
 {
    $result = ezcomComment::countByContent( $contentObjectID, $languageID );
    return array( 'result'=>$result );
 }
}
 
?>