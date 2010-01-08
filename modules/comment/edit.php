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
// todo: check the permission

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
if( $Module->isCurrentAction( 'UpdateComment' ) )
{
    //1. get the form values
    $title = $http->variable( 'ezcomments_comment_edit_title' );
    $name = $http->variable( 'ezcomments_comment_edit_name' );
    $website = $http->variable( 'ezcomments_comment_edit_website' );
    $email = $http->variable( 'ezcomments_comment_edit_email' );
    $content = $http->variable( 'ezcomments_comment_edit_content' );
    $notified = false;
    if( $http->hasVariable( 'ezcomments_comment_edit_notified' ) )
    {
        if( $http->variable( 'ezcomments_comment_edit_notified' ) == 'on' )
        {
            $notified = true;
        }
    }
    //2. validate input
    $clientComment = ezcomComment::create();
    $clientComment->setAttribute( 'contentobject_id', $comment->attribute( 'contentobject_id' ) );
    $clientComment->setAttribute( 'language_id', $comment->attribute( 'language_id' ) );
    $clientComment->setAttribute( 'title', $title );
    $clientComment->setAttribute( 'name', $comment->attribute( 'name' ) );
    $clientComment->setAttribute( 'url', $website );
    $clientComment->setAttribute( 'email', $comment->attribute( 'email' ) );
    $clientComment->setAttribute( 'text', $content );
    $clientComment->setAttribute( 'notification', $notified );
    $validateResult = ezcomComment::validateInput( $clientComment );
    if( $validateResult !== true )
    {
        $tpl->setVariable( 'message', $validateResult );
        return showComment( $comment, $tpl );
    }
    //3. compare the value with database and update comment
    $commentUpdated = array();
    if( $title != $comment->attribute( 'title' ) )
    {
        $commentUpdated['title'] = $title;
    }
    if( $website != $comment->attribute( 'url' ) )
    {
        $commentUpdated['url'] = $website;
    }
    if( $content != $comment->attribute( 'text' ) )
    {
        $commentUpdated['text'] = $content;
    }
    if( $notified != $comment->attribute( 'notification' ) )
    {
        $commentUpdated['notified'] = $notified;
    }
    $updateResult = ezcomComment::updateComment( $commentUpdated, $comment, $user, time() );
    if( !$updateResult )
    {
        $tpl->setVariable( 'message', ezi18n( 'comment/edit', 'Update failed') );
    }
    else
    {  
        $redirectionURI = $http->variable('ezcomments_comment_redirect_uri');
        $Module->redirectTo( $redirectionURI ); 
    }
    return showComment( $comment, $tpl );
}
else if( $Module->isCurrentAction('Cancel') )
{
     $redirectionURI = $http->variable('ezcomments_comment_redirect_uri');
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
         $redirectURI = '/comment/standard/' . $commentID;
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
                                        'text' => ezi18n( 'comment/edit', 'Edit comment' ) ) );
    $Result['content'] = $tpl->fetch( 'design:comment/edit.tpl' );
    return $Result;    
}

?>