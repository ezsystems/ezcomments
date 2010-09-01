<?php
/**
 * File containing ezcomCommentsTestSuite class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */
class ezcomCommentsTestSuite extends ezpDatabaseTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZ Comment Test Suite" );
        $this->addTestSuite( "ezcomCommentTest" );
        $this->addTestSuite( "ezcomNotificationTest" );
        $this->addTestSuite( "ezcomSubscriberTest" );
        $this->addTestSuite( "ezcomSubscriptionTest" );
        $this->addTestSuite( "ezcomCommentManagerTest" );
        $this->addTestSuite( "ezcomSubscriptionManagerTest" );
        $this->addTestSuite( "ezcomNotificationManagerTest" );
        $this->addTestSuite( "ezcomUtilityTest" );
    }

    public static function suite()
    {
        return new self();
    }
}

?>