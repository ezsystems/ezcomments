<?php
/**
 * File containing ezcomCommentManager class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

/**
 *
 *Business logic of comment
 *
 */
abstract class ezcomCommentManager
{
    /**
     * template
     * @var eZTemplate
     */
    public $tpl = null;

    protected static $instance;

    /**
     * @param $comment
     * @param $user
     * @return true if action succeeds
     */
    public function beforeAddingComment( $comment, $user, $notification )
    {
        return true;
    }

    /**
     * action after adding comment
     * @param $comment
     * @return true if action succeeds
     *        
     */
    public function afterAddingComment( $comment, $notification )
    {
        return true;
    }

    /**
     * action before updating comment
     * @param $comment
     * @param $notified
     * @return true if action succeeds
     *        
     */
    public function beforeUpdatingComment( $comment, $notified, $time )
    {
        return true;
    }

    /**
     * action after updating comment
     * @param $comment
     * @param $notified
     * @return true if action succeeds
     *        string if the action has error
     */
    public function afterUpdatingComment( $comment, $notified, $time )
    {
        return true;
    }

    /**
     * action after deleting comment
     * @param $comment
     * true if action succeeds
     *        string if the action has error
     */
    public function afterDeletingComment( $comment )
    {
        return true;
    }

    /**
     * Add comment into ezcomment table and do action
     * The adding doesn't validate the data in http
     * @param $comment: ezcomComment object which has not been stored
     *        title, name, url, email, created, modified, text, notification
     * @param $user: user object
     * @param $time: comment time
     * @return  true : if adding succeeds
     *          false otherwise
     *
     */
    public function addComment( $comment, $user, $time = null, $notification = null )
    {
        if ( $time === null )
        {
            $time = time();
        }

        $beforeAddingResult = $this->beforeAddingComment( $comment, $user, $notification );
        if (  $beforeAddingResult !== true )
        {
            return $beforeAddingResult;
        }

        $comment->store();

        eZDebugSetting::writeNotice( 'extension-ezcomments', 'Comment has been added', __METHOD__ );
        $this->afterAddingComment( $comment, $notification );
        return true;
    }

    /**
     * Update the comment
     * @param $comment comment to be updated
     * @param $notified change the notification
     * @param $time
     * @param $user user to change
     * @return
     */
    public function updateComment( $comment, $user=null, $time = null , $notified = null )
    {
        if ( $time === null )
        {
            $time = time();
        }

        $beforeUpdating = $this->beforeUpdatingComment( $comment, $notified, $time );
        if ( $beforeUpdating !== true )
        {
            return $beforeUpdating;
        }
        $comment->store();

        $afterUpdating = $this->afterUpdatingComment( $comment, $notified, $time );
        if ( $afterUpdating !== true )
        {
            return $afterUpdating;
        }
        return true;
    }

    /**
     * delete comment. Based on the settings, judge if deleting the subscription if all the comments have been deleted.
     * @param $commentID
     * @return
     */
    public function deleteComment( $comment )
    {
        $comment->remove();

        $result = $this->afterDeletingComment( $comment );
        return $result;
    }

    /**
     * create an instance of ezcomCommentManager
     * @return ezcomCommentManager
     */
    public static function instance()
    {
        if ( !isset( self::$instance ) )
        {
            $ini = eZINI::instance( 'ezcomments.ini' );
            $className = $ini->variable( 'ManagerClasses', 'CommentManagerClass' );
            self::$instance = new $className();
        }
        return self::$instance;
    }

}
?>