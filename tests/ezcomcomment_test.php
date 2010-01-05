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
        $this->setName( "ezcomComment object test" );
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
        $comment->setAttribute( 'user_id', 15 );
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
        $list = ezcomComment::fetchForUser( 15 );
        $this->assertType( 'array', $list );
        $this->assertType( 'ezcomComment', $list[0] );
        $this->assertEquals( 13, $list[0]->attribute( 'contentobject_id' ) );
        
        // Assert fetchForUser( userid, notification )
        $list = ezcomComment::fetchForUser( 15, 0 );
        $this->assertType( 'array', $list );
        $this->assertType( 'ezcomComment', $list[0] );
        $this->assertEquals( 13, $list[0]->attribute( 'contentobject_id' ) );
        
        // Assert fetchForUser( userid,notification, status )
        $list = ezcomComment::fetchForUser( 15, null, null, null, false, 1 );
        $this->assertType( 'array', $list );
        $this->assertType( 'ezcomComment', $list[0] );
        $this->assertEquals( 13, $list[0]->attribute( 'contentobject_id' ) );
        
        // Assert fetchForUser( userid ) when there is no record in database
        $list = ezcomComment::fetchForUser( 16 );
        $this->assertType( 'array', $list );
        $this->assertSame( 0, count( $list ) );
    }
    
    /**
     * Test the validateInput method in ezcomComment
     * to do: test the localized error
     * @return 
     */
    public function testValidateInput()
    {
        $result = ezcomComment::validateInput( null );
        $this->assertSame( 'Parameter is empty!', $result );
        $comment = ezcomComment::create();
        $result = ezcomComment::validateInput( $comment );
        $this->assertNotSame( 'Parameter is empty!', $result );
        
        //validate name
        $comment->setAttribute( 'name', '' );
        $result = ezcomComment::validateInput( $comment );
        $this->assertSame( 'Name is empty!', $result );
        
        $comment->setAttribute( 'name', 'chen' );
        $result = ezcomComment::validateInput( $comment );
        $this->assertNotSame( 'Name is empty!', $result );
        
        //validate email
        $comment->setAttribute( 'email', '' );
        $result = ezcomComment::validateInput( $comment );
        $this->assertSame( 'Email is empty!', $result );
        
        $comment->setAttribute( 'email', 'xc2357fddf' );
        $result = ezcomComment::validateInput( $comment );
        $this->assertSame( 'Not a valid email address!', $result );
        
        $comment->setAttribute( 'email', 'xc@ez.no' );
        $result = ezcomComment::validateInput( $comment );
        $this->assertNotSame( 'Email is empty!', $result );
        $this->assertNotSame( 'Not a valid email address!', $result );
        
        // validate text
        $comment->setAttribute( 'text', '' );
        $result = ezcomComment::validateInput( $comment );
        $this->assertSame( 'Content is empty!', $result );
        
        $comment->setAttribute( 'text', 'test comment:)))' );
        $result = ezcomComment::validateInput( $comment );
        $this->assertNotEquals( 'Content is empty!', $result );
        
        // validate language_id
        $comment->setAttribute( 'language_id', '' );
        $result = ezcomComment::validateInput( $comment );
        $this->assertSame( 'Language is empty or not int!', $result );
        
        $comment->setAttribute( 'language_id', 'dd' );
        $result = ezcomComment::validateInput( $comment );
        $this->assertSame( 'Language is empty or not int!', $result );
        
        $comment->setAttribute( 'language_id', 2 );
        $result = ezcomComment::validateInput( $comment );
        $this->assertNotSame( 'Language is empty or not int!', $result );
        
        // validate contentobject_id
        $comment->setAttribute( 'contentobject_id', '' );
        $result = ezcomComment::validateInput( $comment );
        $this->assertSame( 'Object ID can not be empty or string!', $result );
        
        $comment->setAttribute( 'contentobject_id', 'ss' );
        $result = ezcomComment::validateInput( $comment );
        $this->assertSame( 'Object ID can not be empty or string!', $result );
        
        $comment->setAttribute( 'contentobject_id', 12 );
        $result = ezcomComment::validateInput( $comment );
        $this->assertNotSame( 'Object ID can not be empty or string!', $result );
        
        // validate all
        $this->assertTrue( $result );
    }
    
    /**
     * Test the addcomment method in ezcomComment
     */
    public function testAddComment()
    {
        //1. test adding a comment without notification
        $input = array();
        $input['name'] = 'xc';
        $input['email'] = 'xc@ez.no';
        $input['text'] = 'This is a test comment:)';
        $input['notified'] = false;
        $user = eZUser::currentUser();
        $contentObjectID = 209;
        $languageID = 2;
        $time = time();
        //1.1 without subscription
        $result = ezcomComment::addComment( $input, $user, $contentObjectID, $languageID, $time );
        $this->assertTrue( $result['result'] );
        $comment = ezcomComment::fetchByTime( 'created', $time );
        $this->assertEquals( $input['text'], $comment->attribute( 'text' ) );
        $this->assertEquals( $input['name'], $comment->attribute( 'name' ) );
        $this->assertEquals( $input['notified'], $comment->attribute( 'notified' ) );
        
        //1.2 with subscription
        //add subscriber
        $time = $time + 1;
        $subscriber = ezcomSubscriber::create();
        $subscriber->setAttribute( 'user_id', $user->attribute( 'contentobject_id' ) );
        $subscriber->setAttribute( 'email', $input['email'] );
        $subscriber->store();
        //add subscription
        $subscription = ezcomSubscription::create();
        $subscription->setAttribute( 'subscriber_id', $subscriber->attribute( 'id' ) );
        $subscription->setAttribute( 'content_id', $contentObjectID . '_' . $languageID );
        $subscription->setAttribute( 'user_id', $user->attribute( 'contentobject_id' ) );
        $subscription->setAttribute( 'subscription_type', 'ezcomcomment' );
        $subscription->setAttribute( 'subscription_time', $time );
        $subscription->store();
        //add comment
        $result = ezcomComment::addComment( $input, $user, $contentObjectID, $languageID, $time );
        $comment = ezcomComment::fetchByTime( 'created', $time );
        $this->assertEquals( $input['text'], $comment->attribute( 'text' ) );
        $this->assertEquals( $input['name'], $comment->attribute( 'name' ) );
        $this->assertEquals( $input['notified'], $comment->attribute( 'notified' ) );
        $this->assertTrue( $result['result'] );
        //vertify the notification
        $notifications = ezcomNotification::fetchNotificationList( 1, 1, 0, array( 'id' => 'desc' ) );
        $this->assertEquals( $contentObjectID, $notifications[0]->attribute( 'contentobject_id' ) );
        $this->assertEquals( $comment->attribute( 'id' ), $notifications[0]->attribute( 'comment_id' ) );
        $this->assertEquals( $languageID, $notifications[0]->attribute( 'language_id' ) );
        
        //2. test adding a comment with notification
        $input2 = array();
        $input2['name'] = 'chen';
        $input2['email'] = 'cxj2007@gmail.com';
        $input2['text'] = 'notified comment';
        $input2['notified'] = false;
        $user2 = eZUser::currentUser();
        $contentObjectID = 210;
        $languageID = 2;
        //2.1 if there is no subscription
        $time2 = time() + 3;
        $result2 = ezcomComment::addComment( $input2, $user2, $contentObjectID, $languageID, $time2 );
        $this->assertTrue( $result2['result'] );
        $comment = ezcomComment::fetchByTime( 'created', $time2 );
        $this->assertEquals( $input2['text'], $comment->attribute( 'text' ) );
        $this->assertEquals( $input2['name'], $comment->attribute( 'name' ) );
        $this->assertEquals( $input2['notified'], $comment->attribute( 'notified' ) );
        $notifications = ezcomNotification::fetchNotificationList( 1, 1, 0, array( 'id' => 'desc' ) );
        $this->assertNotEquals( $notifications[0]->attribute( 'comment_id' ), $comment->attribute( 'id' ) ); //assert that there is no new notification
        //2.2 if there is already subscription
        $time2 = $time2 + 1;
        //add subscriber
        $subscriber = ezcomSubscriber::create();
        $subscriber->setAttribute( 'user_id', $user2->attribute( 'contentobject_id' ) );
        $subscriber->setAttribute( 'email', $input['email'] );
        $subscriber->store();
        //add subscription
        $subscription = ezcomSubscription::create();
        $subscription->setAttribute( 'subscriber_id', $subscriber->attribute( 'id' ) );
        $subscription->setAttribute( 'content_id', $contentObjectID . '_' . $languageID );
        $subscription->setAttribute( 'user_id', $user2->attribute( 'contentobject_id' ) );
        $subscription->setAttribute( 'subscription_type', 'ezcomcomment' );
        $subscription->setAttribute( 'subscription_time', $time2 );
        $subscription->store();
        //add comment
        $result = ezcomComment::addComment( $input2, $user2, $contentObjectID, $languageID, $time2 );
        $comment = ezcomComment::fetchByTime( 'created', $time2 );
        $this->assertEquals( $input2['text'], $comment->attribute( 'text' ) );
        $this->assertEquals( $input2['name'], $comment->attribute( 'name' ) );
        $this->assertEquals( $input2['notified'], $comment->attribute( 'notified' ) );
        $this->assertTrue( $result['result'] );
        //vertify the notification
        $notifications = ezcomNotification::fetchNotificationList( 1, 1, 0, array( 'id' => 'desc' ) );
        $this->assertEquals( $contentObjectID, $notifications[0]->attribute( 'contentobject_id' ) );
        $this->assertEquals( $comment->attribute( 'id' ), $notifications[0]->attribute( 'comment_id' ) );
        $this->assertEquals( $languageID, $notifications[0]->attribute( 'language_id' ) );
    }
}
?>