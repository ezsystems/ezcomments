<?php
//
// Definition of ezcomCommentCommonManager class
//
// Created on: <20-Jan-2009 12:00:00 xc>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Comments extension for eZ Publish
// SOFTWARE RELEASE: 1.0-0
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
 * 
 *Business logic of comment
 *
 */

class ezcomCommentCommonManager extends ezcomCommentManager
{ 
    
    /**
     * add subscription after adding comment
     * 1) If 'notification' is true
     *     add the user as a subscriber if subscriber with same email doesn't exist
     *     otherwise get the subscriber
     * 2) If 'notification' is true
     *    if the subscription with user's email and contentid doesn't exist, add a new subscription,
     * 3) If there is subscription, add the comment into notifiction queue
     *  
     * @see extension/ezcomments/classes/ezcomCommentManager#afterAddingComment($comment)
     */
    public function afterAddingComment( $comment )
    {
        $contentID = $comment->attribute( 'contentobject_id' ) . '_' . $comment->attribute( 'language_id' );
        $subscriptionType = 'ezcomcomment';
        //add subscription
        if( $comment->attribute( 'notification' ) )
        {
            $user = eZUser::instance();
            $subscription = ezcomSubscriptionManager::instance();
            $subscription->addSubscription( $comment->attribute('email'), $user,
                                          $contentID, $subscriptionType, $comment->attribute( 'created' ) );
        }
        // insert data into notification queue
        // if there is no subscription,not adding to notification queue
        if( ezcomSubscription::exists( $contentID, $subscriptionType ) )
        {
            $notification = ezcomNotification::create();
            $notification->setAttribute( 'contentobject_id', $comment->attribute('contentobject_id') );
            $notification->setAttribute( 'language_id', $comment->attribute( 'language_id' ) );
            $notification->setAttribute( 'comment_id', $comment->attribute( 'id' ) );
            $notification->store();
            eZDebug::writeNotice( 'Notification has been added into queue', 'Add comment', 'ezcomComment' );
        }
    }
   
    /**
     * clean up subscription after deleting comment
     * @see extension/ezcomments/classes/ezcomCommentManager#afterDeletingComment($comment)
     */
    public function afterDeletingComment( $comment )
    {
        // clean up subscription
        $ini = eZINI::instance( 'ezomments.ini' );
        $deletingSubscription = $ini->variable( 'GlobalSettings', 'DeleteSubscriptionAfterDeleteComment' );
        
        if( $deleteingSubscription === 'true' )
        {
            eZDebug::writeNotice( 'The comment to be deleted has subscription', 'Delete comment' );
            $contentID = $comment->attribute( 'contentobject_id' ) . '_'. $comment->attribute( 'language_id' );
            $commentObject = ezcomComment::fetchByEmail( $comment->attribute( 'email' ) );
            //if the comment on the object is empty, delete the susbscription 
            if( is_null( $commentObject ) )
            {
                $subscriptionManager = ezcomSubscriptionManager::instance();
                $subscriptionManager->deleteSubscription( $comment->attribute( 'email' ), $comment->attribute( 'contentobject_id' ),
                                                          $comment->attribute( 'language_id' ) );
            }
        }
        return true;
    }
    
    /**
     * clean up subscription after updating comment 
     * @see extension/ezcomments/classes/ezcomCommentManager#afterUpdatingComment($comment, $notified)
     */
    public function afterUpdatingComment( $comment, $notified )
    {
        $user = eZUser::fetch( $comment->attribute( 'user_id' ) );
        
        // if notified is true, add subscription, else cleanup the subscription on the user and content
        $contentID = $comment->attribute( 'contentobject_id' ) . '_' . $comment->attribute( 'language_id' );
        $subscriptionType = 'ezcomcomment';
        if( !is_null( $notified ) )
        {
            $subscriptionManager = ezcomSubscriptionManager::instance();
            if( $notified === true )
            {
                $subscriptionManager->addSubscription( $comment->attribute( 'email' ), $user, $contentID,
                             $subscriptionType, $time );
            }
            else
            {
                $subscriptionManager->deleteSubscription( $comment->attribute( 'email' ), $comment->attribute( 'contentobject_id' ),
                                                          $comment->attribute( 'language_id' ) );
            }
        }
        //3. update queue. If there is subscription, add one record into queue table
        // if there is subcription on this content, add one item into queue
        if( ezcomSubscription::exists( $contentID, $subscriptionType ) )
        {
            $notification = ezcomNotification::create();
            $notification->setAttribute( 'contentobject_id', $comment->attribute( 'contentobject_id' ) );
            $notification->setAttribute( 'language_id', $comment->attribute( 'language_id' ) );
            $notification->setAttribute( 'comment_id', $comment->attribute( 'id' ) );
            $notification->store();
            eZDebug::writeNotice( 'There is subscription, added a update notification into queue.', 'ezcomments' );
        }
        else
        {
            // todo: if there is no subscription on this content, consider to clean up the queue
        }
        return true;
    }
    
    
    /**
     * common implementation of validteInput method
     * valudate that if the name and text(content) is empty
     * @see extension/ezcomments/classes/ezcomCommentManager#validateInput($comment)
     */
    public function validateInput( $comment )
    {
        if( is_null( $comment ) )
        {
            return ezi18n( 'comment/view/validateInput', 'Parameter is empty!' );
        }
        if( $comment->attribute( 'name' ) == '' )
        {
            return ezi18n( 'comment/view/validateInput', 'Name is empty!' );
        }
        if( $comment->attribute( 'email' ) == '' )
        {
            return ezi18n( 'comment/view/validateInput', 'Email is empty!' );
        }
        else
        {   
            if( eZMail::validate( $comment->attribute( 'email' ) ) == false )
            {
                return ezi18n( 'comment/view/validateInput', 'Not a valid email address!' );
            }
        }
        if( $comment->attribute( 'text' ) == '' )
        {
            return ezi18n( 'comment/view/validateInput', 'Content is empty!' );
        }
        if ( $comment->attribute( 'language_id' ) == '' || !is_numeric( $comment->attribute( 'language_id' ) ) )
        {
            return ezi18n( 'comment/view/validateInput', 'Language is empty or not int!' );
        }
        if ( $comment->attribute( 'contentobject_id' ) == '' || !is_numeric( $comment->attribute( 'contentobject_id' ) ) )
        {
            return ezi18n( 'comment/view/validateInput', 'Object ID can not be empty or string!' );
        }
        return true;
    }
}
?>