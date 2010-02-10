<?php
/**
 * File containing ezcomFunctionCollection class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */


class ezcomFunctionCollection
{

    public static function fetchCommentList( $contentObjectID, $languageID, $sortField, $sortOrder, $offset, $length )
    {
        $sort = array( $sortField=>$sortOrder );
        $result = ezcomComment::fetchByContentObjectID( $contentObjectID, $languageID, $sort, $offset, $length );
        return array( 'result' => $result );
    }

    public static function fetchCommentCount( $contentObjectID, $languageID )
    {
        $result = ezcomComment::countByContent( $contentObjectID, $languageID );
        return array( 'result' => $result );
    }
}

?>