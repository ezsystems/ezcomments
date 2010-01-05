<?php
//
// Definition of ezcomCommentType class
//
// SOFTWARE NAME: eZ Comment
// SOFTWARE RELEASE: 1.0-0
// COPYRIGHT NOTICE: Copyright (C) 2009 Bruce Morrison, 2009 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//

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
        if( $http->hasPostVariable( $enabledName ) )
        {
            $enabledValue = 1;
        }
        if( $http->hasPostVariable( $shownName ) )
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