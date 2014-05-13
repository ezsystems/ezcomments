<?php
/**
 * File containing ezcomCommentsTestSuite class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 */
class ezcomCommentsTestSuite extends ezpDatabaseTestSuite
{
    public function __construct()
    {
        // init extension setting to make unit test possible in ezp source code (not installed environment).
        $ini = eZINI::instance( 'ezcomments.ini' );
        $var = array( 'ManagerClasses'=> array( 'CommentManagerClass' => 'ezcomCommentCommonManager',
                                                'SubscriptionManagerClass' => 'ezcomSubscriptionManager',
                                                'PermissionClass' => 'ezcomPermission',
                                                'AddCommentToolClass' => 'ezcomAddCommentTool',
                                                'EditCommentToolClass' => 'ezcomEditCommentTool' ) );
        $ini->setVariables( $var );

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