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
     return $module->redirectTo( $redirectURI );
}

if ( $module->isCurrentAction( 'AddComment' ) )
{
    $contentCheck = ezcomPostHelper::checkContentRequirements( $module, $http );
    extract( $contentCheck );

    // Check to see if commenting is turned on, on the object level
    $commentContent = ezcomPostHelper::checkCommentPermission( $contentObject, $languageCode, $foundCommentAttribute );
    if( !$commentContent['show_comments'] || !$commentContent['enable_comment'] )
    {
        $tpl->setVariable( 'error_message', ezi18n( 'ezcomments/add', 'Commenting has been turned off for this content.'  ) );
        $Result['content'] = $tpl->fetch( 'design:comment/add.tpl' );
        return $Result;
    }
    else
    {
        // Validate given input date against form setup
        $formTool = ezcomFormTool::instance();
        $formStatus = $formTool->checkVars();

        if ( !$formStatus )
        {
            // missing form data
            $tpl->setVariable( 'error_message', ezi18n( 'ezcomments/add', 'There is a problem with your comment form ' ) );
            $tpl->setVariable( 'validation_messages', $formTool->messages() );
            $Result['content'] = $tpl->fetch( 'design:comment/add.tpl' );
            return $Result;
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

         $user = eZUser::currentUser();

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
             $addingResult = $commentManager->addComment( $comment, $user, null, $notification );
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
}
else
{
    $tpl->setVariable( 'error_message', "You should not access this view directly" );
}



?>