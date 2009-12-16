<?php

/**
 * ezcomSubscription persistent object class definition
 * 
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
                                                'sub_type' => array( 'name' => 'SubType',
                                                                        'datatype' => 'string',
                                                                        'default' => '',
                                                                        'required' => true ),
                                                'sub_id' => array( 'name' => 'SubID',
                                                                   'datatype' => 'string',
                                                                   'default' => '',
                                                                   'required' => true ),
                                                'sub_time' => array( 'name' => 'SubTime',
                                                                   'datatype' => 'integer',
                                                                   'default' => 1,
                                                                   'required' => true ),
                                                'enabled' => array( 'name' => 'Enabled',
                                                                   'datatype' => 'integer',
                                                                   'default' => 1,
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
    
}

?>