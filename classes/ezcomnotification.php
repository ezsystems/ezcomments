<?php

/**
 * ezcomNotification persistent object class definition
 * 
 */
class ezcomNotification extends eZPersistentObject
{
    /**
     * Construct, use {@link ezcomNotification::create()} to create new objects.
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
                                                'contentobject_id' => array( 'name' => 'ContentObjectID',
                                                                             'datatype' => 'integer',
                                                                             'default' => 0,
                                                                             'required' => true ),
                                                'language_id' => array( 'name' => 'LanguageID',
                                                                        'datatype' => 'integer',
                                                                        'default' => 0,
                                                                        'required' => true ),
                                                'send_time' => array( 'name' => 'SendTime',
                                                                   'datatype' => 'integer',
                                                                   'default' => 0,
                                                                   'required' => true ),
                                                'status' => array( 'name' => 'Status',
                                                                   'datatype' => 'integer',
                                                                   'default' => 1,
                                                                   'required' => true ),
                                                'comment_id' => array( 'name' => 'CommentID',
                                                                        'datatype' => 'integer',
                                                                        'default' => 0,
                                                                        'required' => true ) ),
                             'keys' => array( 'id' ),
                             'function_attributes' => array(),
                             'increment_key' => 'id',
                             'class_name' => 'ezcomNotification',
                             'name' => 'ezcomment_notification' );
        return $def;
    }

    /**
     * Create new ezcomNotification object
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
     * Fetch notification by given id
     * 
     * @param int $id
     * @return null|ezcomNotification
     */
    static function fetch( $id )
    {
        $cond = array( 'id' => $id );
        $return = eZPersistentObject::fetchObject( self::definition(), null, $cond );
        return $return;
    }
    
    /**
     * Fetch the list of notification
     * @param $length: count of the notification to be fetched
     * @param $status: the status of the notification
     * @param $offset: offset
     * @return notification list
     */
    static function fetchNotificationList( $status = 1, $length = null, $offset = 0, $sorts = null )
    {
        $cond = array();
        if( is_null( $status ) )
        {
            $cond = null;
        }
        else
        {
        $cond['status'] = $status;
        }
        $limit = array();
        if( is_null( $length ) )
        {
            $limit = null;
        }
        else
        {
            $limit['offset'] = $offset;
            $limit['length'] = $length;
        }
        return eZPersistentObject::fetchObjectList( self::definition(), null, $cond, $sorts, $limit );
    }
}

?>