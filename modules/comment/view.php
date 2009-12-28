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

//1. check permission

//2. get user's ID

//3. 

//$commentList = ezcomComment::fetchForUser( 1 );

$http = eZHTTPTool::instance();

$mode = $Params['ViewMode'];

$contentObjectID = $Params['ContentObjectID'];
$contentObject = eZContentObject::fetch( $contentObjectID );
$tpl = templateInit();
$tpl->setVariable('contentobject',$contentObject);

if( $mode == 'ajax' )
{
    $tpl->setVariable("contentobject_id",$contentObjectID);
    $tpl->setVariable("enabled",1);
    $tpl->setVariable("shown",1);
    $tpl->setVariable('language_id',2);
    
    $Result = array();
    $Result['content'] = $tpl->fetch( 'design:comment/view.tpl' );
    $Result['path'] = array( array( 'url' => false,
                                    'text' => ezi18n( '', 'View comment' ) ) );
    return $Result;
}
else if( $mode == 'standard' )
{
     //check the permission
     $user = eZUser::currentUser();
     $userID = $user->attribute( 'id' );
     $Module = $Params['Module'];
     $Page = $Params['Page'];
     if( is_null( $Page ) )
     {
         $Page = 1;
     }
     //If the content can not be read by the user, the comment can't be as well
     if( !$contentObject->canRead() )
     {
         return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel',
                                     array( 'AccessList' => $contentObject->accessList( 'read' ) ) );
     }
     
     // to do: consider the comment view cache
     // get the comment list
     $count = ezcomComment::countByContent( $contentObjectID );
     
     $ezcommentsINI = eZINI::instance( 'ezcomments.ini' );
     $defaultNumPerPage = $ezcommentsINI->variable( 'commentSettings', 'NumberPerPage' );
     
     $offset =  ( $Page - 1 ) * $defaultNumPerPage;
     
     if( $offset > $count || $offset < 0 )
     {
         // to do: define error and output
//         return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel',
//                                     array( 'AccessList' => $contentObject->accessList( 'read' ) ) );
     }
     
     $length = $defaultNumPerPage;
     
     $defaultSortField = $ezcommentsINI->variable( 'commentSettings', 'DefaultSortField' );
     $defaultSortOrder = $ezcommentsINI->variable( 'commentSettings', 'DefaultSortOrder' );
     $sorts = array( $defaultSortField => $defaultSortOrder );
     
     $comments = ezcomComment::fetchByContentObjectID( $contentObjectID, $sorts, $offset, $length);
     $tpl->setVariable( 'comments', $comments );
     $tpl->setVariable( 'total_count', $count );
     $tpl->setVariable( 'total_page', ceil( $count / $defaultNumPerPage) );
     $tpl->setVariable( 'current_page', $Page );
     $Result['content'] = $tpl->fetch( 'design:comment/view_standard.tpl' );
     return $Result;
}

?>