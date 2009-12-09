<?php
/**
 * 
 * @author xc
 *
 */
class ezcomCommentTest extends ezpDatabaseTestCase
{
    /**
     * Path to the DB schema.
     * 
     * @var array
     */
    protected $sqlFiles = array( array( 'extension/ezcomments/sql/', 'schema.sql' ) );

    public function __construct()
    {
        parent::__construct();
        $this->setName( "ezcommComment object test" );
    }

    public function setUp()
    {
        parent::setUp();

        ezpTestDatabaseHelper::insertSqlData( $this->sharedFixture, $this->sqlFiles );
    }

    /**
     * 1. Create a new comment object with dummy data
     * 2. Check if data are stored properly
     * 
     */
    public function testCreateObject()
    {
        // Create a new comment
        $comment = ezcomComment::create();
        $comment->setAttribute( 'contentobject_id', 12 );
        $comment->setAttribute( 'language_id', 2 );
        $comment->setAttribute( 'created', 21213423 );
        $comment->setAttribute( 'modified', 21321231 );
        $comment->setAttribute( 'user_id', 14 );
        $comment->setAttribute( 'session_key', 'a2e4822a98337283e39f7b60acf85ec9' );
        $comment->setAttribute( 'ip', '10.0.2.122' );
        $comment->setAttribute( 'name', 'xc' );
        $comment->setAttribute( 'email', 'xc@ez.no' );
        $comment->setAttribute( 'url', 'http://ez.no' );
        $comment->setAttribute( 'text', 'ezcomComment object test comment.' );
        $comment->setAttribute( 'notification', 1 );
        $comment->store();

        $this->assertType( 'ezcomComment', $comment );
        $this->assertEquals( 12, $comment->attribute( 'contentobject_id' ) );
        $this->assertEquals( 2, $comment->attribute( 'language_id' ) );
        $this->assertEquals( 21213423, $comment->attribute( 'created' ) );
        $this->assertEquals( 21321231, $comment->attribute( 'modified' ) );
        $this->assertEquals( 14, $comment->attribute( 'user_id' ) );
        $this->assertEquals( 'a2e4822a98337283e39f7b60acf85ec9', $comment->attribute( 'session_key' ) );
        $this->assertEquals( '10.0.2.122', $comment->attribute( 'ip' ) );
        $this->assertEquals( 'xc', $comment->attribute( 'name' ) );
        $this->assertEquals( 'xc@ez.no', $comment->attribute( 'email' ) );
        $this->assertEquals( 'http://ez.no', $comment->attribute( 'url' ) );
        $this->assertEquals( 'ezcomComment object test comment.', $comment->attribute( 'text' ) );
        $this->assertEquals( 1, $comment->attribute( 'notification' ) );
    }

    /**
     * 1. Fetch ezcomComment object
     * 2. Check is object is instance of ezcomComment class
     * 3. Fetch ezcomComment object which does not exist
     * 4. Check if result equals to null
     *
     */
    public function testFetchObject()
    {
        $comment = ezcomComment::fetch( 1 );
        $this->assertType( 'ezcomComment', $comment );
        
        $comment = ezcomComment::fetch( 2 );
        $this->assertEquals( null, $comment );
    }
    
    /**
     * 1. store an ezcomcomment object into database
     * 2. Fetch the ezcomComment object list
     * 3. assert fetchForUser( userid )
     * 4. Assert fetchForUser( userid, notification )
     * 5. Assert fetchForUser( userid,notification, status )
     * 6. Assert fetchForUser( userid ) when there is no record in database
     */
    public function testFetchForUser()
    {
        // Create a new comment
        $comment = ezcomComment::create();
        $comment->setAttribute( 'contentobject_id', 13 );
        $comment->setAttribute( 'language_id', 2 );
        $comment->setAttribute( 'created', 21213423 );
        $comment->setAttribute( 'modified', 21321231 );
        $comment->setAttribute( 'user_id', 14 );
        $comment->setAttribute( 'session_key', 'a2e4822a98337283e39f7b60acf85ec9' );
        $comment->setAttribute( 'ip', '10.0.2.122' );
        $comment->setAttribute( 'name', 'xc' );
        $comment->setAttribute( 'email', 'xc@ez.no' );
        $comment->setAttribute( 'url', 'http://ez.no' );
        $comment->setAttribute( 'status', 1 );
        $comment->setAttribute( 'text', 'ezcomComment object test comment.' );
        $comment->setAttribute( 'notification', 0 );
        $comment->store();
        
        // Assert fetchForUser( userid )
        $list = ezcomComment::fetchForUser( 14 );
        $this->assertType( 'array', $list );
        $this->assertType( 'ezcomComment', $list[0] );
        $this->assertEquals( 12, $list[0]->attribute( 'contentobject_id' ) );
        
        // Assert fetchForUser( userid, notification )
        $list = ezcomComment::fetchForUser( 14, 0 );
        $this->assertType( 'array', $list );
        $this->assertType( 'ezcomComment', $list[0] );
        $this->assertEquals( 13, $list[0]->attribute( 'contentobject_id' ) );
        
        // Assert fetchForUser( userid,notification, status )
        $list = ezcomComment::fetchForUser( 14, false, 1 );
        $this->assertType( 'array', $list );
        $this->assertType( 'ezcomComment', $list[0] );
        $this->assertEquals( 13, $list[0]->attribute( 'contentobject_id' ) );
        
        // Assert fetchForUser( userid ) when there is no record in database
        $list = ezcomComment::fetchForUser( 15 );
        $this->assertType( 'array', $list );
        $this->assertSame( 0, count( $list ) );
    }
}
?>