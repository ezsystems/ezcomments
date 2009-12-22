<?php
/**
 * 
 * @author xc
 *
 */
class ezcomSubscriptionTest extends ezpDatabaseTestCase
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
        $this->setName( "ezcommSubscription object test" );
    }

    public function setUp()
    {
        parent::setUp();
        ezpTestDatabaseHelper::insertSqlData( $this->sharedFixture, $this->sqlFiles );
    }
    
    /**
     * Test create ezcomSubscription object 
     */
    public function testCreateObject()
    {
        $subscription = ezcomSubscription::create();
        $now = time();
        $subscription->setAttribute( 'user_id', 14 );
        $subscription->setAttribute( 'subscriber_id', 4 );
        $subscription->setAttribute( 'subscription_type', 'ezcomcomment' );
        $subscription->setAttribute( 'content_id', '209_2' );
        $subscription->setAttribute( 'subscription_time', $now );
        $subscription->setAttribute( 'enabled', 1 );
        $subscription->store();

        $this->assertType( 'ezcomSubscription', $subscription );
        $this->assertEquals( 14, $subscription->attribute( 'user_id' ) );
        $this->assertEquals( 4, $subscription->attribute( 'subscriber_id' ) );
        $this->assertEquals( 'ezcomcomment', $subscription->attribute( 'subscription_type' ) );
        $this->assertEquals( '209_2', $subscription->attribute( 'content_id' ) );
        $this->assertEquals( $now, $subscription->attribute( 'subscription_time' ) );
        $this->assertEquals( 1, $subscription->attribute( 'enabled' ) );
    }
    
    /**
     * Test fetch method
     */
    public function testFetch()
    {
        $subscription = ezcomSubscription::fetch( 1 );
        $this->assertType( 'ezcomSubscription', $subscription );
        $subscription = ezcomSubscription::fetch( 1001 );
        $this->assertEquals( null, $subscription );
    }
    
    /**
     * Test exists method
     */
    public function testExists()
    {
        $subscriptionType = 'ezcomcomment';
        //insert a subscriber object and subscription object
        $time = time();
        $testemail = 'testemail@ez.no';
        $subscriber = ezcomSubscriber::create();
        $subscriber->setAttribute( 'email', $testemail );
        $subscriber->setAttribute( 'user_id', 10 );
        $subscriber->setAttribute( 'enabled', 0 );
        $subscriber->store();
        
        $subscription = ezcomSubscription::create();
        $subscription->setAttribute( 'user_id', 15 );
        $subscription->setAttribute( 'subscriber_id', $subscriber->attribute( 'id' ) );
        $subscription->setAttribute( 'subscription_type', $subscriptionType );
        $subscription->setAttribute( 'content_id', '210_2' );
        $subscription->setAttribute( 'subscription_time', $time );
        $subscription->setAttribute( 'enabled', 1 );
        $subscription->store();
        
        //1. test if the subscription exists by contentID
        $exists = ezcomSubscription::exists( '210_2', $subscriptionType );
        $this->assertTrue( $exists );
        $exists = ezcomSubscription::exists( '20009', $subscriptionType);
        $this->assertFalse( $exists );
        $exists = ezcomSubscription::exists( '20009', 'othertypesssss' );
        $this->assertFalse( $exists );
        $exists = ezcomSubscription::exists( '20009', null );
        $this->assertNull( $exists );
        //2. test if the subsription exists by contenetID and enabled
        $exists = ezcomSubscription::exists( '210_2', $subscriptionType, null, 0 );
        $this->assertTrue( $exists );
        $exists = ezcomSubscription::exists( '210_2', $subscriptionType, null, 1 );
        $this->assertFalse( $exists );
        $exists = ezcomSubscription::exists( '210_2', $subscriptionType, null, 12 );
        $this->assertNull( $exists );
        
        //3. test if the subscription exists by contentID and email
        $exists = ezcomSubscription::exists( '210_2', $subscriptionType, $testemail );
        $this->assertTrue( $exists );
        $exists = ezcomSubscription::exists( '210_2', $subscriptionType, 'otheremail@ez.no' );
        $this->assertFalse( $exists );
        
        //4. test if the subscription exists by contentID, email and enabled
        $exists = ezcomSubscription::exists( '210_2', $subscriptionType, $testemail, 0 );
        $this->assertTrue( $exists );
        $exists = ezcomSubscription::exists( '210_2', $subscriptionType, $testemail, 1 );
        $this->assertFalse( $exists );
        $exists = ezcomSubscription::exists( '210_2', $subscriptionType, 'otheremail@ez.no', 0 );
        $this->assertFalse( $exists );
        $exists = ezcomSubscription::exists( '210_2', $subscriptionType, $testemail, 46 );
        $this->assertNull( $exists );
    }
    
}