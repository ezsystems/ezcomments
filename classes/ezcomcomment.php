<?php
//
// Definition of ezcomComment class
//
// Created on: <06-Dec-2009 00:00:00 xc>
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
 * ezcomComment persistent object class definition
 * 
 */
class ezcomComment extends eZPersistentObject
{
    /**
     * Construct, use {@link ezcomComment::create()} to create new objects.
     * 
     * @param array $row
     */
    public function __construct( $row )
    {
        parent::__construct( $row );
    }

    /**
     * Fields definition.
     * 
     * @static
     * @return array
     */
    public static function definition()
    {
        static $def = array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                                'language_id' => array( 'name' => 'LanguageID',
                                                                        'datatype' => 'integer',
                                                                        'default' => 0,
                                                                        'required' => true ),
                                                'created' => array( 'name' => 'Created',
                                                                    'datatype' => 'integer',
                                                                    'default' => 0,
                                                                    'required' => true ),
                                                'modified' => array( 'name' => 'Modified',
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true ),
                                                'user_id' => array( 'name' => 'UserID',
                                                                    'datatype' => 'integer',
                                                                    'default' => 0,
                                                                    'required' => true ),
                                                'session_key' => array( 'name' => 'SessionKey',
                                                                        'datatype' => 'string',
                                                                        'default' => '',
                                                                        'required' => true ),
                                                'ip' => array( 'name' => 'IPAddress',
                                                               'datatype' => 'string',
                                                               'default' => '',
                                                               'required' => true ),
                                                'contentobject_id' => array( 'name' => 'ContentObjectID',
                                                                             'datatype' => 'integer',
                                                                             'default' => 0,
                                                                             'required' => true ),
                                                'contentobject_attribute_id' => array( 'name' => 'ContentObjectAttributeID',
                                                                                       'datatype' => 'integer',
                                                                                       'default' => 0,
                                                                                       'required' => true ),
                                                'parent_comment_id' => array( 'name' => 'ParentCommentID',
                                                                              'datatype' => 'integer',
                                                                              'default' => 0,
                                                                              'required' => true ),
                                                'name' => array( 'name' => 'Name',
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                                'email' => array( 'name' => 'EMail',
                                                                  'datatype' => 'string',
                                                                  'default' => '',
                                                                  'required' => true ),
                                                'url' => array( 'name' => 'URL',
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ),
                                                'text' => array( 'name' => 'Text',
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                                'status' => array( 'name' => 'Status',
                                                                   'datatype' => 'integer',
                                                                   'default' => 0,
                                                                   'required' => true ),
                                                'notification' => array( 'name' => 'Notification',
                                                                         'datatype' => 'integer',
                                                                         'default' => 0,
                                                                         'required' => true ),
                                                'title' => array( 'name' => 'Title',
                                                                  'datatype' => 'string',
                                                                  'default' => '',
                                                                  'required' => true ) ),
                             'keys' => array( 'id' ),
                             'function_attributes' => array( 
                                                            'contentobject' => 'contentObject' ),
                             'increment_key' => 'id',
                             'class_name' => 'ezcomComment',
                             'name' => 'ezcomment' );
        return $def;
    }

    /**
     * get the contentobject of one comment
     * @return unknown_type
     */
    public function contentObject()
    {
        if ( isset( $this->ContentObjectID ) and $this->ContentObjectID )
        {
            return eZContentObject::fetch( $this->ContentObjectID );
        }
        return null;
    }
    
    /**
     * Creates new ezcomComments object
     * 
     * @static
     * @param array $row
     * @return ezcomComment
     */
    public static function create( $row = array() )
    {
        $object = new self( $row );
        return $object;
    }

    /**
     * Fetch comment by given id.
     * 
     * @param int $id
     * @return null|ezcomComment
     */
    static function fetch( $id )
    {
        $cond = array( 'id' => $id );
        $return = eZPersistentObject::fetchObject( self::definition(), null, $cond );
        return $return;
    }
    
    static function fetchForUser( $userID, $sorts = null, $offset = null, $length = null, $notification = false, $status = false  )
    {
        $cond = array();
        $cond['user_id'] = $userID;
        if ( $notification !== false )
        {
            $cond['notification'] = $notification;
        }
        if( $status !== false )
        {
            $cond['status'] = $status;
        }
        $limit = null;
        if( !is_null( $offset ) )
        {
           $limit = array();
           $limit = array( 'offset' => $offset, 'length' => $length );
        }
        
        $return = eZPersistentObject::fetchObjectList( self::definition(), null, $cond, $sorts, $limit );
        return $return;
    }
    
    static function fetchByEmail( $email, $sorts = null, $offset = null, $length = null, $notification = false, $status = false  )
    {
        $cond = array();
        $cond['email'] = $email;
        if ( $notification !== false )
        {
            $cond['notification'] = $notification;
        }
        if( $status !== false )
        {
            $cond['status'] = $status;
        }
        $limit = null;
        if( !is_null( $offset ) )
        {
           $limit = array();
           $limit = array( 'offset' => $offset, 'length' => $length );
        }
        $return = eZPersistentObject::fetchObjectList( self::definition(), null, $cond, $sorts, $limit );
        return $return;
    }
    
    static function fetchByContentObjectID( $contentObjectID, $languageID, $sorts = null, $offset = null, $length = null )
    {
        $cond = array();
        $cond['contentobject_id'] = $contentObjectID;
        $cond['language_id'] = $languageID;
        if( is_null( $offset ) || is_null( $length ) )
        {
            return null;
        }
        else
        {
            $limit = array( 'offset' => $offset, 'length' => $length);
            $return = eZPersistentObject::fetchObjectList( self::definition(), null, $cond, $sorts, $limit );
            return $return;
        
        }
    }

    static function fetchByTime( $timefield, $time )
    {
        $cond = array();
        if( $timefield == 'created' )
        {
            $cond['created'] = $time;
        }
        else if( $timefield == 'modified' )
        {
            $cond['modified'] = $time;
        }
        else
        {
            return null;
        }
        $return = eZPersistentObject::fetchObject( self::definition(), null, $cond );
        return $return;
    }
    
    
    static function updateFields( $fields, $conditions )
    {
        $parameters = array();
        $parameters['definition'] = self::definition();
        $parameters['update_fields'] = $fields;
        $parameters['conditions'] = $conditions;
        //use try to catch the error
        eZPersistentObject::updateObjectList( $parameters );
    }
    
    /**
     * count the comments by content object id
     * @param int $contentObjectID
     * @return int : count number
     */
    static function countByContent( $contentObjectID, $languageID = false )
    {
        $cond = array();
        $cond['contentobject_id'] = $contentObjectID;
        if( $languageID !== false )
        {
            $cond['language_id'] = $languageID;
        }
//        $cond['status'] = 1;
        return eZPersistentObject::count( self::definition(), $cond );
    }
    
    
    /**
     * update one comment, and update the relavant subscription, notificaton queue
     * @param $commentInput: comment input array.
     *         'title': comment title
     *         'url': website of the commenter
     *         'text': comment content
     *         'notified' boolean: notified for this content.
     *               If there is change for notified, the notified has value, otherwise there is no 'notified' in array
     * @param $commentParam: id or object of updated comment
     * @param $user: the author user object
     * @param $time: modified time
     * @return boolean: true if succeed, false if failed
     */
    static function updateComment( $commentInput, $commentParam, $user, $time = null )
    {
        // todo: remove the notified field in comment, instead, use subscription 
        //1. get the comment, update it
        if( is_null( $commentInput ) || is_null( $commentParam ) || is_null( $user ) )
        {
            eZDebug::writeError( 'Parameter error in comment input!', 'ezcomments', 'ezcomComment' );
            return false;
        }
        $comment = null;
        if( gettype( $commentParam ) == 'object' )
        {
            if( get_class( $commentParam ) == 'ezcomComment' )
            {
                $comment = $commentParam;
            }
            else
            {
                eZDebug::writeError( 'Comment Param error.', 'ezcomment' );
                return false;
            }
        }
        else
        {
            if( is_null( $commentParam ) || !is_numeric( $commentParam ) )
            {
                eZDebug::writeError( 'Comment id is ilegal!', 'ezcomments', 'ezcomComment' );
                return false;
            }
            $comment = ezcomComment::fetch( $commentParam );
        }
        if( isset( $commentInput['title'] ) )
        {
            $comment->setAttribute( 'title', $commentInput['title'] );
        }
        if( isset( $commentInput['url'] ) )
        {
            $comment->setAttribute( 'url', $commentInput['url'] );
        }
        if( isset( $commentInput['text'] ) )
        {
            $comment->setAttribute( 'text', $commentInput['text'] );
        }
        if( is_null( $time ) )
        {
            $time = time();
        }
        $comment->setAttribute( 'modified', $time );
        if( isset( $commentInput['notified'] ) )
        {
            $comment->setAttribute( 'notification', $commentInput['notified'] );
        }
        $comment->store();
        
        //2. update subscription
        // if notified is true, add subscription, else cleanup the subscription on the user and content
        $contentID = $comment->attribute( 'contentobject_id' ) . '_' . $comment->attribute( 'language_id' );
        $subscriptionType = 'ezcomcomment';
        if( isset( $commentInput['notified'] ) )
        {
            if( $commentInput['notified'] === true )
            {
                self::addSubscription( $comment->attribute('email'), $user, $contentID,
                             $subscriptionType, $time );
            }
            else
            {
                ezcomSubscription::cleanupSubscription( $comment->attribute('email'), $contentID );
            }
        }
        //3. update queue. If there is subscription, add one record into queue table
        // if there is subcription on this content, add one item into queue
        if( ezcomSubscription::exists( $contentID, $subscriptionType ) )
        {
            $notification = ezcomNotification::create();
            $notification->setAttribute( 'contentobject_id', $comment->attribute( 'contentobject_id' ) );
            $notification->setAttribute( 'language_id', $comment->attribute( 'language_id' ) );
            $notification->setAttribute( 'comment_id', $comment->attribute( 'id' ) );
            $notification->store();
            eZDebug::writeNotice( 'There is subscription, added a update notification into queue.', 'ezcomments' );
        }
        else
        {
            // todo: if there is no subscription on this content, consider to clean up the queue
        }
        return true;
    }
    
    /**
     * delete comment 
     * @param string/int $commentID
     * @return true if succeed, false if failed.
     */
    public static function deleteComment( $commentID )
    {
        $cond = array();
        $cond['id'] = $commentID;
        $return = eZPersistentObject::remove();
    }
    
    /**
     * delete comment and clean up subscription related, notification queue 
     * @param string/int $commentID
     * @return true if succeed, false if failed
     */
    public static function deleteCommentWithSubscription( $commentID )
    {
        if( is_null( $commentID ) )
        {
            eZDebug::writeError( 'The comment id is empty!', 'Delete Comment', ezcomComment );
            return false;
        }
        $comment = ezcomComment::fetch( $commentID );
        $email = $comment->attribute( 'email' );
        $notification = $comment->attribute( 'notification' );
        // 1. remove comment
        $comment->remove();
        
        // 2. clean up subscription
        if( $notification )
        {
            eZDebug::writeNotice( 'The comment to be deleted has notification', 'Delete comment' );
            $contentID = $comment->attribute( 'contentobject_id' ) . '_'. $comment->attribute( 'language_id' );
            $cleanupResult = ezcomSubscription::cleanupSubscription( $email, $contentID );
            if( $cleanupResult === true )
            {
                eZDebug::writeNotice( 'The subscription has been cleaned up', 'Delete comment' );
            }
            else if( $cleanupResult === false )
            {
                eZDebug::writeNotice( 'There is no subscription to be cleaned up', 'Delete comment' );
            }
            else
            {
                eZDebug::writeWarning( 'Cleaning up subscription error', 'Delete comment' );
            }
        }
        //3. todo: clean up the queue
        return true;
    }
   
}

?>