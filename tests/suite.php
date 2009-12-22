<?php

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
    }

    public static function suite()
    {
        return new self();
    }
}

?>