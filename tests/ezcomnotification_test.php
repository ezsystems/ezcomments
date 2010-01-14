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