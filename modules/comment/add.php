<?php 
//
// Created on: <13-Jan-2010 11:14:00 xc>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Comments extension for eZ Publish
// SOFTWARE RELEASE: 1.0-1
// COPYRIGHT NOTICE: Copyright (C) 2010 eZ Systems AS
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
require_once( 'kernel/common/template.php' );

//TODO:check the permission

$module = $Params['Module'];

$http = eZHTTPTool::instance();

$redirectURI = $http->variable( 'RedirectURI' );

if( $module->isCurrentAction( 'Back' ) )
{
     $module->redirectTo( $redirectURI );
     return;
}

$user = eZUser::currentUser();
$userID = $user->attribute( 'contentobject_id' );

$contentObjectID = null;
if( $http->hasVariable( 'ContentObjectID' ) )
{
    $contentObjectID = $http->variable('ContentObjectID');
}
else
{
    return;
}

$languageID = null;
if( $http->hasVariable( 'LanguageID' ) )
{
    $languageID = $http->variable('LanguageID');
}
else
{
    return;
}
if( !is_numeric( $contentObjectID ) )
{
    eZDebug::writeError( 'The parameter ContentObjectID is not a number!', 'ezcomments' );
    return;
}
if( !is_numeric( $languageID ) )
{
    eZDebug::writeError( 'The parameter LanguageID is not a number!', 'ezcomments' );
    return;
}
$contentObject = eZContentObject::fetch( $contentObjectID );

$language = eZContentLanguage::fetch( $languageID );
if( $language === false )
{
    eZDebug::writeError( 'Language doesn\'t exist!', 'ezcomments'  );
    return;
}
$languageCode = $language->attribute( 'locale' );

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

 // if the comment is not shown or enables commenting
 if( ( $objectAttribute->attribute( 'data_float' ) != '1.0' ) ||
         ( $objectAttribute->attribute( 'data_int' ) != 1 ) )
 {
      eZDebug::writeError( 'Adding comment error, comment not shown or diabled!', 'Add comment' );
      return;
 }
 
 $comment = ezcomComment::create();
 $title = $http->postVariable( 'ezcomments_comment_view_addcomment_title' );
 $comment->setAttribute( 'title', $title );
 $name = $http->postVariable( 'ezcomments_comment_view_addcomment_name' );
 $comment->setAttribute( 'name', $name );
 $website = $http->postVariable( 'ezcomments_comment_view_addcomment_website' );
 $comment->setAttribute( 'url', $website );
 $email = $http->postVariable( 'ezcomments_comment_view_addcomment_email' );
 $comment->setAttribute( 'email', $email );
 $content = $http->postVariable( 'ezcomments_comment_view_addcomment_content' );
 $comment->setAttribute( 'text', $content );
 if( $http->postVariable( 'ezcomments_comment_view_addcomment_notified' ) == 'on')
 {
     $comment->setAttribute( 'notification', true );
 }
 else
 {
     $comment->setAttribute( 'notification', false );
 }
 $comment->setAttribute( 'contentobject_id', $contentObjectID );
 $comment->setAttribute( 'language_id', $languageID );
 $currentTime = time();
 $comment->setAttribute( 'created', $currentTime);
 $comment->setAttribute( 'modified', $currentTime);
 $commentManager = ezcomCommentManager::instance();
 $commentManager->tpl = $tpl;
 $addingResult = $commentManager->addComment( $comment, $user );
 if( $addingResult === true )
 {
     //remember cookies
     if( $user->isAnonymous() )
     {
         $cookieManager = ezcomCookieManager::instance();
         if( $http->hasVariable( 'ezcomments_comment_view_addcomment_rememberme') &&
                 $http->variable( 'ezcomments_comment_view_addcomment_rememberme' ) == 'on' )
         {
             $cookieManager->storeCookie( $comment );
         }
         else
         {
             $cookieManager->clearCookie();
         }
     }
     //clear cache
     eZContentCacheManager::clearContentCache( $contentObjectID );
     $tpl->setVariable( 'success', true );
     $tpl->setVariable( 'redirect_uri', $redirectURI );
 }
 else
 {
    $tpl->setVariable( 'error_message', $addingResult );
 }
 $Result['path'] = array( array( 'url' => false,
                            'text' => ezi18n( 'extension/ezcomments/add', 'Add comment' ) ) );
 $Result['content'] = $tpl->fetch( 'design:comment/add.tpl' );
 return $Result;
?>