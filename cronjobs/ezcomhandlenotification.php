<?php
/**
 * File containing php script for sending notification
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

/**
 * Send comment notification to user who subscribed the content
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
    $cli->output( "Start sending comment notification..."  );
// 1. check ezcomment_notification table
$db = eZDB::instance();

$now = new eZDateTime();
$currentTime = $now->toTime()->timeStamp();
$ezcommentsINI = eZINI::instance( 'ezcomments.ini' );

$sendingNumber = $ezcommentsINI->variable( 'NotificationSettings', 'NotificationNumberPerExecuation' );
$mailContentType = $ezcommentsINI->variable( 'NotificationSettings', 'MailContentType');
$mailFrom = $ezcommentsINI->variable( 'NotificationSettings', 'MailFrom' );

$contentObjectIDList = $db->arrayQuery( 'SELECT DISTINCT contentobject_id, language_id' .
                                        ' FROM ezcomment_notification ' .
                                        'WHERE status=1' .
                                        ' AND send_time < ' . $currentTime ,
                                        array( 'offset' => 0,
                                               'limit' => $sendingNumber )
                                      );
$notificationCount = 0;
$mailCount = 0;
foreach( $contentObjectIDList as $contentObjectArray )
{
    $contentObjectID = $contentObjectArray['contentobject_id'];
    $contentLanguage = $contentObjectArray['language_id'];
    $notifications = $db->arrayQuery( 'SELECT * FROM ezcomment_notification '.
                                      'WHERE contentobject_id = ' . $contentObjectID );
    // fetch content object
    $contentObject = eZContentObject::fetch( $contentObjectID, true );
    if ( is_null( $contentObject ) )
    {
        $cli->output( "Content doesn't exist, delete the notification. Content ID:" . $contentObjectID );
        $db->query( 'DELETE FROM ezcomment_notification WHERE contentobject_id=' . $contentObjectID );
        continue;
    }
    
    // fetch subscribers
    
    $subscriptionList = $db->arrayQuery( "SELECT subscriber_id FROM ezcomment_subscription".
                                      " WHERE enabled = 1" . 
                                      " AND content_id = $contentObjectID" .
                                      " AND language_id = $contentLanguage"   );
    $subscriberList = array();
    foreach( $subscriptionList as $subscription )
    {
        $subscriberList[] = ezcomSubscriber::fetch( $subscription['subscriber_id'] );
    }

    //fetch comment list
    $commentList = array();
    foreach ( $notifications as $notification )
    {
         // fetch comment object
         $comment = ezcomComment::fetch( $notification['comment_id'] );
         if ( is_null( $comment ) )
         {
             $cli->output( "Comment doesn't exist, delete the notification. Comment ID:" . $notification['comment_id'] );
             $db->query( 'DELETE FROM ezcomment_notification WHERE id=' . $notification['id'] );
             continue;
         }
         $commentList[] = $comment;
    }
    try
    {
        $notificationManager = ezcomNotificationManager::instance();
        $commentsInOne = $ezcommentsINI->variable( 'NotificationSettings', 'CommentsInOne' );
        foreach( $subscriberList as $subscriber )
        {
            if ( $commentsInOne !== 'true' )
            {
                foreach( $commentList as $comment )
                {
                    if ( $comment->attribute('email') != $subscriber->attribute( 'email' ) )
                    {
                        $notificationManager->sendNotificationInMany( $subscriber, $contentObject, $comment );
                    }
                }
            }
            else
            {
                // only send mail to the subscriber that didn't comment.
                $isAuthor = false;
                foreach( $commentList as $comment )
                {
                    if ( $comment->attribute('email') == $subscriber->attribute( 'email' ) )
                    {
                        $isAuthor = true;
                    }
                }
                if ( !$isAuthor )
                {
                    $notificationManager->sendNotificationInOne( $subscriber, $contentObject );
//                    if ( eZDebug::isDebugEnabled() )
//                    {
//                        eZLog::write( 'Sent email to ' . $subscriber->attribute( 'email' ),
//                                      'ezcomments.log' );
//                    }
                }
            }
        }
    }
    catch( Exception $ex )
    {
        if ( !$isQuiet )
            $cli->output( 'Sending notification error! Exception' . $ex->getMessage() . '\n' );
    }
    $db->query( 'DELETE FROM ezcomment_notification' . 
                ' WHERE status = 1 AND' .
                ' contentobject_id = ' . $contentObjectID .
                ' AND language_id =' . $contentLanguage );
}
    
?>