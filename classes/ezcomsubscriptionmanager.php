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
    var $params = null;
    var $tpl = null;
    var $module = null;
    
    
    function __construct( $tpl, $module, $params )
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
            //for test
            self::sendActivationEmail( eZContentObject::fetch( 158 ), $subscriber, $subscription );
            //
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
     * method for creating object
     * @return ezcomSubscriptionManager
     */
    public static function instance( $tpl, $module, $params )
    {
        return new ezcomSubscriptionManager( $tpl, $module, $params );
    }
    
}
?>