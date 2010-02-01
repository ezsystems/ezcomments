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

// We are reloading the debug.ini settings here to get override values from extensions
$ini = eZINI::instance( 'debug.ini' );
$ini->loadCache();

require_once( 'kernel/common/template.php' );
$tpl = templateInit();

$module = $Params['Module'];
$http = eZHTTPTool::instance();

$Result = array();
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'ezcomments/add', 'Add comment' ) ) );
$Result['content'] ='';

if( $http->hasVariable( 'RedirectURI' ) )
{
    $redirectURI = $http->variable( 'RedirectURI' );
}

if( $http->hasVariable( 'BackButton' ) &&
      $http->variable( 'BackButton') == 'Back')
{
     $module->redirectTo( $redirectURI );
     return;
}


$user = eZUser::currentUser();

if ( $module->isCurrentAction( 'AddComment' ) )
{
}

// Which object is our comment attached to
if( !$http->hasPostVariable( 'ContentObjectID' ) )
{
    eZDebug::writeError( 'No content object id is provided', 'ezcomments' );
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}
$contentObjectId = (int)$http->postVariable('ContentObjectID');

// Either use provided language code, or fallback on siteaccess default
if ( $http->hasPostVariable( 'CommentLanguageCode' ) )
{
    $languageCode = $http->postVariable( 'CommentLanguageCode' );
    $language = eZContentLanguage::fetchByLocale( $languageCode );
    if ( $language === false )
    {
        eZDebug::writeError( "The language code [$languageCode] given is not valid in the system.", 'ezcomments' );
        return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
    }
}
else
{
    $defaultLanguage = eZContentLanguage::topPriorityLanguage();
    $languageCode = $defaultLanguage->attribute( 'locale' );
}

$contentObject = eZContentObject::fetch( $contentObjectId );
if ( !($contentObject instanceof eZContentObject ) )
{
    eZDebug::writeError( 'No content object exists for the given id.', 'ezcomments' );
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$dataMap = $contentObject->fetchDataMap( false, $languageCode );
$foundCommentAttribute = false;
foreach( $dataMap as $attr )
{
    if( $attr->attribute( 'data_type_string' ) === 'ezcomcomments' )
    {
        $foundCommentAttribute = $attr;
        break;
    }
}

// if there is no ezcomcomments attribute inside the content, return
if( !$foundCommentAttribute )
{
    eZDebug::writeError( "Content object with id [$contentObjectId], does not contain an ezcomments attribute.", 'ezcomments' );
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

//check permission
$canAddComment = ezcomPermission::hasAccessToFunction( 'add', $contentObject, $languageCode,  null, null, $contentObject->mainNode() );
if ( !$canAddComment['result'] )
{
    eZDebug::writeWarning( 'No access to adding comments.', 'ezcomments' );
    return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}

// Check to see if commenting is turned on, on the object level
$commentContent = $foundCommentAttribute->content();
if( !$commentContent['show_comments'] || !$commentContent['enable_comment'] )
{
    $tpl->setVariable( 'error_message', ezi18n( 'ezcomments/add', 'Commenting has been turned off for this content.'  ) );
}
else
{
    // Validate given input date against form setup
    $formTool = ezcomFormTool::instance();
    $formStatus = $formTool->checkVars();

    if ( !$formStatus )
    {
        // missing form data
    }

     $title = $formTool->fieldValue( 'title' );
     $name = $formTool->fieldValue( 'name' );
     $website = $formTool->fieldValue( 'website' );
     $email = $formTool->fieldValue( 'email' );
     $content = $formTool->fieldValue( 'comment' );

     $comment = ezcomComment::create();
     $comment->setAttribute( 'title', $title );
     $comment->setAttribute( 'name', $name );
     $comment->setAttribute( 'url', $website );
     $comment->setAttribute( 'email', $email );
     $comment->setAttribute( 'text', $content );

     $languageId = eZContentLanguage::idByLocale( $languageCode );

     $existingNotification = ezcomSubscription::exists( $contentObjectId,
                                                        $languageId,
                                                        'ezcomcomment',
                                                        $email );

     if( $http->hasPostVariable( 'CommentNotified' ) &&
         $http->postVariable( 'CommentNotified' ) == 'on' )
     {
         $notification = true;
     }
     else
     {
         $notification = false;
     }


     $comment->setAttribute( 'contentobject_id', $contentObjectId );
     $comment->setAttribute( 'language_id', $languageId );
     $currentTime = time();
     $comment->setAttribute( 'user_id', $user->attribute( 'contentobject_id' ) );
     $comment->setAttribute( 'created', $currentTime );
     $comment->setAttribute( 'modified', $currentTime );

     $commentManager = ezcomCommentManager::instance();
     $commentManager->tpl = $tpl;


     // toggle notification state on change in state
     if ( $notification == $existingNotification )
     {
         $addingResult = $commentManager->addComment( $comment, $user );
     }
     else
     {
         $addingResult = $commentManager->addComment( $comment, $user, $notification );
     }

     if( $addingResult === true )
     {
         //remember cookies
         if( $user->isAnonymous() )
         {
             $cookieManager = ezcomCookieManager::instance();
             if( $http->hasPostVariable( 'CommentRememberme') &&
                 $http->postVariable( 'CommentRememberme' ) == 'on' )
             {
                 $cookieManager->storeCookie( $comment );
             }
             else
             {
                 $cookieManager->clearCookie();
             }
         }

         eZContentCacheManager::clearContentCacheIfNeeded( $contentObjectId );

         $tpl->setVariable( 'success', true );
         $tpl->setVariable( 'redirect_uri', $redirectURI );
     }

     else
     {
        $tpl->setVariable( 'error_message', $addingResult );
     }
}


$Result['content'] = $tpl->fetch( 'design:comment/add.tpl' );
return $Result;
?>