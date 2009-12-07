<?php
class ezcomNotificationTest extends ezpDatabaseTestCase
{
   public function __construct()
   { 
       parent::__construct();
       $this->setName( "ezcommNotification object test " );
   }
   
   /**
    * test fetch object
    * 
    */
   public function testFetchObject()
   {
      $db = eZDB::instance();
      $query = 'INSERT INTO ezcomment_notification( 
                                                 contentobject_id,
                                                 language_id
                                                 )
                                          VALUES(12,
                                                 2
                                                 )'; 
      $db->query( $query );
      $db->commit();
      $row = $db->arrayQuery( 'SELECT id FROM ezcomment_notification ORDER BY id DESC LIMIT 0,1' );
      $id = $row[0]['id'];
      $notification = ezcomNotification::fetch( $id );
      $this->assertEquals( 12, $notification->attribute( 'contentobject_id' ) );
      $this->assertEquals( 2, $notification->attribute( 'language_id' ) );
      $this->assertEquals( 1, $notification->attribute( 'status' ) );
      $db->query( 'DELETE FROM ezcomment_notification WHERE id = '. $id );
   }
}
?>