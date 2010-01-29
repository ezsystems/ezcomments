<?php
//
// Definition of ezcomSubscription class
//
// Created on: <10-Dec-2009 00:00:00 xc>
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
 * ezcomSubscription persistent object class definition
 */
class ezcomSubscription extends eZPersistentObject
{
    /**
     * Construct, use {@link ezcomSubscription::create()} to create new objects.
     *
     * @param array $row
     */
    public function __construct( $row )
    {
       parent::__construct( $row );
    }

    /**
     * Fields definition
     *
     * @return array
     */
    public static function definition()
    {
        static $def = array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                               'subscriber_id' => array( 'name' => 'SubscriberID',
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true ),
                                                'user_id' => array( 'name' => 'UserID',
                                                                             'datatype' => 'integer',
                                                                             'default' => 0,
                                                                             'required' => true ),
                                                'subscription_type' => array( 'name' => 'SubscriptionType',
                                                                        'datatype' => 'string',
                                                                        'default' => '',
                                                                        'required' => true ),
                                                'content_id' => array( 'name' => 'ContentID',
                                                                   'datatype' => 'string',
                                                                   'default' => '',
                                                                   'required' => true ),
                                                'subscription_time' => array( 'name' => 'SubscriptionTime',
                                                                   'datatype' => 'integer',
                                                                   'default' => 1,
                                                                   'required' => true ),
                                                'enabled' => array( 'name' => 'Enabled',
                                                                   'datatype' => 'integer',
                                                                   'default' => 1,
                                                                   'required' => true ),
                                                'hash_string' => array( 'name' => 'HashString',
                                                                   'datatype' => 'string',
                                                                   'default' => '',
                                                                   'required' => true ) ),
                             'keys' => array( 'id' ),
                             'function_attributes' => array(),
                             'increment_key' => 'id',
                             'class_name' => 'ezcomSubscription',
                             'name' => 'ezcomment_subscription' );
        return $def;
    }

    /**
     * Create new ezcomSubscription object
     *
     * @static
     * @param array $row
     * @return ezcomNotification
     */
    public static function create( $row = array() )
    {
        $object = new self( $row );
        return $object;
    }

    /**
     * Fetch ezcomSubscription by given id
     *
     * @param int $id
     * @return null|ezcomSubscription
     */
    static function fetch( $id )
    {
        $cond = array( 'id' => $id );
        $return = eZPersistentObject::fetchObject( self::definition(), null, $cond );
        return $return;
    }

    static function fetchByCond( $cond )
    {
        return eZPersistentObject::fetchObject( self::definition(), null, $cond );
    }

    /**
     * fetch the subscription object by hash_string
     * @return null / ezcomSubscription object
     */
    static function fetchByHashString( $hashString )
    {
        $cond = array( 'hash_string'=>$hashString );
        $return = self::fetchByCond( $cond );
        return $return;
    }

    /**
     * get the count of subscription in a subscriber ID
     * @param $subscriberID
     * @param $status
     * @return unknown_type
     */
    static function countWithSubscriberID( $subscriberID, $status = null )
    {
        $cond = array();
        if( $languageID !== false )
        {
            $cond['subscriber_id'] = $subscriberID;
        }
        if( !is_null( $status ) )
        {
            $cond['status'] = $status;
        }
        $count = eZPersistentObject::count( self::definition(), $cond );
        return $count;
    }

    /**
     * enable the subscription
     * @return true: enabled
     *         false: alreaday enabled
     */
    public function enable()
    {
        if( $this->attribute( 'enabled' ) )
        {
            return false;
        }
        else
        {
            $this->setAttribute( 'enabled', 1 );
            $this->store();
            return true;
        }
    }

    /**
     * clean up subscription based on an email address and content,
     *  make the subscription consistent.
     *  example:
     *      cleanup subscription on content: cleanupSubscription( $email, $contentID )
     *      cleanup subscription on comment: cleanupSubscription( $email, null, $commentID )
     * @param string $email
     * @return true - there is subscription deleted
     *         false - no subscription deleted
     *         null - error
     */
    static function cleanupSubscription( $email, $contentID = null, $commentID = null )
    {
        //1. get comment ID
        $contentObjectID = "";
        $contentLanguage = "";
        $contentString = "";
        $commentString = "";

        if( !is_null( $contentID ) )
        {
            $contentObjectID = substr( $contentID, 0, strpos( $contentID, '_' ) );
            $contentLanguage = substr( $contentID, strpos( $contentID, '_')+1 );
        }
        if( !is_null( $commentID ) )
        {
            $comment = ezcomComment::fetch( $commentID );
            $contentObjectID = $comment->attribute( 'contentobject_id' );
            $contentLanguage = $comment->attribute( 'language_id' );
        }
        $contentID = $contentObjectID . '_' . $contentLanguage;
        $queryComment = "SELECT count(*) AS count FROM ezcomment
                        WHERE email = '$email'" .
                        "AND notification=1 ".
                        "AND contentobject_id= $contentObjectID " .
                        "AND language_id= $contentLanguage";
        $db = eZDB::instance();
        $commentCount = $db->arrayQuery( $queryComment );
        $hasComment = false;
        if( $commentCount[0]['count'] > 0 )
        {
            $hasComment = true;
        }
        if( $hasComment === false )
        {
            //2. get subscriber
            $subscriber = ezcomSubscriber::fetchByEmail( $email );
            if( is_null( $subscriber ) )
            {
                return false;
            }
            $subscriberID = $subscriber->attribute( 'id' );
            //3. clean up subscription
            $querySubscription = "DELETE FROM ezcomment_subscription
                                  WHERE subscriber_id = $subscriberID
                                  AND content_id = '$contentID'";
            $db->query($querySubscription);
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Check if the subscription exists by a given contentID
     * @param string $contentID : the ID of content subscribed
     * @param string $subscriptionType : type of the subscription
     * @param string $email : email in table subscriber
     * @param integer $enabled : 1/0 - check if the subscriber is enabled.
     *                               Empty/false - not check if the subscriber is enabled
     * @return boolean: true if existing, false if not, null if error happens
     */
    static function exists( $contentID , $subscriptionType, $email = null, $enabled = false )
    {
        if( !isset( $contentID ) )
        {
            return null;
        }
        if( !isset( $subscriptionType ) )
        {
            return null;
        }
        $emailString = '';
        if( !is_null($email) )
        {
            $emailString = "WHERE email = '$email'";
        }
        $countArray = null;
        $db = eZDB::instance();
        if( $enabled === false )
        {
            $countArray = $db->arrayQuery( "SELECT count(*) AS count
                                       FROM ezcomment_subscription
                                       WHERE
                                       content_id = '$contentID'
                                       AND subscription_type = '$subscriptionType'
                                       AND subscriber_id IN
                                       (SELECT id FROM ezcomment_subscriber
                                       $emailString )");
        }
        else
        if( $enabled === 1 || $enabled === 0 )
        {
          $enabledString = "enabled = $enabled";
          if( $emailString != '' )
          {
              $enabledString = " AND " . $enabledString;
          }
          else
          {
              $enabledString = " WHERE " . $enabledString;
          }
          $countArray = $db->arrayQuery( "SELECT count(*) AS count
                                       FROM ezcomment_subscription
                                       WHERE
                                       content_id = '$contentID'
                                       AND subscription_type = '$subscriptionType'
                                       AND subscriber_id IN
                                       (SELECT id FROM ezcomment_subscriber
                                       $emailString
                                       $enabledString)");
        }
        else
        {
            return null;
        }
        $totalCount = $countArray[0]['count'];
        if( $totalCount === '0' )
        {
            return false;
        }
        else
        {
            return true;
        }
    }
}

?>