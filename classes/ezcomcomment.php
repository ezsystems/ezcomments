<?php

/**
 * ezcomComment persistent object class definition
 * 
 */
class ezcomComment extends eZPersistentObject
{
    /**
     * Construct, use {@link ezcomComment::create()} to create new objects.
     * 
     * @param array $row
     */
    public function __construct( $row )
    {
        parent::__construct( $row );
    }

    /**
     * Fields definition.
     * 
     * @static
     * @return array
     */
    public static function definition()
    {
        static $def = array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                                'language_id' => array( 'name' => 'LanguageID',
                                                                        'datatype' => 'integer',
                                                                        'default' => 0,
                                                                        'required' => true ),
                                                'created' => array( 'name' => 'Created',
                                                                    'datatype' => 'integer',
                                                                    'default' => 0,
                                                                    'required' => true ),
                                                'modified' => array( 'name' => 'Modified',
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true ),
                                                'user_id' => array( 'name' => 'UserID',
                                                                    'datatype' => 'integer',
                                                                    'default' => 0,
                                                                    'required' => true ),
                                                'session_key' => array( 'name' => 'SessionKey',
                                                                        'datatype' => 'string',
                                                                        'default' => '',
                                                                        'required' => true ),
                                                'ip' => array( 'name' => 'IPAddress',
                                                               'datatype' => 'string',
                                                               'default' => '',
                                                               'required' => true ),
                                                'contentobject_id' => array( 'name' => 'ContentObjectID',
                                                                             'datatype' => 'integer',
                                                                             'default' => 0,
                                                                             'required' => true ),
                                                'contentobject_attribute_id' => array( 'name' => 'ContentObjectAttributeID',
                                                                                       'datatype' => 'integer',
                                                                                       'default' => 0,
                                                                                       'required' => true ),
                                                'parent_comment_id' => array( 'name' => 'ParentCommentID',
                                                                              'datatype' => 'integer',
                                                                              'default' => 0,
                                                                              'required' => true ),
                                                'name' => array( 'name' => 'Name',
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                                'email' => array( 'name' => 'EMail',
                                                                  'datatype' => 'string',
                                                                  'default' => '',
                                                                  'required' => true ),
                                                'url' => array( 'name' => 'URL',
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ),
                                                'text' => array( 'name' => 'Text',
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                                'status' => array( 'name' => 'Status',
                                                                   'datatype' => 'integer',
                                                                   'default' => 0,
                                                                   'required' => true ),
                                                'notification' => array( 'name' => 'Notification',
                                                                         'datatype' => 'integer',
                                                                         'default' => 0,
                                                                         'required' => true ),
                                                'title' => array( 'name' => 'Title',
                                                                  'datatype' => 'string',
                                                                  'default' => '',
                                                                  'required' => true ) ),
                             'keys' => array( 'id' ),
                             'function_attributes' => array( 
                                                            'contentobject' => 'contentObject' ),
                             'increment_key' => 'id',
                             'class_name' => 'ezcomComment',
                             'name' => 'ezcomment' );
        return $def;
    }

    /**
     * get the contentobject of one comment
     * @return unknown_type
     */
    public function contentObject()
    {
        if ( isset( $this->ContentObjectID ) and $this->ContentObjectID )
        {
            return eZContentObject::fetch( $this->ContentObjectID );
        }
        return null;
    }
    
    /**
     * Creates new ezcomComments object
     * 
     * @static
     * @param array $row
     * @return ezcomComment
     */
    public static function create( $row = array() )
    {
        $object = new self( $row );
        return $object;
    }

    /**
     * Fetch comment by given id.
     * 
     * @param int $id
     * @return null|ezcomComment
     */
    static function fetch( $id )
    {
        $cond = array( 'id' => $id );
        $return = eZPersistentObject::fetchObject( self::definition(), null, $cond );
        return $return;
    }
    
    static function fetchForUser( $userID, $sorts = null, $offset = null, $length = null, $notification = false, $status = false  )
    {
        $cond = array();
        $cond['user_id'] = $userID;
        if ( $notification !== false )
        {
            $cond['notification'] = $notification;
        }
        if( $status !== false )
        {
            $cond['status'] = $status;
        }
        $limit = null;
        if( !is_null( $offset ) )
        {
           $limit = array();
           $limit = array( 'offset' => $offset, 'length' => $length );
        }
        
        $return = eZPersistentObject::fetchObjectList( self::definition(), null, $cond, $sorts, $limit );
        return $return;
    }
    
    static function fetchByEmail( $email, $sorts = null, $offset = null, $length = null, $notification = false, $status = false  )
    {
        $cond = array();
        $cond['email'] = $email;
        if ( $notification !== false )
        {
            $cond['notification'] = $notification;
        }
        if( $status !== false )
        {
            $cond['status'] = $status;
        }
        $limit = null;
        if( !is_null( $offset ) )
        {
           $limit = array();
           $limit = array( 'offset' => $offset, 'length' => $length );
        }
        $return = eZPersistentObject::fetchObjectList( self::definition(), null, $cond, $sorts, $limit );
        return $return;
    }
    
    static function fetchByContentObjectID($contentobject_id, $sorts = null, $offset = null, $length = null)
    {
        $cond = array();
        $cond['contentobject_id'] = $contentobject_id;
        if( is_null( $offset ) || is_null( $length ) )
        {
            return null;
        }
        else
        {
            $limit = array( 'offset' => $offset, 'length' => $length);
            $return = eZPersistentObject::fetchObjectList( self::definition(), null, $cond, $sorts, $limit );
            return $return;
        
        }
    }

    static function fetchByTime( $timefield, $time )
    {
        $cond = array();
        if( $timefield == 'created' )
        {
            $cond['created'] = $time;
        }
        else if( $timefield == 'modified' )
        {
            $cond['modified'] = $time;
        }
        else
        {
            return null;
        }
        $return = eZPersistentObject::fetchObject( self::definition(), null, $cond );
        return $return;
    }
    
    
    static function updateFields( $fields, $conditions )
    {
        $parameters = array();
        $parameters['definition'] = self::definition();
        $parameters['update_fields'] = $fields;
        $parameters['conditions'] = $conditions;
        //use try to catch the error
        eZPersistentObject::updateObjectList( $parameters );
    }
    
    /**
     * count the comments by content object id
     * @param int $contentObjectID
     * @return int : count number
     */
    static function countByContent( $contentObjectID )
    {
        $cond = array();
        $cond['contentobject_id'] = $contentObjectID;
//        $cond['status'] = 1;
        return eZPersistentObject::count( self::definition(), $cond );
    }
    
    /**
     * 
     * @param $comment: a comment object
     * @return true if the validation succeeds
     *         error message if the validation fails.
     */
    static function validateInput( $comment )
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
        if ( $comment->attribute( 'language_id' ) == '' || !is_int( $comment->attribute( 'language_id' ) ) )
        {
            return ezi18n( 'comment/view/validateInput', 'Language is empty or not int!' );
        }
        if ( $comment->attribute( 'contentobject_id' ) == '' || !is_int( $comment->attribute( 'contentobject_id' ) ) )
        {
            return ezi18n( 'comment/view/validateInput', 'Object ID can not be empty or string!' );
        }
        return true;
    }
    
    /**
     * 
     * Add comment into ezcomment table and related data into ezcomment_subscriber,
     *                                                        ezcomment_subscription,
     *                                                        ezcomment_notification
     * 1) Add a comment into ezcomment table
     * 2) If 'notified' is true
     *      add the user as a subscriber if subscriber with same email doesn't exist
     *      otherwise get the subscriber
     * 3) If 'notified' is true
     *    if the subscription with user's email and contentid doesn't exist, add a new subscription,
     * 4) If there is subscription, add the comment into notifiction queue
     *  
     * @param $commentInput: an array that contains the input
     *        title, name, url, email, created, modified, text, notified
     * @param $user: user object
     * @param $contentObjectID: id of the content object
     * @param $languageID: language id of the content object
     * @param $time: comment time
     * @return array: result of adding
     *      result:true/falses
     *      errors:array error when adding
     *      warnings:array warnings wehn adding
     *      
     */
    static function addComment( $commentInput, $user, $contentObjectID, $languageID, $time = null )
    {
        $result = array();
        $result['result'] = true;
        $result['errors'] = array();
        $result['warnings'] = array();
        $comment = ezcomComment::create();
        if( isset( $commentInput['title'] ) )
        {
            $comment->setAttribute( 'title', $commentInput['title'] );
        }
        $comment->setAttribute( 'name', $commentInput['name'] );
        if( isset( $commentInput['url'] ) )
        {
            $comment->setAttribute( 'url', $commentInput['url'] );
        }
        $comment->setAttribute( 'language_id', $languageID);
        $comment->setAttribute( 'email', $commentInput['email'] );
        $comment->setAttribute( 'text', $commentInput['text'] );
        $comment->setAttribute( 'user_id', $user->attribute( 'id' ) );
        $comment->setAttribute( 'contentobject_id', $contentObjectID);
        $currentTime = null;
        if( is_null( $time ) )
        {
            $currentTime = time();
        }
        else
        {
            $currentTime = $time;
        }
        $comment->setAttribute( 'created', $currentTime);
        $comment->setAttribute( 'modified', $currentTime);
        if( $commentInput['notified'] === true )
        {
            $comment->setAttribute( 'notification', 1 );
        }
        else
        {
            $comment->setAttribute( 'notification', 0 );
        }
        $comment->store();
        
        $contentID = $contentObjectID . '_' . $languageID;
        $subscriptionType = 'ezcomcomment';
        
        $hasSubscription = false;
        $subscriptionMessage = "";
        if( $commentInput['notified'] === true )
        {
            //2 insert into subscriber
            $ezcommentsINI = eZINI::instance( 'ezcomments.ini' );
            $subscriber = ezcomSubscriber::fetchByEmail( $commentInput['email'] );
            //if there is no data in subscriber for same email, save it
            if( is_null( $subscriber ) )
            {
                $subscriber = ezcomSubscriber::create();
                $subscriber->setAttribute( 'user_id', $user->attribute( 'contentobject_id' ));
                $subscriber->setAttribute( 'email', $commentInput['email'] );
                if( $user->isAnonymous() )
                {
                    $subscriber->setAttribute( 'hash_string', hash('md5',uniqid()) );
                }
                $subscriber->store();
                $subscriber = ezcomSubscriber::fetchByEmail( $commentInput['email'] );
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
                                            $commentInput['email']);
            if( $hasSubscription === false )
            {
                $subscription = ezcomSubscription::create();
                $subscription->setAttribute( 'user_id', $user->attribute( 'contentobject_id' ) );
                $subscription->setAttribute( 'subscriber_id', $subscriber->attribute( 'id' ) );
                $subscription->setAttribute( 'subscription_type', "ezcomcomment" );
                $subscription->setAttribute( 'content_id', $contentID );
                $subscription->setAttribute( 'subscription_time', $currentTime );
                $subscription->store();
            }
        }
        
        // 3.4 insert data into notification queue
        // if there is subscription,not adding to notification queue
        if( ezcomSubscription::exists( $contentID, $subscriptionType ) )
        {
            $notification = ezcomNotification::create();
            $notification->setAttribute( 'contentobject_id', $contentObjectID );
            $notification->setAttribute( 'language_id', $languageID );
            $notification->setAttribute( 'comment_id', $comment->attribute('id') );
            $notification->store();
        }
        return $result;
    }
}

?>