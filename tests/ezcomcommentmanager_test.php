<?php
/**
 * File containing ezcomCommentManagerTest class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

class ezcomCommentManagerTest extends ezpDatabaseTestCase
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
    
//    /**
//     * Test the validateInput method in ezcomComment
//     * to do: test the localized error
//     * @return 
//     */
//    public function testValidateInput()
//    {
//        $commentManager = ezcomCommentManager::instance();
//        $result = $commentManager->validateInput( null );
//        $this->assertSame( 'Parameter is empty!', $result );
//        $comment = ezcomComment::create();
//        $result = $commentManager->validateInput( $comment );
//        $this->assertNotSame( 'Parameter is empty!', $result );
//        
//        //validate name
//        $comment->setAttribute( 'name', '' );
//        $result = $commentManager->validateInput( $comment );
//        $this->assertSame( 'Name is empty!', $result );
//        
//        $comment->setAttribute( 'name', 'chen' );
//        $result = $commentManager->validateInput( $comment );
//        $this->assertNotSame( 'Name is empty!', $result );
//        
//        //validate email
//        $comment->setAttribute( 'email', '' );
//        $result = $commentManager->validateInput( $comment );
//        $this->assertSame( 'Email is empty!', $result );
//        
//        $comment->setAttribute( 'email', 'xc2357fddf' );
//        $result = $commentManager->validateInput( $comment );
//        $this->assertSame( 'Not a valid email address!', $result );
//        
//        $comment->setAttribute( 'email', 'xc@ez.no' );
//        $result = $commentManager->validateInput( $comment );
//        $this->assertNotSame( 'Email is empty!', $result );
//        $this->assertNotSame( 'Not a valid email address!', $result );
//        
//        // validate text
//        $comment->setAttribute( 'text', '' );
//        $result = $commentManager->validateInput( $comment );
//        $this->assertSame( 'Content is empty!', $result );
//        
//        $comment->setAttribute( 'text', 'test comment:)))' );
//        $result = $commentManager->validateInput( $comment );
//        $this->assertNotEquals( 'Content is empty!', $result );
//        
//        // validate language_id
//        $comment->setAttribute( 'language_id', '' );
//        $result = $commentManager->validateInput( $comment );
//        $this->assertSame( 'Language is empty or not int!', $result );
//        
//        $comment->setAttribute( 'language_id', 'dd' );
//        $result = $commentManager->validateInput( $comment );
//        $this->assertSame( 'Language is empty or not int!', $result );
//        
//        $comment->setAttribute( 'language_id', 2 );
//        $result = $commentManager->validateInput( $comment );
//        $this->assertNotSame( 'Language is empty or not int!', $result );
//        
//        // validate contentobject_id
//        $comment->setAttribute( 'contentobject_id', '' );
//        $result = $commentManager->validateInput( $comment );
//        $this->assertSame( 'Object ID can not be empty or string!', $result );
//        
//        $comment->setAttribute( 'contentobject_id', 'ss' );
//        $result = $commentManager->validateInput( $comment );
//        $this->assertSame( 'Object ID can not be empty or string!', $result );
//        
//        $comment->setAttribute( 'contentobject_id', 12 );
//        $result = $commentManager->validateInput( $comment );
//        $this->assertNotSame( 'Object ID can not be empty or string!', $result );
//        
//        // validate all
//        $this->assertTrue( $result );
//    }
    
///**
//     * Test the addcomment method in ezcomComment
//     */
//    public function testAddComment()
//    {
//        //1. test adding a comment without notification
//        $time = time();
//        $contentObjectID = 209;
//        $languageID = 2;
//        $comment = ezcomComment::create();
//        $comment->setAttribute( 'name', 'xc' );
//        $comment->setAttribute( 'email', 'xc@ez.no' );
//        $comment->setAttribute( 'text', 'This is a test comment:)' );
//        $comment->setAttribute( 'contentobject_id', $contentObjectID );
//        $comment->setAttribute( 'language_id', $languageID );
//        $comment->setAttribute( 'created', $time );
//        $comment->setAttribute( 'modified', $time );
//        $user = eZUser::currentUser();
//
//        $commentManager = ezcomCommentManager::instance();
//        //1.1 without subscription
//        $result = $commentManager->addComment( $comment, $user );
//        $this->assertSame( true, $result );
//        $commentResult = ezcomComment::fetchByTime( 'created', $time );
//        $this->assertEquals( $comment->attribute( 'text' ), $commentResult->attribute( 'text' ) );
//        $this->assertEquals( $comment->attribute( 'name' ), $commentResult->attribute( 'name' ) );
//
//        //1.2 with subscription
//        //add subscriber
//        $time = $time + 1;
//        $comment->setAttribute( 'created', $time );
//        $comment->setAttribute( 'modified', $time );
//        $subscriber = ezcomSubscriber::create();
//        $subscriber->setAttribute( 'user_id', $user->attribute( 'contentobject_id' ) );
//        $subscriber->setAttribute( 'email', $comment->attribute( 'email' ) );
//        $subscriber->store();
//        //add subscription
//        $subscription = ezcomSubscription::create();
//        $subscription->setAttribute( 'subscriber_id', $subscriber->attribute( 'id' ) );
//        $subscription->setAttribute( 'content_id', $contentObjectID . '_' . $languageID );
//        $subscription->setAttribute( 'user_id', $user->attribute( 'contentobject_id' ) );
//        $subscription->setAttribute( 'subscription_type', 'ezcomcomment' );
//        $subscription->setAttribute( 'subscription_time', $time );
//        $subscription->store();
//        //add comment
//        $result = $commentManager->addComment( $comment, $user );
//        $commentResult = ezcomComment::fetchByTime( 'created', $time );
//        $this->assertEquals( $comment->attribute( 'text' ), $commentResult->attribute( 'text' ) );
//        $this->assertEquals( $comment->attribute( 'name' ), $commentResult->attribute( 'name' ) );
//        $this->assertSame( true, $result );
//        //vertify the notification
//        $notifications = ezcomNotification::fetchNotificationList( 1, 1, 0, array( 'id' => 'desc' ) );
//        $this->assertEquals( $contentObjectID, $notifications[0]->attribute( 'contentobject_id' ) );
//        $this->assertEquals( $comment->attribute( 'id' ), $notifications[0]->attribute( 'comment_id' ) );
//        $this->assertEquals( $languageID, $notifications[0]->attribute( 'language_id' ) );
//
//        //2. test adding a comment with notification
//        $time2 = time() + 3;
//        $contentObjectID = 210;
//        $languageID = 2;
//        $comment2 = ezcomComment::create();
//        $comment2->setAttribute( 'name', 'chen' );
//        $comment2->setAttribute( 'email', 'cxj2007@gmail.com' );
//        $comment2->setAttribute( 'text', 'notified comment' );
//        $comment2->setAttribute( 'contentobject_id', $contentObjectID );
//        $comment2->setAttribute( 'language_id', $languageID );
//        $comment2->setAttribute( 'created', $time2 );
//        $comment2->setAttribute( 'modified', $time2 );
//
//        $user2 = eZUser::currentUser();
//        //2.1 if there is no subscription
//        $result2 = $commentManager->addComment( $comment2, $user2, $time2 );
//        $this->assertSame( true, $result2 );
//        $commentResult = ezcomComment::fetchByTime( 'created', $time2 );
//        $this->assertEquals( $comment2->attribute( 'text' ), $commentResult->attribute( 'text' ) );
//        $this->assertEquals( $comment2->attribute( 'name' ), $commentResult->attribute( 'name' ) );
//        $notifications = ezcomNotification::fetchNotificationList( 1, 1, 0, array( 'id' => 'desc' ) );
//        $this->assertNotEquals( $notifications[0]->attribute( 'comment_id' ), $commentResult->attribute( 'id' ) ); //assert that there is no new notification
//        //2.2 if there is already subscription
//        $time2 = $time2 + 1;
//        //add subscriber
//        $subscriber = ezcomSubscriber::create();
//        $subscriber->setAttribute( 'user_id', $user2->attribute( 'contentobject_id' ) );
//        $subscriber->setAttribute( 'email', $comment2->attribute( 'email' ) );
//        $subscriber->store();
//        //add subscription
//        $subscription = ezcomSubscription::create();
//        $subscription->setAttribute( 'subscriber_id', $subscriber->attribute( 'id' ) );
//        $subscription->setAttribute( 'content_id', $contentObjectID . '_' . $languageID );
//        $subscription->setAttribute( 'user_id', $user2->attribute( 'contentobject_id' ) );
//        $subscription->setAttribute( 'subscription_type', 'ezcomcomment' );
//        $subscription->setAttribute( 'subscription_time', $time2 );
//        $subscription->store();
//        //add comment
//        $comment2->setAttribute( 'created', $time2 );
//        $comment2->setAttribute( 'modified', $time2 );
//        $result = $commentManager->addComment( $comment2, $user2, $time2 );
//        $commentResult = ezcomComment::fetchByTime( 'created', $time2 );
//        $this->assertEquals( $comment2->attribute( 'text' ), $commentResult->attribute( 'text' ) );
//        $this->assertEquals( $comment2->attribute( 'name' ), $commentResult->attribute( 'name' ) );
//        $this->assertSame( true, $result );
//        //vertify the notification
//        $notifications = ezcomNotification::fetchNotificationList( 1, 1, 0, array( 'id' => 'desc' ) );
//        $this->assertEquals( $contentObjectID, $notifications[0]->attribute( 'contentobject_id' ) );
//        $this->assertEquals( $commentResult->attribute( 'id' ), $notifications[0]->attribute( 'comment_id' ) );
//        $this->assertEquals( $languageID, $notifications[0]->attribute( 'language_id' ) );
//    }
}
?>
