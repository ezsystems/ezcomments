<?php
/**
 * File containing ezcomCommentTest class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
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
     * Test FetchByContentObjectList
     */
    public function testFetchByContentObjectIDList()
    {
        $language = eZContentLanguage::fetchByLocale( 'eng-GB' );
        $languageID = $language->attribute( 'id' );
        $time = time();
        $comment1 = array( 'contentobject_id' => 15,
                           'language_id' => $languageID,
                           'created' => $time,
                           'modified' => $time,
                           'text'=>'comment1',
                           'user_id' => 15 );
        $comment2 = array( 'contentobject_id' => 15,
                           'language_id' => $languageID,
                           'created' => $time + 1,
                           'modified' => $time + 1,
                           'text'=>'comment2',
                           'user_id' => 15 );
        $comment3 = array( 'contentobject_id' => 15,
                           'language_id' => $languageID,
                           'created' => $time + 2,
                           'modified' => $time + 2,
                           'text'=>'comment3',
                           'user_id' => 15 );
        $comment4 = array( 'contentobject_id' => 14,
                           'language_id' => $languageID,
                           'created' => $time + 3,
                           'modified' => $time + 3,
                           'text'=>'comment4',
                           'user_id' => 14 );
        $comment = ezcomComment::create( $comment1 );
        $comment->store();
        $comment = ezcomComment::create( $comment2 );
        $comment->store();
        $comment = ezcomComment::create( $comment3 );
        $comment->store();
        $comment = ezcomComment::create( $comment4 );
        $comment->store();
        // test null contentobject id and user id
        $result = ezcomComment::fetchByContentObjectIDList( null, 15, 'eng-GB', null, array( 'modified'=>'desc' ), 0 );
        $this->assertEquals( 'comment3', $result[0] -> attribute('text') );
        $this->assertEquals( 'comment1', $result[2] -> attribute('text') );

        // test null contentobject id array and empty user_id
        $result = ezcomComment::fetchByContentObjectIDList( null, null, 'eng-GB', null, array( 'modified'=>'desc' ), 0 );
        $this->assertEquals( 'comment1', $result[3] -> attribute('text') );

        // test one contentobject id array
        $result = ezcomComment::fetchByContentObjectIDList( array( 14 ), null, 'eng-GB', null, array( 'modified'=>'desc' ), 0 );
        $this->assertEquals( 'comment4', $result[0] -> attribute('text') );

        // test many contentobjects array and sort
        $result = ezcomComment::fetchByContentObjectIDList( array( 14, 15 ), null, 'eng-GB', null, array( 'modified'=>'asc' ), 0 );
        $this->assertEquals( 'comment3', $result[2] -> attribute('text') );

        // test length with all null
        $result = ezcomComment::fetchByContentObjectIDList( null, null, null, null, null, null, 3 );
        $this->assertEquals( 3, count( $result ) );
    }

//    /**
//     * 1. store an ezcomcomment object into database
//     * 2. Fetch the ezcomComment object list
//     * 3. assert fetchForUser( userid )
//     * 4. Assert fetchForUser( userid, notification )
//     * 5. Assert fetchForUser( userid,notification, status )
//     * 6. Assert fetchForUser( userid ) when there is no record in database
//     */
//    public function testFetchForUser()
//    {
//        // Create a new comment
//        $comment = ezcomComment::create();
//        $comment->setAttribute( 'contentobject_id', 13 );
//        $comment->setAttribute( 'language_id', 2 );
//        $comment->setAttribute( 'created', 21213423 );
//        $comment->setAttribute( 'modified', 21321231 );
//        $comment->setAttribute( 'user_id', 15 );
//        $comment->setAttribute( 'session_key', 'a2e4822a98337283e39f7b60acf85ec9' );
//        $comment->setAttribute( 'ip', '10.0.2.122' );
//        $comment->setAttribute( 'name', 'xc' );
//        $comment->setAttribute( 'email', 'xc@ez.no' );
//        $comment->setAttribute( 'url', 'http://ez.no' );
//        $comment->setAttribute( 'status', 1 );
//        $comment->setAttribute( 'text', 'ezcomComment object test comment.' );
//        $comment->store();
//
//        // Assert fetchForUser( userid )
//        $list = ezcomComment::fetchForUser( 15 );
//        $this->assertType( 'array', $list );
//        $this->assertType( 'ezcomComment', $list[0] );
//        $this->assertEquals( 13, $list[0]->attribute( 'contentobject_id' ) );
//
//        // Assert fetchForUser( userid, notification )
//        $list = ezcomComment::fetchForUser( 15, 0 );
//        $this->assertType( 'array', $list );
//        $this->assertType( 'ezcomComment', $list[0] );
//        $this->assertEquals( 13, $list[0]->attribute( 'contentobject_id' ) );
//
//        // Assert fetchForUser( userid,notification, status )
//        $list = ezcomComment::fetchForUser( 15, null, null, null, false, 1 );
//        $this->assertType( 'array', $list );
//        $this->assertType( 'ezcomComment', $list[0] );
//        $this->assertEquals( 13, $list[0]->attribute( 'contentobject_id' ) );
//
//        // Assert fetchForUser( userid ) when there is no record in database
//        $list = ezcomComment::fetchForUser( 16 );
//        $this->assertType( 'array', $list );
//        $this->assertSame( 0, count( $list ) );
//    }
    
    
    
    
//    /**
//     * Test updateComment method
//     * Test cases:
//     *      1) update title, website, text
//     *         the title, name, website, text will be updated
//     *      2) update notified to be true
//     *         if there is subscription for the user and content, nothing changed
//     *         if there is no subscription for the user and content, add one subscription
//     *         there will be a new notification in notification queue.
//     *      3) update notified to be false
//     *         if there is no notified for the user and content, delete subscription
//     *         if there is still notified for the user and content, keep the subscription
//     */
//    public function testUpdateComment()
//    {
//        //add comment&subscription
//        $input = array();
//        $input['name'] = 'xc';
//        $input['email'] = 'xcccccc@ez.no';
//        $input['text'] = 'This is a test comment for updating:)';
//        $input['notified'] = false;
//        $user = eZUser::currentUser();
//        $contentObjectID = 219;
//        $languageID = 3;
//        $time = time() + 9;
//        $result = ezcomComment::addComment( $input, $user, $contentObjectID, $languageID, $time );
//        $comment = ezcomComment::fetchByTime( 'created', $time );
//
//        // 1. update title, name, website, text
//        $commentInput = array();
//        $commentInput['title'] = 'title changed:)';
//        $commentInput['text'] = 'text \' changed?11';
//        $commentInput['url'] = 'http://dfsfsdf.com';
//        $updateResult = ezcomComment::updateComment( $commentInput, $comment->attribute( 'id' ), $user );
//        $this->assertTrue( $updateResult );
//        $updatedComment = ezcomComment::fetchByTime( 'created', $time );
//        $this->assertEquals( $commentInput['title'], $updatedComment->attribute( 'title' ) );
//        $this->assertEquals( $commentInput['text'], $updatedComment->attribute( 'text' ) );
//        $this->assertEquals( $commentInput['url'], $updatedComment->attribute( 'url' ) );
//        $this->assertFalse( ezcomComment::updateComment( null, 1, null ) );
//
//        $subscriber = ezcomSubscriber::fetchByEmail( $input['email'] );
//        $this->assertNull( $subscriber );
//
//        $hasSubscription = ezcomSubscription::exists( $contentObjectID . '_' . $languageID,
//                                            'ezcomcomment', $input['email'] );
//        $this->assertFalse( $hasSubscription );
//
//        // 2. update notified true
//        $commentInput =array();
//        $commentInput['notified'] = true;
//        $updateResult = ezcomComment::updateComment( $commentInput, $comment->attribute( 'id' ), $user );
//        $this->assertTrue( $updateResult );
//        $subscriber = ezcomSubscriber::fetchByEmail( $input['email'] );
//        $this->assertNotNull( $subscriber );
//        $hasSubscription = ezcomSubscription::exists( $contentObjectID . '_' . $languageID,
//                                            'ezcomcomment', $input['email'] );
//        $this->assertTrue( $hasSubscription );
//
//        //3. update notfied false, the subscriber will be kept, the subscription will be deleted
//        $commentInput['notified'] = false;
//        $updateResult = ezcomComment::updateComment( $commentInput, $comment->attribute( 'id' ), $user );
//        $subscriber = ezcomSubscriber::fetchByEmail( $input['email'] );
//        $this->assertNotNull( $subscriber );
//        $hasSubscription = ezcomSubscription::exists( $contentObjectID . '_' . $languageID,
//                                            'ezcomcomment', $input['email'] );
//        $this->assertFalse( $hasSubscription );
//    }
    
    // todo: finished the test case
    public function testAddSubscription()
    {
        
    }
    
//    public function testDeleteCommentWithSubscription()
//    {
//        // 1.1 create comment and subscription
//        $input = array();
//        $input['name'] = 'xc';
//        $input['email'] = 'ccccc@ez.no';
//        $input['text'] = 'This is a test comment for deleting!';
//        $input['notified'] = true;
//        $user = eZUser::currentUser();
//        $time = time() + 10;
//        $contentObjectID = 222;
//        $languageID = 3;
//        ezcomComment::addComment( $input, $user, $contentObjectID, $languageID, $time );
//        $comment = ezcomComment::fetchByTime( 'created', $time );
//        $commentID = $comment->attribute( 'id' );
//        // 1.2 delete the comment
//        $this->assertTrue( ezcomSubscription::exists( $contentObjectID . '_' . $languageID, 'ezcomcomment', $input['email'] ) );
//        ezcomComment::deleteCommentWithSubscription( $commentID );
//        $this->assertNull( ezcomComment::fetch( $commentID ) );
//        $this->assertFalse( ezcomSubscription::exists( $contentObjectID . '_' . $languageID, 'ezcomcomment', $input['email'] ) );
//        // 2.1 create comment without subscription
//        $input['notified'] = false;
//        ezcomComment::addComment( $input, $user, $contentObjectID, $languageID, $time );
//        $comment = ezcomComment::fetchByTime( 'created', $time );
//        $commentID = $comment->attribute( 'id' );
//        // 2.2 delete the comment
//        $this->assertFalse( ezcomSubscription::exists( $contentObjectID . '_' . $languageID, 'ezcomcomment', $input['email'] ) );
//        ezcomComment::deleteCommentWithSubscription( $commentID );
//        $this->assertNull( ezcomComment::fetch( $commentID ) );
//    }
}
?>