<?php
/**
 * File containing ezcomCommentCommonManager class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

/**
 *
 *Business logic of comment
 *
 */

class ezcomCommentCommonManager extends ezcomCommentManager
{
    
    /**
     * set status for adding comment
     * @see extension/ezcomments/classes/ezcomCommentManager#beforeAddingComment($comment, $user, $notification)
     */
    public function beforeAddingComment( $comment, $user, $notification )
    {
        $comment->setAttribute( 'status', 1 );
        return true;
    }

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
    public function afterAddingComment( $comment, $notification )
    {
        $contentID = $comment->attribute( 'contentobject_id' );
        $languageID = $comment->attribute( 'language_id' );
        $subscriptionType = 'ezcomcomment';
        //add subscription

        $subscription = ezcomSubscriptionManager::instance();
        $user = eZUser::instance();
        if ( $notification === true )
        {
            $subscription->addSubscription( $comment->attribute('email'),
                                            $user,
                                            $contentID,
                                            $languageID,
                                            $subscriptionType,
                                            $comment->attribute( 'created' ) );
        }
        else if ( $notification === false )
        {
            if ( !$user->isAnonymous() )
            {
                $subscription->deleteSubscription( $comment->attribute( 'email' ),
                                                   $comment->attribute( 'contentobject_id' ),
                                                   $comment->attribute( 'language_id' ) );
            }
        }



        // insert data into notification queue
        // if there is no subscription,not adding to notification queue
        if ( ezcomSubscription::exists( $contentID, $languageID, $subscriptionType, null, 1 ) )
        {
            $notification = ezcomNotification::create();
            $notification->setAttribute( 'contentobject_id', $comment->attribute('contentobject_id') );
            $notification->setAttribute( 'language_id', $comment->attribute( 'language_id' ) );
            $notification->setAttribute( 'comment_id', $comment->attribute( 'id' ) );
            $notification->store();
            eZDebugSetting::writeNotice( 'extension-ezcomments', 'Notification added to queue', __METHOD__ );
        }
    }

    /**
     * Placeholder for afterDeletetingComment hook
     * @see extension/ezcomments/classes/ezcomCommentManager#afterDeletingComment($comment)
     */
    public function afterDeletingComment( $comment )
    {
        return true;
    }

    /**
     * clean up subscription after updating comment
     * @see extension/ezcomments/classes/ezcomCommentManager#afterUpdatingComment($comment, $notified)
     */
    public function afterUpdatingComment( $comment, $notified, $time )
    {
        $user = eZUser::fetch( $comment->attribute( 'user_id' ) );

        // if notified is true, add subscription, else cleanup the subscription on the user and content
        $contentID = $comment->attribute( 'contentobject_id' );
        $languageID = $comment->attribute( 'language_id' );
        $subscriptionType = 'ezcomcomment';
        if ( !is_null( $notified ) )
        {
            $subscriptionManager = ezcomSubscriptionManager::instance();
            if ( $notified === true )
            {
                //add subscription but not send activation
                try
                {
                $subscriptionManager->addSubscription( $comment->attribute( 'email' ),
                                                       $user,
                                                       $contentID,
                                                       $languageID,
                                                       $subscriptionType,
                                                       $time,
                                                       false );
                }
                catch ( Exception $e )
                {
                    eZDebug::writeError( $e->getMessage(), __METHOD__ );
                    switch ( $e->getCode() )
                    {
                        case ezcomSubscriptionManager::ERROR_SUBSCRIBER_DISABLED:
                            return 'The subscriber is disabled.';
                        default:
                            return false;
                    }
                }
            }
            else
            {
                $subscriptionManager->deleteSubscription( $comment->attribute( 'email' ),
                                                          $comment->attribute( 'contentobject_id' ),
                                                          $comment->attribute( 'language_id' ) );
            }
        }
        //3. update queue. If there is subscription, add one record into queue table
        // if there is subcription on this content, add one item into queue
        if ( ezcomSubscription::exists( $contentID, $languageID,  $subscriptionType ) )
        {
            $notification = ezcomNotification::create();
            $notification->setAttribute( 'contentobject_id', $comment->attribute( 'contentobject_id' ) );
            $notification->setAttribute( 'language_id', $comment->attribute( 'language_id' ) );
            $notification->setAttribute( 'comment_id', $comment->attribute( 'id' ) );
            $notification->store();
            eZDebugSetting::writeNotice( 'extension-ezcomments', 'There are subscriptions, added an update notification to the queue.', __METHOD__ );
        }
        else
        {
            // todo: if there is no subscription on this content, consider to clean up the queue
        }
        return true;
    }
}
?>