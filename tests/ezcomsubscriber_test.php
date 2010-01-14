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