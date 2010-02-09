<?php
/**
 * File containing ezcomCommentsType class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

class ezcomCommentsType extends eZDataType
{
    const DATA_TYPE_STRING = "ezcomcomments";

    /*!
     Construction of the class, note that the second parameter in eZDataType
     is the actual name showed in the datatype dropdown list.
    */
    function __construct()
    {
        parent::__construct( self::DATA_TYPE_STRING, ezi18n( 'extension/ezcomment/datatype', 'Comments', 'Datatype name'),
                             array( 'serialize_supported' => true) );
    }

    /**
     * store the contentobjectattribute into database
     * @see kernel/classes/eZDataType#storeObjectAttribute($objectAttribute)
     */
    function storeObjectAttribute( $contentObjectAttribute )
    {
        return true;
    }

    function objectAttributeContent( $objectAttribute )
    {
        $result = array(
            'enable_comment' => ( $objectAttribute->attribute( 'data_int' ) == 1 ),
            'show_comments' => ( $objectAttribute->attribute( 'data_float' ) == 1 )
        );
        return $result;
    }

    /**
     * put the option enabled of ezcomcomment into  data_int of contentobjectattribute
     *
     *
     * @see kernel/classes/eZDataType#fetchObjectAttributeHTTPInput($http, $base, $objectAttribute)
     */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $enabledName = $base . '_ezcomcomments_enabled_' . $contentObjectAttribute->attribute( 'id' );
        $shownName = $base . '_ezcomcomments_shown_' . $contentObjectAttribute->attribute( 'id' );
        $enabledValue = 0;
        $shownValue = -1;
        if ( $http->hasPostVariable( $enabledName ) )
        {
            $enabledValue = 1;
        }
        if ( $http->hasPostVariable( $shownName ) )
        {
            $shownValue = 1;
        }
        $contentObjectAttribute->setAttribute( 'data_int', $enabledValue );
        $contentObjectAttribute->setAttribute( 'data_float', $shownValue );
        return true;
    }

    function isIndexable()
    {
        return true;
    }


    function sortKeyType()
    {
        return '';
    }

    function deleteStoredObjectAttribute( $contentObjectAttribute, $version = null )
    {

    }
}

eZDataType::register( ezcomCommentsType::DATA_TYPE_STRING, 'ezcomCommentsType' );
?>