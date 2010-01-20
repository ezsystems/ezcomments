<?php
//
// Definition of ezcomCommentManager class
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
abstract class ezcomCommentManager
{
    /**
     * template
     * @var eZTemplate
     */
    public $tpl = null;
    
    protected static $instance;
    
    /**
     * @param $comment
     * @param $user
     * @return true continue add
     * false stop add
     */
    public abstract function beforeAddingComment( $comment, $user );
    
    /**
     * @param $comment: a comment object
     * @return true if the validation succeeds
     *         error message if the validation fails.
     */
    public abstract function validateInput( $comment );    
    
    
    /**
     * Add comment into ezcomment table and related data into ezcomment_subscriber,
     *                                                        ezcomment_subscription,
     *                                                        ezcomment_notification
     * The adding doesn't validate the data in http
     * 1) Add a comment into ezcomment table
     * 2) If 'notification' is true
     *      add the user as a subscriber if subscriber with same email doesn't exist
     *      otherwise get the subscriber
     * 3) If 'notification' is true
     *    if the subscription with user's email and contentid doesn't exist, add a new subscription,
     * 4) If there is subscription, add the comment into notifiction queue
     *  
     * @param $comment: ezcomComment object which has not been stored
     *        title, name, url, email, created, modified, text, notification
     * @param $user: user object
     * @param $contentObjectID: id of the content object
     * @param $languageID: language id of the content object
     * @param $time: comment time
     * @return  true : if adding succeeds
     *          false otherwise
     *          string: error message
     *      
     */
    public function addComment( $comment, $user, $time = null )
    {
        $validationResult = $this->validateInput( $comment );
        if( $validationResult !== true )
        {
            return $validationResult;
        }
        $currentTime = null;
        if( is_null( $time ) )
        {
            $currentTime = time();
        }
        else
        {
            $currentTime = $time;
        }
        $beforeAddingResult = $this->beforeAddingComment( $comment, $user );
        if(  $beforeAddingResult !== true )
        {
            return $beforeAddingResult;
        }
        $comment->store();
        eZDebug::writeNotice( 'Comment has been added into ezcomment table', 'Add comment', 'ezcomComment' );
        
        $contentID = $comment->attribute( 'contentobject_id' ) . '_' . $comment->attribute( 'language_id' );
        $subscriptionType = 'ezcomcomment';
        //add subscription
        if( $comment->attribute( 'notification' ) )
        {
            $subscription = ezcomSubscriptionManager::instance();
            $subscription->addSubscription( $comment->attribute('email'), $user,
                                          $contentID, $subscriptionType, $currentTime );
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
        return true;
    }
    
    /**
     * create an instance of ezcomCommentManager
     * @return ezcomCommentManager
     */
    public static function instance()
    {
        if( !isset( self::$instance ) )
        {
            $ini = eZINI::instance( 'ezcomments.ini' );
            $className = $ini->variable( 'ManagerClasses', 'CommentManagerClass' );
            self::$instance = new $className();
        }
        return self::$instance;
    }
    
}
?>