<?php
/**
 * File containing the ezcomSubscriptionManager class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

/**
 * Business logic for subscription
 */
class ezcomSubscriptionManager
{
    const ERROR_SUBSCRIBER_DISABLED = 0;
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
        if ( is_null( $subscription ) )
        {
            $this->tpl->setVariable( 'error_message', ezi18n( 'ezcomments/comment/activate',
                                      'The is no subscription with the hash string!' ) );
        }
        else
        {
            if ( $subscription->attribute( 'enabled' ) )
            {
                ezDebugSetting::writeNotice( 'extension-ezcomments', 'Subscription activated', __METHOD__ );
            }

            $subscriber = ezcomSubscriber::fetch( $subscription->attribute( 'subscriber_id' ) );

            if ( $subscriber->attribute( 'enabled' ) )
            {
                $subscription->enable();
                $this->tpl->setVariable( 'subscriber', $subscriber );
            }
            else
            {
                $this->tpl->setVariable( 'error_message', ezi18n( 'ezcomments/comment/activate',
                                      'The subscriber is disabled!' ) );
            }
        }
    }

    /**
    * Add an subscription. If the subscriber is disabled, throw an exception
    * If there is no subscriber, add one.
    * If there is no subscription for the content, add one
    * @param $email: user's email
    * @return void
    */
    public function addSubscription( $email, $user, $contentID, $languageID, $subscriptionType, $currentTime, $activate = true )
    {
        //1. insert into subscriber
        $ezcommentsINI = eZINI::instance( 'ezcomments.ini' );
        $subscriber = ezcomSubscriber::fetchByEmail( $email );
        //if there is no data in subscriber for same email, save it
        if ( is_null( $subscriber ) )
        {
            $subscriber = ezcomSubscriber::create();
            $subscriber->setAttribute( 'user_id', $user->attribute( 'contentobject_id' ) );
            $subscriber->setAttribute( 'email', $email );
            if ( $user->isAnonymous() )
            {
                $util = ezcomUtility::instance();
                $hashString = $util->generateSusbcriberHashString( $subscriber );
                $subscriber->setAttribute( 'hash_string', $hashString );
            }
            $subscriber->store();
            eZDebugSetting::writeNotice( 'extension-ezcomments', 'Subscriber does not exist, added one', __METHOD__ );
            $subscriber = ezcomSubscriber::fetchByEmail( $email );
        }
        else
        {
            if ( $subscriber->attribute( 'enabled' ) == false )
            {
                throw new Exception('Subscription can not be added because the subscriber is disabled.', self::ERROR_SUBSCRIBER_DISABLED );
            }
        }
        //3 insert into subscription table
        // if there is no data in ezcomment_subscription with given contentobject_id and subscriber_id
        $hasSubscription = ezcomSubscription::exists( $contentID,
                                                      $languageID,
                                                      $subscriptionType,
                                                      $email );
        if ( $hasSubscription === false )
        {
            $subscription = ezcomSubscription::create();
            $subscription->setAttribute( 'user_id', $user->attribute( 'contentobject_id' ) );
            $subscription->setAttribute( 'subscriber_id', $subscriber->attribute( 'id' ) );
            $subscription->setAttribute( 'subscription_type', $subscriptionType );
            $subscription->setAttribute( 'content_id', $contentID );
            $subscription->setAttribute( 'language_id', $languageID );
            $subscription->setAttribute( 'subscription_time', $currentTime );
            $defaultActivated = $ezcommentsINI->variable( 'CommentSettings', 'SubscriptionActivated' );

            if ( $user->isAnonymous() && $defaultActivated !== 'true' && $activate === true )
            {
                $subscription->setAttribute( 'enabled', 0 );
                $utility = ezcomUtility::instance();
                $subscription->setAttribute( 'hash_string', $utility->generateSubscriptionHashString( $subscription ) );
                $subscription->store();

                $result = ezcomSubscriptionManager::sendActivationEmail( eZContentObject::fetch( $contentID ),
                                                                         $subscriber,
                                                                         $subscription );
                if ( !$result )
                {
                    eZDebug::writeError( "Error sending mail to '$email'", __METHOD__ );
                }
            }
            else
            {
                $subscription->setAttribute( 'enabled', 1 );
                $subscription->store();
            }
            eZDebugSetting::writeNotice( 'extension-ezcomments', 'No existing subscription for this content and user, added one', __METHOD__ );
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
        if ( is_array( $result ) && count( $result ) > 0 )
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
        $subscriber = ezcomSubscriber::fetchByEmail( $email );
        $cond = array();
        $cond['subscriber_id'] = $subscriber->attribute( 'id' );
        $cond['content_id'] = $contentObjectID;
        $cond['language_id'] = $languageID;
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
        if ( is_null( $className ) )
        {
            $ini = eZINI::instance( 'ezcomments.ini' );
            $className = $ini->variable( 'ManagerClasses', 'SubscriberManagerClass' );
        }

        if ( !is_null( self::$instance ) )
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