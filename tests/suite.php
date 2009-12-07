<?php
class suite extends ezpTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZ Comment Test Suite" );
        $this->addTestSuite( "ezcomCommentTest" );
        $this->addTestSuite( "ezcomNotificationTest" );
    }
    public static function suite()
    {
        return new self();
    }
}
?>