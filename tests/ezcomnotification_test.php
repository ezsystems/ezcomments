<?php

/**
 * ezcomNotificationTest class definition
 * 
 */
class ezcomNotificationTest extends ezpDatabaseTestCase
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
        $this->setName( "ezcommNotification object test" );
    }

    public function setUp()
    {
        parent::setUp();

        ezpTestDatabaseHelper::insertSqlData( $this->sharedFixture, $this->sqlFiles );
    }

    /**
     * 1. Create a new ezcomNotification object
     * 2. Check if data are stored properly
     * 
     */
    public function testCreateObject()
    {
        $notification = ezcomNotification::create();
        $notification->setAttribute( 'contentobject_id', 12 );
        $notification->setAttribute( 'language_id', 2 );
        $notification->setAttribute( 'status', 1 );        
        $notification->store();
        
        $this->assertType( 'ezcomNotification', $notification );
        $this->assertEquals( 12, $notification->attribute( 'contentobject_id' ) );
        $this->assertEquals( 2, $notification->attribute( 'language_id' ) );
        $this->assertEquals( 1, $notification->attribute( 'status' ) );
    }

    /**
     * 1. Fetch ezcomNotification object
     * 2. Check is object is instance of ezcomNotification class
     * 3. Fetch ezcomNotification object which does not exist
     * 4. Check if result equals to null
     * 
     */
    public function testFetchObject()
    {
        $notification = ezcomNotification::fetch( 1 );
        $this->assertType( 'ezcomNotification', $notification );
        
        $notification = ezcomNotification::fetch( 2 );
        $this->assertEquals( null, $notification );
    }
}
?>