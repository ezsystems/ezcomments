<?php
/**
 * 
 * @author xc
 *
 */
class ezcomCommentTest extends ezpDatabaseTestCase
{
   public function __construct()
   { 
       parent::__construct();
       $this->setName( "ezcommComment object test " );
   }
   
   public function setUp()
   {
       parent::setUp();
   }
   
  /**
   * 1. insert 1 row
   * 2. fetch one row , vertify the result
   */
  public function testFetchObject()
  {
      $db = eZDB::instance();
      //1. insert 1 row
      $query = "INSERT INTO ezcomment( contentobject_id,
                                        language_id,
                                        created,
                                        modified,
                                        user_id,
                                        session_key,
                                        ip,
                                        name,
                                        email,
                                        url,
                                        text,
                                        notification
                                        )
                                VALUES( 12,
                                        2,
                                        21213423,
                                        21321231,
                                        01,
                                        'dfsfsfafdaf',
                                        '10.0.2.122',
                                        'xc',
                                        'xc@ez.no',
                                        'http://ez.no',
                                        'hello, this is a test!',
                                        1
                                        )";
      $db->query($query);
      $db->commit();
    //2. fetch the list
      $result = $db->arrayQuery( "SELECT id FROM ezcomment ORDER BY id DESC LIMIT 0,1" );
      $id = $result[0]['id'];
      $comment = ezcomComment::fetch( $id );
      $this->assertEquals( 12, $comment->attribute( 'contentobject_id' ) );
      $this->assertEquals( 2, $comment->attribute( 'language_id' ) );
      $this->assertEquals( 21213423, $comment->attribute( 'created' ) );
      $this->assertEquals( 21321231, $comment->attribute( 'modified' ) );
      $this->assertEquals( 01, $comment->attribute( 'user_id' ) );
      $this->assertEquals( 'dfsfsfafdaf', $comment->attribute( 'session_key' ) );
      $this->assertEquals( '10.0.2.122', $comment->attribute( 'ip' ) );
      $this->assertEquals( 'xc', $comment->attribute( 'name' ) );
      $this->assertEquals( 'xc@ez.no', $comment->attribute( 'email' ) );
      $this->assertEquals( 'http://ez.no', $comment->attribute( 'url' ) );
      $this->assertEquals( 'hello, this is a test!', $comment->attribute( 'text' ) );
      $this->assertEquals( 1, $comment->attribute( 'notification' ) );
      $db->query( 'DELETE FROM ezcomment WHERE id='. $id );
  }
    
}
?>