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

    /**
     * data_float->show comment: 1 to show comment, -1 not to show comment, other value to get default setting
     * data_int->enable commenting: 1 to show comment, -1 not to show comment, other value to get default setting
     * @see kernel/classes/eZDataType#objectAttributeContent($objectAttribute)
     */
    function objectAttributeContent( $objectAttribute )
    {
        $ini = eZINI::instance( 'ezcomments.ini' );
        $defaultShown = $ini->variable( 'GlobalSettings', 'DefaultShown' );
        $defaultEnabled = $ini->variable( 'GlobalSettings', 'DefaultEnabled' );
        $showComments = false;
        switch ( $objectAttribute->attribute( 'data_float' ) )
        {
            case 1:
                $showComments = true;
                break;
            case -1:
                $showComments = false;
                break;
            default:
                $showComments = $defaultShown === 'true'; 
                break;
        }
        $enableComment = false;
        switch ( $objectAttribute->attribute( 'data_int' ) )
        {
            case 1:
                $enableComment = true;
                break;
            case -1:
                $enableComment = false;
                break;
            default:
                $enableComment = $defaultEnabled === 'true'; 
                break;
        }
        $result = array(
            'show_comments' => $showComments,
            'enable_comment' => $enableComment
        );
        return $result;
    }

    /**
     * put the option enabled of ezcomcomment into  data_int of contentobjectattribute
     *
     * @see kernel/classes/eZDataType#fetchObjectAttributeHTTPInput($http, $base, $objectAttribute)
     */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $enabledName = $base . '_ezcomcomments_enabled_' . $contentObjectAttribute->attribute( 'id' );
        $shownName = $base . '_ezcomcomments_shown_' . $contentObjectAttribute->attribute( 'id' );
        $enabledValue = -1;
        $shownValue = -1;
        if ( $http->hasPostVariable( $enabledName ) )
        {
            $enabledValue = 1;
        }
        if ( $http->hasPostVariable( $shownName ) )
        {
            $shownValue = 1;
        }
        $contentObjectAttribute->setAttribute( 'data_float', $shownValue );
        $contentObjectAttribute->setAttribute( 'data_int', $enabledValue );
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