<?php
/**
 * eZ Comment notification persistent object class definition
 */

class ezcomNotification extends eZPersistentObject
{
    /**
     * Construct, use {@link ezcomComment::create()} to create new objects.
     * @param array $row
     */
    public function __construct( $row )
    {
       parent::__construct( $row );
    }
    /**
     * Fields definition
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
                                                                             'required' => true),
                                                'language_id' => array( 'name' => 'LanguageID',
                                                                        'datatype' => 'integer',
                                                                        'default' => 0,
                                                                        'required' => true),
                                                'status' => array( 'name' => 'Status',
                                                                   'datatype' => 'integer',
                                                                   'default' => 1,
                                                                   'required' => true),
                                                                       ),
                             'keys' => 'id',
                             'function_attributes' => array(),
                             'increment_key' => 'id',
                             'class_name' => 'ezcomNotification',
                             'name' => 'ezcomment_notification');
        return $def;
    }
    
    /**
     * create new ezcomNotification object
     * @static
     * @param array $row
     * @return ezcomNotification
     */
    public static function create( $row = array() ){
        $object = new self( $row );
        return $object;
    }
    
    /**
     * fetch notification by given id
     * @param int $id
     * @return null|ezcomNotification
     */
    static function fetch( $id ){
        $cond = array( 'id' => $id );
        $return = eZPersistentObject::fetchObject( self::definition(), null, $cond );
        return $return;
    }
}

?>