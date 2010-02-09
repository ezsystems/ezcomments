<?php
/**
 * File containing ezcomSubscripotionManagerTest class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

class ezcomSubscriptionManagerTest extends ezpDatabaseTestCase
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
        $this->setName( "ezcomSubscriptionManager object test" );
    }
    
    public function setUp()
    {
        parent::setUp();
        ezpTestDatabaseHelper::insertSqlData( $this->sharedFixture, $this->sqlFiles );
    }
    
    //note: this method will send email to the mail account
     public function testSendActivationEmail()
     {
         $subscriber = ezcomSubscriber::create();
         $subscriber->setAttribute( 'email', 'xc@ez.no' );
         $subscriber->setAttribute( 'user_id', 10 );
         $subscriber->store();
         
         $contentObject = new ezpObject( 'article' );
         $contentObject->publish();
         
         $subscription = ezcomSubscription::create();
         $subscription->setAttribute( 'subscriber_id', $subscriber->attribute( 'id' ) );
         $subscription->setAttribute( 'subscriber_type', 'ezcomcomment' );
         $subscription->setAttribute( 'enabled', 0 );
         $subscription->setAttribute( 'content_id', '10_2' );
         $hashString = ezcomUtility::instance()->generateSubscriptionHashString( $subscription );
         $subscription->setAttribute( 'hash_string', $hashString );
         $subscription->store();
         
//         $result = ezcomSubscriptionManager::sendActivationEmail( $contentObject, $subscriber, $subscription );
//         $this->assertTrue( $result );
     }
     
     public function testActivateSubscription()
     {
         $subscriber = ezcomSubscriber::create();
         $subscriber->setAttribute( 'email', 'xccccc@ez.no' );
         $subscriber->setAttribute( 'user_id', 10 );
         $subscriber->store();
         
         $subscription = ezcomSubscription::create();
         $subscription->setAttribute( 'subscriber_id', $subscriber->attribute( 'id' ) );
         $subscription->setAttribute( 'subscriber_type', 'ezcomcomment' );
         $subscription->setAttribute( 'enabled', 0 );
         $subscription->setAttribute( 'content_id', '10_2' );
         $hashString = ezcomUtility::instance()->generateSubscriptionHashString( $subscription );
         $subscription->setAttribute( 'hash_string', $hashString );
         $subscription->store();
         $id = $subscription->attribute( 'id' );
         require_once( 'kernel/common/template.php' );
         $tpl = templateInit();
         $subscriptionManager = ezcomSubscriptionManager::instance( $tpl, null, null );
         $subscriptionManager->activateSubscription( $hashString );
         $subscriptionActivated = ezcomSubscription::fetch( $id );
         $this->assertEquals( 1, $subscriptionActivated->attribute( 'enabled' ) );
     }
     
}
?>