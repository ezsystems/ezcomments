<?php
//
// Created on: <07-Jan-2010 13:25:00 xc>
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
$tpl = templateInit();
$user = eZUser::currentUser();
$http = eZHttpTool::instance();

// get the action parameter
$Module = $Params['Module'];
$commentID = $Params['CommentID'];
// fetch comment object
if( is_null( $commentID ) || $commentID == '' )
{
    eZDebug::writeError( 'The parameter comment id is null!', 'ezcomments' );
    return;
}
if( !is_numeric( $commentID ) )
{
    eZDebug::writeError( 'The parameter comment id is not a number!', 'ezcomments' );
    return;
}
$comment = ezcomComment::fetch( $commentID );
if( is_null( $comment ) )
{
    eZDebug::writeError( 'The comment doesn\'t exist!', 'ezcomments' );
    return;
}

//check the permission
$contentObject = $comment->contentObject();
$languageID = $comment->attribute( 'language_id' );
$languageCode = eZContentLanguage::fetch( $languageID )->attribute( 'locale' );
$canEdit = false;
$canEditResult = ezcomPermission::hasAccessToFunction( 'edit', $contentObject, $languageCode, $comment );
$canEdit = $canEditResult['result'];
$tpl->setVariable( 'can_edit', $canEdit );

$contentID = $comment->attribute( 'contentobject_id' ) . '_' . $languageID;
$notified = ezcomSubscription::exists( $contentID, 'ezcomcomment', $comment->attribute( 'email' ) );
$tpl->setVariable( 'notified', $notified );

if( !$canEdit )
{
    $Result['path'] = array( array( 'url' => false,
                                    'text' => ezi18n( 'extension/ezcomments/edit', 'Edit comment' ) ) );
    $Result['content'] = $tpl->fetch( 'design:comment/edit.tpl' );
    return $Result;
}

if( $Module->isCurrentAction( 'UpdateComment' ) )
{
    //1. get the form values
    $title = $http->postVariable( 'CommentTitle' );
    $name = $http->postVariable( 'CommentName' );
    $website = $http->postVariable( 'CommentWebsite' );
    $email = $http->postVariable( 'CommentEmail' );
    $content = $http->postVariable( 'CommentContent' );
    $clientNotified = false;
    if( $http->hasPostVariable( 'CommentNotified' ) )
    {
        if( $http->postVariable( 'CommentNotified' ) == 'on' )
        {
            $clientNotified = true;
        }
    }

    $comment->setAttribute( 'title', $title );
    $comment->setAttribute( 'url', $website );
    $comment->setAttribute( 'text', $content );
    $comment->setAttribute( 'notification', $notified );
    $time = time();
    $comment->setAttribute( 'modified', $time );
    $commentManager = ezcomCommentManager::instance();

    $existSusbcription = ezcomSubscription::exists( $contentID, 'ezcomcomment', $comment->attribute( 'email' ) );
    $updateResult = null;
    if( $clientNotified == $notified )
    {
        $updateResult = $commentManager->updateComment( $comment, null, $time );
    }
    else
    {
        $updateResult = $commentManager->updateComment( $comment, null, $time, $clientNotified );
    }
    if( $updateResult !== true )
    {
        $tpl->setVariable( 'message', ezi18n( 'extension/ezcomments/edit', 'Updating failed!') . $updateResult );
    }
    else
    {
        //clean up cache
        eZContentCacheManager::clearContentCache( $contentObject->attribute( 'id' ) );
        $redirectionURI = $http->postVariable('ezcomments_comment_redirect_uri');
        $Module->redirectTo( $redirectionURI );
    }
    return showComment( $comment, $tpl );
}
else if( $Module->isCurrentAction('Cancel') )
{
     $redirectionURI = $http->postVariable('ezcomments_comment_redirect_uri');
     $Module->redirectTo( $redirectionURI );
}
else
{
     $redirectURI = null;
     if ( $http->hasSessionVariable( "LastAccessesURI" ) )
     {
         $redirectURI = $http->sessionVariable( 'LastAccessesURI' );
     }
     else
     {
         $redirectURI = '/comment/view/standard/' . $comment->attribute( 'contentobject_id' );
         $redirectURI = eZURI::transformURI( $redirectURI );
     }
     $tpl->setVariable( 'redirect_uri', $redirectURI );
     return showComment( $comment, $tpl );
}

/**
 *
 * @param $comment
 * @param $tpl
 * @return array
 */
function showComment( $comment, $tpl )
{
    $tpl->setVariable( 'comment', $comment );
    $Result = array();
    $Result['path'] = array( array( 'url' => false,
                                        'text' => ezi18n( 'extension/ezcomments/edit', 'Edit comment' ) ) );
    $Result['content'] = $tpl->fetch( 'design:comment/edit.tpl' );
    return $Result;
}

?>