<?php
/**
 * File containing the ezcomSubscription class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

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
                                                                   'datatype' => 'integer',
                                                                   'default' => 0,
                                                                   'required' => true ),
                                                'language_id' => array( 'name'=> 'LanguageID',
                                                                   'datatype' => 'integer',
                                                                   'default' => 0,
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
                             'function_attributes' => array( 'contentobject' => 'contentObject' ),
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

    static function fetchListBySubscriberID( $subscriberID, $languageID = false, $enabled = false, $sorts = null, $limit = null )
    {
        $cond = array();
        $cond['subscriber_id'] = $subscriberID;
        if ( $enabled !== false )
        {
            $cond['enabled'] = $enabled;
        }
        if ( $languageID !== false )
        {
            $cond['language_id'] = $languageID;
        }
        return eZPersistentObject::fetchObjectList( self::definition(),
                                                    null,
                                                    $cond,
                                                    $sorts,
                                                    $limit);
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
    static function countWithSubscriberID( $subscriberID, $languageID =false , $enabled = false )
    {
        $cond = array();
        $cond['subscriber_id'] = $subscriberID;
        if ( $enabled !== false )
        {
            $cond['enabled'] = $enabled;
        }
        if ( $languageID !== false )
        {
            $cond['language_id'] = $languageID;
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
        if ( $this->attribute( 'enabled' ) )
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
     * get the content of the subscription
     * @return ezcontentobject
     */
    public function contentObject()
    {
        $contentID = $this->attribute( 'content_id' );
        //TODO:try to get the language id
        $languageID = $this->attribute( 'language_id' );
        return eZContentObject::fetch( $contentID );
    }
    
    /**
     * clean up subscription based on an email address and content, language,
     *  make the subscription consistent.
     *  example:
     * @param string $email
     * @return true - there is subscription deleted
     *         false - no subscription deleted
     *         null - error
     */
    static function cleanupSubscription( $email, $contentobjectID, $languageID )
    {
        //2. get subscriber
        $subscriber = ezcomSubscriber::fetchByEmail( $email );
        if ( is_null( $subscriber ) )
        {
            return false;
        }
        $subscriberID = $subscriber->attribute( 'id' );
        //3. clean up subscription
        $querySubscription = "DELETE FROM ezcomment_subscription" . 
                             " WHERE subscriber_id = $subscriberID ".
                             " AND content_id = $contentID" .
                             " AND language_id= $languageID";
        $db->query($querySubscription);
        return true;
    }

    /**
     * Check if the subscription exists by a given contentID
     * @param string $contentID : the ID of content subscribed
     * @param string $languageID : the language ID of content
     * @param string $subscriptionType : type of the subscription
     * @param string $email : email in table subscriber
     * @param integer $enabled : 1/0 - check if the subscriber is enabled.
     *                               Empty/false - not check if the subscriber is enabled
     * @return boolean: true if existing, false if not, null if error happens
     */
    static function exists( $contentID, $languageID , $subscriptionType, $email = null, $enabled = false )
    {
      
        $emailString = '';
        if ( !is_null($email) )
        {
            $emailString = " WHERE email = '$email'";
        }
        $countArray = null;
        $db = eZDB::instance();
        if ( $enabled === false )
        {
            $countArray = $db->arrayQuery( "SELECT count(*) AS count" .
                                       " FROM ezcomment_subscription" .
                                       " WHERE " .
                                       " content_id = $contentID" .
                                       " AND language_id = $languageID" .
                                       " AND subscription_type = '$subscriptionType'" .
                                       " AND subscriber_id IN" . 
                                       "( SELECT id FROM ezcomment_subscriber" .
                                       "$emailString )");
        }
        else
        if ( $enabled === 1 || $enabled === 0 )
        {
          $enabledString = "enabled = $enabled";
          if ( $emailString != '' )
          {
              $enabledString = " AND " . $enabledString;
          }
          else
          {
              $enabledString = " WHERE " . $enabledString;
          }
          $countArray = $db->arrayQuery( "SELECT count(*) AS count" .
                                       " FROM ezcomment_subscription" .
                                       " WHERE" .
                                       " content_id = $contentID" .
                                       " AND language_id = $languageID" .
                                       " AND subscription_type = '$subscriptionType'" .
                                       " AND subscriber_id IN" .
                                       " ( SELECT id FROM ezcomment_subscriber" .
                                       $emailString .
                                       "$enabledString )" );
        }
        else
        {
            return null;
        }
        $totalCount = $countArray[0]['count'];
        if ( $totalCount === '0' )
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