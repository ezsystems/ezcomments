<?php
//
// Created on: <09-Dec-2009 00:00:00 xc>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Comments extension for eZ Publish
// SOFTWARE RELEASE: 1.0-1
// COPYRIGHT NOTICE: Copyright (C) 2009 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
// 
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
// 
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
// 
// 
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

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