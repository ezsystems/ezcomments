<?php
//
// Definition of ezcomCommentManager class
//
// Created on: <20-Jan-2009 12:00:00 xc>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Comments extension for eZ Publish
// SOFTWARE RELEASE: 1.0-0
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
     *        string if the action has error
     */
    public function beforeAddingComment( $comment, $user )
    {
        return true;
    }

    /**
     * action after adding comment
     * @param $comment
     * @return true if action succeeds
     *        string if the action has error
     */
    public function afterAddingComment( $comment )
    {
        return true;
    }

    /**
     * action before updating comment
     * @param $comment
     * @param $notified
     * @return true if action succeeds
     *        string if the action has error
     */
    public function beforeUpdatingComment( $comment, $notified )
    {
        return true;
    }

    /**
     * action after updating comment
     * @param $comment
     * @param $notified
     * @return true if action succeeds
     *        string if the action has error
    public function afterUpdatingComment( $comment, $notified )
    {
        return true;
    }

    /**
     * action after deleting comment
     * @param $comment
     * true if action succeeds
     *        string if the action has error
    public function afterDeletingComment( $comment )
    {
        return true;
    }

    /**
     * @param $comment: a comment object
     * @return true if the validation succeeds
     *         error message if the validation fails.
     */
    public abstract function validateInput( $comment );


    /**
     * Add comment into ezcomment table and do action
     * The adding doesn't validate the data in http
     * @param $comment: ezcomComment object which has not been stored
     *        title, name, url, email, created, modified, text, notification
     * @param $user: user object
     * @param $time: comment time
     * @return  true : if adding succeeds
     *          false otherwise
     *          string: error message
     *
     */
    public function addComment( $comment, $user, $time = null )
    {
        // $validationResult = $this->validateInput( $comment );
        // if( $validationResult !== true )
        // {
        //     return $validationResult;
        // }
        $currentTime = null;
        if( is_null( $time ) )
        {
            $currentTime = time();
        }
        else
        {
            $currentTime = $time;
        }
        $beforeAddingResult = $this->beforeAddingComment( $comment, $user );
        if(  $beforeAddingResult !== true )
        {
            return $beforeAddingResult;
        }
        $comment->store();
        eZDebugSetting::writeNotice( 'extension-ezcomments', 'Comment has been added', __METHOD__ );
        $this->afterAddingComment( $comment );
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
        $validationResult = $this->validateInput( $comment );
        if( $validationResult !== true )
        {
            return $validationResult;
        }
        $currentTime = null;
        if( is_null( $time ) )
        {
            $currentTime = time();
        }
        else
        {
            $currentTime = $time;
        }
        $beforeUpdating = $this->beforeUpdatingComment( $comment, $notified );
        if( $beforeUpdating !== true )
        {
            return $beforeUpdating;
        }
        $comment->store();

        $afterUpdating = $this->afterUpdatingComment( $comment, $notified );
        if( $afterUpdating !== true )
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
        if( !isset( self::$instance ) )
        {
            $ini = eZINI::instance( 'ezcomments.ini' );
            $className = $ini->variable( 'ManagerClasses', 'CommentManagerClass' );
            self::$instance = new $className();
        }
        return self::$instance;
    }

}
?>