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
 *  Logic for view notification to set set user notifications
 */
require_once( 'kernel/common/template.php' );

$http = eZHTTPTool::instance();

$mode = $Params['ViewMode'];

// to do: change the language using parameter
$language = 'eng-US';
$languageID =3;

$contentObjectID = (int)$Params['ContentObjectID'];
$contentObject = eZContentObject::fetch( $contentObjectID );
$objectAttributes = $contentObject->fetchDataMap( false, $language );
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
if( $mode == 'ajax' )
{
    $tpl->setVariable( 'contentobject_id', $contentObjectID );
    $tpl->setVariable( 'enabled', $objectAttribute->attribute( 'data_int' ) );
    $tpl->setVariable( 'shown', $objectAttribute->attribute( 'data_float' ) );
    $tpl->setVariable( 'language_id', $languageID );
    
    $Result = array();
    $Result['content'] = $tpl->fetch( 'design:comment/view.tpl' );
    $Result['path'] = array( array( 'url' => false,
                                    'text' => ezi18n( 'comment/view', 'View comment' ) ) );
    return $Result;
}
else if( $mode == 'standard' )
{
     //check the permission
     
     $user = eZUser::currentUser();
     $userID = $user->attribute( 'contentobject_id' );
     $Module = $Params['Module'];

     if( $Module->isCurrentAction( 'PostComment' ) )
     {
         // if the comment is not shown or enables commenting
         if( ( $objectAttribute->attribute( 'data_float' ) != '1.0' ) ||
                 ( $objectAttribute->attribute( 'data_int' ) != 1 ) )
         {
              eZDebug::writeError( 'Adding comment error, comment not shown or diabled!', 'Add comment' );
              return;
         }
         // validate data
         $comment = ezcomComment::create();
         $title = $http->variable( 'ezcomments_comment_view_addcomment_title' );
         $comment->setAttribute( 'title', $title );
         $name = $http->variable( 'ezcomments_comment_view_addcomment_name' );
         $comment->setAttribute( 'name', $name );
         $website = $http->variable( 'ezcomments_comment_view_addcomment_website' );
         $comment->setAttribute( 'url', $website );
         $email = $http->variable( 'ezcomments_comment_view_addcomment_email' );
         $comment->setAttribute( 'email', $email );
         $content = $http->variable( 'ezcomments_comment_view_addcomment_content' );
         $comment->setAttribute( 'text', $content );
         $comment->setAttribute( 'contentobject_id', $contentObjectID );
         $comment->setAttribute( 'language_id', $languageID );
         $validateResult = ezcomComment::validateInput( $comment );
         if( $validateResult !== true )
         {
             $tpl->setVariable( 'hasError', true );
             $tpl->setVariable( 'errorMessage', $validateResult );
         }
         else
         {
             // deal with cookie
             if( $http->hasVariable( 'ezcomments_comment_view_addcomment_rememberme' ) )
             {
                if( $http->variable( 'ezcomments_comment_view_addcomment_rememberme' ) == 'on' )
                {
                   // cookie expire data 1 year
                   $expireTime = time() + 60*60*24*365;
                   //save name, email, website into cookie
                   setcookie( 'ezcommentsRemember', 1, $expireTime );
                   setcookie( 'ezcommentsName', $comment->attribute( 'name' ), $expireTime );
                   setcookie( 'ezcommentsWebsite', $comment->attribute( 'url' ), $expireTime );
                   setcookie( 'ezcommentsEmail', $comment->attribute( 'email' ), $expireTime );
                }
                else
                {
                $deleteTime = time() - 60;
                setcookie( 'ezcommentsRemember', 0, $deleteTime );
                setcookie( 'ezcommentsName', '', $deleteTime );
                setcookie( 'ezcommentsWebsite', '', $deleteTime );
                setcookie( 'ezcommentsEmail', '', $deleteTime );
                }
             }
             // add into database
             $commentInput = array();
             $commentInput['title'] = $comment->attribute( 'title' );
             $commentInput['name'] = $comment->attribute( 'name' );
             $commentInput['url'] = $comment->attribute( 'url' );
             $commentInput['text'] = $comment->attribute( 'text' );
             $commentInput['email'] = $comment->attribute( 'email' );
             if( $http->variable( 'ezcomments_comment_view_addcomment_content' ) == 'on')
             {
                 $commentInput['notified'] = true;
             }
             else
             {
                 $commentInput['notified'] = false;
             }
             $addingResult = ezcomComment::addComment( $commentInput, $user, $contentObjectID, $languageID, time() );
             
             // redirect URL
             if( $addingResult['result'] === true )
             {
                 
             }
             else
             {
                 $tpl->setVariable( 'hasError', true );
                 $tpl->setVariable( 'errorMessage', $validateResult['errors'] );
             }
         }
     }
     $Page = $Params['Page'];
     if( is_null( $Page ) )
     {
         $Page = 1;
     }
    // to do:  consider
    //If the content can not be read by the user, the comment can't be as well
    //     if( !$contentObject->canRead() )
    //     {
    //         
    //     }
     
     // to do: consider the comment view cache
     // get the comment list
     $count = ezcomComment::countByContent( $contentObjectID );
     
     $ezcommentsINI = eZINI::instance( 'ezcomments.ini' );
     $defaultNumPerPage = $ezcommentsINI->variable( 'commentSettings', 'NumberPerPage' );
     
     $offset =  ( $Page - 1 ) * $defaultNumPerPage;
     
     if( $offset > $count || $offset < 0 )
     {
         $tpl->setVariable( 'hasError', true );
         $tpl->setVariable( 'errorMessage', ezi18n( 'comment/view/addcomment', 'offset overflowed' ) );
     }
     
     $length = $defaultNumPerPage;
     
     $defaultSortField = $ezcommentsINI->variable( 'commentSettings', 'DefaultSortField' );
     $defaultSortOrder = $ezcommentsINI->variable( 'commentSettings', 'DefaultSortOrder' );
     $sorts = array( $defaultSortField => $defaultSortOrder );
     
     $comments = ezcomComment::fetchByContentObjectID( $contentObjectID, $sorts, $offset, $length);
     //is anonymous
     
     
     //notified option
     $notified = false;
     $defaultNotified = $ezcommentsINI->variable( 'ezcommentsSettings', 'EnableNotification' );
     $notified = null;
     $name = '';
     $website = '';
     $email = ''; 
     $remember = true;
     if( $user->isAnonymous() )
     {
         if( isset( $_COOKIE['ezcommentsName'] ) )
         {
             $name = $_COOKIE['ezcommentsName'];
         }
         if( isset( $_COOKIE['ezcommentsWebsite'] ) )
         {
             $website = $_COOKIE['ezcommentsWebsite'];
         }
         if( isset( $_COOKIE['ezcommentsEmail'] ) )
         {
             $email = $_COOKIE['ezcommentsEmail'];
         }
         if( isset( $_COOKIE['ezcommentsNotified'] ) )
         {
             $notified = $_COOKIE['ezcommentsNotified'];
         }
         else
         {
             $notified = $defaultNotified;
         }
         if( isset( $_COOKIE['ezcommentsRemember'] ) )
         {
            $remember = ( boolean )$_COOKIE['ezcommentsRemember'];
         }
     }
     else
     {
         $name = $user->attribute( 'name' );
         $email = $user->attribute( 'email' );
         $notified = $defaultNotified;
     }
     $tpl->setVariable( 'comments', $comments );
     $tpl->setVariable( 'total_count', $count );
     $tpl->setVariable( 'total_page', ceil( $count / $defaultNumPerPage) );
     $tpl->setVariable( 'current_page', $Page );
     $tpl->setVariable( 'number_per_page', $defaultNumPerPage );
     $tpl->setVariable( 'is_anonymous', $user->isAnonymous() );
     $tpl->setVariable( 'comment_name', $name );
     $tpl->setVariable( 'comment_email', $email );
     $tpl->setVariable( 'comment_website', $website );
     $tpl->setVariable( 'comment_notified', $notified );
     $tpl->setVariable( 'comment_remember', $remember );
     
     $Result['content'] = $tpl->fetch( 'design:comment/view_standard.tpl' );
     $Result['path'] = array( array( 'url' => false,
                                    'text' => ezi18n( 'comment/view', 'View comment' ) ) );
     return $Result;
}
?>