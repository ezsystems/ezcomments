<?php
//
// Created on: <09-Dec-2009 00:00:00 xc>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Comments extension for eZ Publish
// SOFTWARE RELEASE: 1.0-1
// COPYRIGHT NOTICE: Copyright (C) 2009 eZ Systems AS
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
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/**
 *  Logic for view notification to set user notifications
 */
require_once( 'kernel/common/template.php' );

$http = eZHTTPTool::instance();

$mode = $Params['ViewMode'];

// fetch the content object
if( !is_numeric( $Params['ContentObjectID'] ) )
{
    eZDebug::writeError( 'The parameter ContentObjectID is not a number!', 'ezcomments' );
    return;
}
$contentObjectID = (int)$Params['ContentObjectID'];
$contentObject = eZContentObject::fetch( $contentObjectID );

// fetch the language
$ini =eZINI::instance();
$languageCode = $ini->variable( 'RegionalSettings', 'Locale' );
$language = eZContentLanguage::fetchByLocale( $languageCode );
$languageID = $language->attribute( 'id' );

// fetch the content object attribute
$objectAttributes = $contentObject->fetchDataMap( false, $languageCode );
$objectAttribute = null;
foreach( $objectAttributes as $attribute )
{
    if( $attribute->attribute( 'data_type_string' ) === 'ezcomcomments' )
    {
        $objectAttribute = $attribute;
        break;
    }
}

// if there is no ezcomcomments attribute inside the content, return
if( is_null( $objectAttribute ) )
{
    eZDebug::writeError( 'The object doesn\'t have a ezcomcomments attribute!', 'ezcomments' );
    return;
}

$tpl = templateInit();
$tpl->setVariable( 'contentobject', $contentObject );
$tpl->setVariable( 'objectattribute', $objectAttribute );
$tpl->setVariable( 'language_id', $languageID );
$tpl->setVariable( 'language_code', $languageCode );

$canAdd = false;
$canAddResult = ezcomPermission::hasAccessToFunction( 'add', $contentObject, $languageCode );
$canAdd = $canAddResult['result'];

$canRead = false;
$canReadResult = ezcomPermission::hasAccessToFunction( 'read', $contentObject, $languageCode );
$canRead = $canReadResult['result'];

if( $mode == 'ajax' )
{
    $tpl->setVariable( 'contentobject_id', $contentObjectID );
    $tpl->setVariable( 'enabled', $objectAttribute->attribute( 'data_int' ) );
    $tpl->setVariable( 'shown', $objectAttribute->attribute( 'data_float' ) );

    $Result = array();
    $Result['content'] = $tpl->fetch( 'design:comment/ajax/view.tpl' );
    $Result['path'] = array( array( 'url' => false,
                                    'text' => ezi18n( 'extension/ezcomments/view', 'View comment' ) ) );
    return $Result;
}
else if( $mode == 'standard' )
{

     $user = eZUser::currentUser();
     $userID = $user->attribute( 'contentobject_id' );
     $Module = $Params['Module'];

     $Page = null;
     if( !is_null( $Params['Page'] ) )
     {
         if( !is_numeric( $Params['Page'] ) )
         {
             eZDebug::writeError( 'The parameter for page is not a number!', 'ezcomments' );
             return;
         }
         else
         {
            $Page = $Params['Page'];
         }
     }
     else
     {
         $Page = 1;
     }

     $tpl->setVariable( 'can_add', $canAdd );
     $tpl->setVariable( 'can_read', $canRead );
     // get the comment list
     $count = ezcomComment::countByContent( $contentObjectID, $languageID );

     $ezcommentsINI = eZINI::instance( 'ezcomments.ini' );
     $defaultNumPerPage = $ezcommentsINI->variable( 'CommentSettings', 'NumberPerPage' );
     $offset =  ( $Page - 1 ) * $defaultNumPerPage;

     if( $offset > $count || $offset < 0 )
     {
         eZDebug::writeError( 'Offset overflowed!', 'ezcomments' );
         return;
     }

     $length = $defaultNumPerPage;

     $defaultSortField = $ezcommentsINI->variable( 'CommentSettings', 'DefaultSortField' );
     $defaultSortOrder = $ezcommentsINI->variable( 'CommentSettings', 'DefaultSortOrder' );
     $sorts = array( $defaultSortField => $defaultSortOrder );

     $comments = ezcomComment::fetchByContentObjectID( $contentObjectID, $languageID, $sorts, $offset, $length);

     $tpl->setVariable( 'comments', $comments );
     $tpl->setVariable( 'total_count', $count );
     $tpl->setVariable( 'total_page', ceil( $count / $defaultNumPerPage) );
     $tpl->setVariable( 'current_page', $Page );
     $tpl->setVariable( 'number_per_page', $defaultNumPerPage );

     $Result['content'] = $tpl->fetch( 'design:comment/view/view.tpl' );
     $Result['path'] = array( array( 'url' => false,
                                    'text' => ezi18n( 'extension/ezcomments/view', 'View comment' ) ) );
     return $Result;
}
?>