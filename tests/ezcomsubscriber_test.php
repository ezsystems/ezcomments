<?php
/**
 * 
 * @author xc
 *
 */
class ezcomSubscriberTest extends ezpDatabaseTestCase
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
        $this->setName( "ezcommSubscriber object test" );
    }

    public function setUp()
    {
        parent::setUp();
        ezpTestDatabaseHelper::insertSqlData( $this->sharedFixture, $this->sqlFiles );
    }
    
    /**
     * Test create ezcomsubscriber object 
     */
    public function testCreateObject()
    {
        $subscriber = ezcomSubscriber::create();
        $subscriber->setAttribute( 'user_id', 10 );
        $subscriber->setAttribute( 'email', 'xc@ez.no' );
        $subscriber->setAttribute( 'enabled', 0 );
        $hashString = hash('md5','xc@ez.no');
        $subscriber->setAttribute( 'hash_string', $hashString );
        $subscriber->store();
        $this->assertType( 'ezcomSubscriber', $subscriber );
        $this->assertEquals( 'xc@ez.no', $subscriber->attribute( 'email' ) );
        $this->assertEquals( 10, $subscriber->attribute( 'user_id' ) );
        $this->assertEquals( 0, $subscriber->attribute( 'enabled' ) );
        $this->assertEquals( $hashString,$subscriber->attribute( 'hash_string' ) );
    }
    
    /**
     * Test fetchByHashString method
     */
    function testFetchByHashString()
    {
        $hashString = hash('md5','xc@ez.no');
        $subscriber = ezcomSubscriber::fetchByHashString( $hashString );
        $this->assertType( 'ezcomSubscriber', $subscriber );
        $hashString2 = hash('md5','xc11111111111@wfsasdfasf.noddfdsf');
        $subscriber2 = ezcomSubscriber::fetchByHashString( $hashString2 );
        $this->assertEquals( null, $subscriber2 );
    }
    
    /**
     * Test fetchByEmail method
     */
    function testFetchByEmail()
    {
        $subscriber = ezcomSubscriber::fetchByEmail( 'xc@ez.no' );
        $this->assertType( 'ezcomSubscriber', $subscriber );
        $subscriber = ezcomSubscriber::fetchByEmail( 'xc1111111111@111.com' );
        $this->assertEquals( null, $subscriber );
    }
    
    /**
     * Test fetch method
     */
    function testFetch()
    {
        $subscriber = ezcomSubscriber::fetch( 1 );
        $this->assertType( 'ezcomSubscriber', $subscriber );
        $subscriber = ezcomSubscriber::fetch( 100 );
        $this->assertEquals( null, $subscriber );
    }
}