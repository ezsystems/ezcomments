<?php
//
// Send comment notification to user who subscribed the content
//
// Created on: <17-Dec-2009 13:12:00 xc>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.3.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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
 * Send comment notification to user who subscribed the content
 * // 1. check ezcomment_notification table
 * // 2. fetch content from content table and build mail content
 * // 3. send mail to notified user 
 * 
 * 
 */
$cli = eZCLI::instance();
$scriopt = eZScript::instance( array( 'description' => 'eZ Publish extension ezcomments sending notification script',
                                      'use-session' => false,
                                      'use-module' => false,
                                      'use-extension' => false ) );
$script->startup();
$script->initialize();
if ( !$isQuiet )
    $cli->output( "Send comment notification..."  );
// 1. check ezcomment_notification table
$db = eZDB::instance();

// 2. fetch content from content table and build mail content
$now = new eZDateTime();
$currentTime = $now->toTime()->timeStamp();
//to be done: setting from setting.ini
$sendingNumber = 10000;
$commentLength = 10000;

$notifications = $db->arrayQuery( 'SELECT * FROM ezcomment_notification '.
                                   'WHERE send_time < ' . $currentTime .
                                   ' LIMITED 0,'. $sendingNumber .
                                   ' ORDER BY id' );
foreach ( $notifications as $notification )
{
     $contentObjectID = $notification['contentobject_id'];
     $contentLanguage = $notification['language_id'];
     $commentID = $notification['comment_id'];
     //fetch the content from content object, this can be extended to be other content
     $contentObject = eZContentObject::fetch( true );
     $contentName = $contentObject->name( false, $contentLanguage );
     
     $comment = ezcomComment::fetch( $commentID );
     $commentAuthor = $comment->attribute( 'name' );
     $commentTitle = $comment->attribute('title');
     $commentText = $comment->attribute('text');
     if ( isset( $commentLength ) && ( $commentLength > -1 ) )
     {
         if( strlen( $commentLength ) > $commentLength )
         {
            $commentText = substr( $commentText, $commentLength ).'...';
         }
     }
     
     //fetch the mail address list
     
     $db->arrayQuery( 'SELECT  ' );
     
     //fetch mail template
     
     //send mail
     
     //handle error
}

return;
// 3. send mail to notified user 
//$emailAddressList = array();
//$emailAddressList['xc'] = 'xc@ez.no';
//$mailSubject = 'Mail tesing';
//$mailBody = 'This is a mail sent by machine automatically. Testing mail sending function :) - chen<hr />';
//$transport = eZNotificationTransport::instance( 'ezmail' );
//$parameters = array();
//$replyTo = 'mail@xiongjie.net';
//$from = 'xc@ez.no';
//$to = 'chen';
//$contentType = 'text/plain';
//$parameters['reply_to'] = $replyTo;
//$parameters['from'] = $from;
//$parameters['to'] = $to;
//$parameters['content_type'] = $contentType;
//
//$result = $transport->send( $emailAddressList, $mailSubject, $mailBody, null, $parameters );
//if ( $result )
//{
//    $cli->output( 'Mail sent!' );
//}
//else
//{
//    $cli->output( 'Sending mail failed!' );
//}


?>