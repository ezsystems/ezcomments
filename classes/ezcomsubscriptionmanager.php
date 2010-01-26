<?php
//
// Created on: <17-Jan-2010 19:39:00 xc>
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

/**
 * Business logic for subscription
 */
class ezcomSubscriptionManager
{
    public $params = null;
    public $tpl = null;
    public $module = null;
    protected static $instance = null;
    
    function __construct( $tpl = null, $module = null, $params = null )
    {
        $this->tpl = $tpl;
        $this->module = $module;
        $this->params = $params;
    }
    
    /**
     * Activate subscription
     * If there is error,set 'error_message' to the template,
     * If activation succeeds, set 'subscriber' to the template 
     * @param string: $hashString
     * @return void
     */
    public function activateSubscription( $hashString )
    {
        // 1. fetch the subscription object
        $subscription = ezcomSubscription::fetchByHashString( $hashString );
        if( is_null( $subscription ) )
        {
            $this->tpl->setVariable( 'error_message', ezi18n( 'extension/ezcomments/activate', 
                                      'The is no subscription with the hash string!' ) );
        }
        else
        {
            if( $subscription->attribute( 'enabled' ) )
            {
                ezDebug::writeNotice( 'The subscription has been activated!' );
            }
            
            $subscriber = ezcomSubscriber::fetch( $subscription->attribute( 'subscriber_id' ) );
            
            if( $subscriber->attribute( 'enabled' ) )
            {
                $subscription->enable();
                $this->tpl->setVariable( 'subscriber', $subscriber ); 
            }
            else
            {
                $this->tpl->setVariable( 'error_message', ezi18n( 'extension/ezcomments/activate', 
                                      'The subscriber is disabled!' ) );
            }
        }
    }
    
    /**
    * Add an subscription. 
    * If there is no subscriber, add one.
    * If there is no subscription for the content, add one
    * @param $email: user's email
    * @return void
    */
    public function addSubscription( $email, $user, $contentID, $subscriptionType, $currentTime )
    {
        //1. insert into subscriber
        $ezcommentsINI = eZINI::instance( 'ezcomments.ini' );
        $subscriber = ezcomSubscriber::fetchByEmail( $email );
        //if there is no data in subscriber for same email, save it
        if( is_null( $subscriber ) )
        {
            $subscriber = ezcomSubscriber::create();
            $subscriber->setAttribute( 'user_id', $user->attribute( 'contentobject_id' ) );
            $subscriber->setAttribute( 'email', $email );
            if( $user->isAnonymous() )
            {
                $subscriber->setAttribute( 'hash_string', hash('md5',uniqid()) );
            }
            $subscriber->store();
            eZDebug::writeNotice( 'Subscriber doesn\'t exist, added one', 'Add comment', 'ezcomComment' );
            $subscriber = ezcomSubscriber::fetchByEmail( $email );
        }
        else
        {
            if( $subscriber->attribute( 'enabled' ) === 0 )
            {
                $result['warnings'][] = ezi18n( 'comment/view/addcomment', 'The email is disabled,
                             you won\'t receive notification
                              until it is activated!' );
            }
        }
        //3 insert into subscription table
        // if there is no data in ezcomment_subscription with given contentobject_id and subscriber_id
        $hasSubscription = ezcomSubscription::exists( $contentID,
                                        $subscriptionType,
                                        $email );
        if( $hasSubscription === false )
        {
            $subscription = ezcomSubscription::create();
            $subscription->setAttribute( 'user_id', $user->attribute( 'contentobject_id' ) );
            $subscription->setAttribute( 'subscriber_id', $subscriber->attribute( 'id' ) );
            $subscription->setAttribute( 'subscription_type', $subscriptionType );
            $subscription->setAttribute( 'content_id', $contentID );
            $subscription->setAttribute( 'subscription_time', $currentTime );
            $defaultActivated = $ezcommentsINI->variable( 'CommentSettings', 'SubscriptionActivated' );
        
            if( $user->isAnonymous() && $defaultActivated !== 'true' )
            {
                $subscription->setAttribute( 'enabled', 0 );
                $utility = ezcomUtility::instance();
                $subscription->setAttribute( 'hash_string', $utility->generateSubscriptionHashString( $subscription ) );
                $subscription->store();
                
                $result = ezcomSubscriptionManager::sendActivationEmail( eZContentObject::fetch( $contentID),
                                                                         $subscriber, 
                                                                         $subscription );
                if( !$result )
                {
                    eZDebug::writeError( 'The mail sending failed', 'Add comment', 'ezcomComment' );
                }
            }
            else
            {
                $subscription->setAttribute( 'enabled', 1 );
                $subscription->store();
            }
            eZDebug::writeNotice( 'There is no subscription for the content and user, added one', 'Add comment', 'ecomComment' );
        }
    }
    
    /**
     * send activation email to the user
     * @param ezcomContentObject $contentObject
     * @param ezcomSubscriber $subscriber
     * @param ezcomSubscription $subscription
     * @return true if mail sending succeeds
     * false if mail sending fails
     */
    public static function sendActivationEmail( $contentObject, $subscriber, $subscription )
    {
        $transport = eZNotificationTransport::instance( 'ezmail' );
        
        $email = $subscriber->attribute( 'email' );
        require_once( 'kernel/common/template.php' );
        $tpl = templateInit();
        $tpl->setVariable( 'contentobject', $contentObject );
        $tpl->setVariable( 'subscriber', $subscriber );
        $tpl->setVariable( 'subscription', $subscription );
        $mailSubject = $tpl->fetch( 'design:comment/notification_activation_subject.tpl' );
        $mailBody = $tpl->fetch( 'design:comment/notification_activation_body.tpl' );
        $parameters = array();
        $ezcommentsINI = eZINI::instance( 'ezcomments.ini' );
        $mailContentType = $ezcommentsINI->variable( 'NotificationSettings', 'ActivationMailContentType' );
        $parameters['content_type'] = $mailContentType;
        
        $result = $transport->send( array( $email ), $mailSubject, $mailBody, null, $parameters );
        return $result;
    }
    
    /**
     * clean up the subscription if the subscription has not been activate for very long
     * @return array id of subscription cleaned up
     * null if nothing cleaned up
     */
    public static function cleanupExpiredSubscription( $time )
    {
        //1. find the subscription which is disabled and whose time is too long
        $startTime = time() - $time;
        $db = eZDB::instance();
        $selectSql = "SELECT id FROM ezcomment_subscription WHERE subscription_time < $startTime AND enabled = 0";
        $result = $db->arrayQuery( $selectSql );
        if( is_array( $result ) && count( $result ) > 0 )
        {
            //2. clean up the subscription
            $deleteSql = "DELETE FROM ezcomment_subscription WHERE subscription_time < $startTime AND enabled = 0";
            $db->query( $deleteSql );
            return $result;
        }
        else
        {
            return null;
        }
    }
    
    /**
     * delete the subscription given the subscriber's email
     * @param $email
     * @param $contentObjectID
     * @param $languageID
     * @return unknown_type
     */
    public function deleteSubscription( $email, $contentObjectID, $languageID )
    {
        $contentID = $contentObjectID . '_' . $languageID;
        $subscriber = ezcomSubscriber::fetchByEmail( $email );
        $cond = array();
        $cond['subscriber_id'] = $subscriber->attribute( 'id' );
        $cond['content_id'] = $contentID;
        $cond['subscription_type'] = 'ezcomcomment';
        $subscription = ezcomSubscription::fetchByCond( $cond );
        $subscription->remove();
    }
    
    /**
     * method for creating object
     * @return ezcomSubscriptionManager
     */
    public static function instance( $tpl = null, $module = null, $params = null, $className = null )
    {
        $object = null;
        if( is_null( $className ) )
        {
            $ini = eZINI::instance( 'ezcomments.ini' );
            $className = $ini->variable( 'ManagerClasses', 'SubscriberManagerClass' );
        }
        
        if( !is_null( self::$instance ) )
        {
            $object = self::$instance;
        }
        else
        {
            $object = new $className();
            $object->tpl = $tpl;
            $object->module = $module;
            $object->params = $params;
        }
        return $object;
    }
    
}
?>