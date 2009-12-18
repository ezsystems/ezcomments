<?php

/**
 * ezcomSubscriber persistent object class definition
 * 
 */
class ezcomSubscriber extends eZPersistentObject
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
                                                'user_id' => array( 'name' => 'UserID',
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true ),
                                                'email' => array( 'name' => 'EMail',
                                                                        'datatype' => 'string',
                                                                        'default' => '',
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
                             'class_name' => 'ezcomSubscriber',
                             'name' => 'ezcomment_subscriber' );
        return $def;
    }

    /**
     * Create new ezcomSubscriber object
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
     * Fetch ezcomSubscriber by given id
     * 
     * @param int $id
     * @return null|ezcomSubscriber
     */
    static function fetch( $id )
    {
        $cond = array( 'id' => $id );
        $return = eZPersistentObject::fetchObject( self::definition(), null, $cond );
        return $return;
    }
    
/**
     * Fetch ezcomSubscriber by given email
     * 
     * @param int $email
     * @return null|ezcomSubscriber
     */
    static function fetchByEmail( $email )
    {
        $cond = array( 'email' => $email );
        $return = eZPersistentObject::fetchObject( self::definition(), null, $cond );
        return $return;
    }
    
    /* Fetch ezcomSubscriber by given hashstring
     * 
     * @param string $hashstring
     * @return null|ezcomSubscriber
     */
    static function fetchByHashString( $hashString )
    {
        $cond = array( 'hash_string' => $hashString );
        $return = eZPersistentObject::fetchObject( self::definition(), null, $cond );
        return $return;
    }
}

?>