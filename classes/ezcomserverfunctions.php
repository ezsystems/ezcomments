<?php
//
// Definition of ezsrServerFunctions class
//
// Created on: <06-Dec-2009 00:00:00 xc>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Star Rating
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

/*
 * ezjscServerFunctions for ezstarrating (rating related)
 */

class ezcomServerFunctions extends ezjscServerFunctions
{
    /**
     * Get the comment list in view 'notification'.
     * Return format:
     * ===========================================
     * comments:
     * -------------------------------------------
     * id, contentobject_id, notification, comment text, object name
     * 5,   12,               1, This is a comment, ezpublish 4.3 released!
     * -------------------------------------------
     * total_count: 125
     * ===========================================
     * @return JSON object
     * 
     */
    public static function get_notification_comment_list( $args )
    {
        $http = eZHTTPTool::instance();
        $offset = null;
        $length = null;
        $userID = null;
        $argObject = array();
        
        $ezcommentsINI = eZINI::instance( 'ezcomments.ini' );
        //1. check the permission
        
        //2. check user
        
        if( $http->hasPostVariable( 'args' ) )
        {
            $args = $http->postVariable( 'args' );
            $argObject = json_decode($args);
        }
        if ( isset( $argObject->user_id ) )
        {
            $userID = $argObject->user_id;
        }
        else
        {
            $userID = eZUser::currentUserID();
        }
            
        //3. check offset
        $defaultNumPerPage = $ezcommentsINI->variable( 'notificationSettings', 'NumberPerPage' );
        if( $defaultNumPerPage != '-1' )
        {
            if ( isset( $argObject->offset ) )
            {
                $offset = $argObject->offset;
            }
            else
            {
                $offset = 0;
            }
            //4. check countPerPage
            if ( isset( $argObject->length ) )
            {
                $length = $argObject->length;
            }
            else
            {
                $length = $defaultNumPerPage;
            }
        }
        
        //5. fetch comment
        $comments = ezcomComment::fetchForUser( $userID, null, $offset, $length );
        $db = eZDB::instance();
        $countArray = $db->arrayQuery( 'select count(*) as count from ezcomment where user_id ='.$userID );
        $totalCount = $countArray[0]['count'];
        
        //6. build JSON object and return
        $result = array();
        if( !is_null( $comments ) && is_array( $comments ) )
        {
            $resultComments = array();
            foreach( $comments as $comment )
            {
                $row = array();
                $contentobject_id = $comment->attribute( 'contentobject_id' );
                $contentObject = eZContentObject::fetch( $contentobject_id );
                $objectName =  $contentObject -> attribute( 'name' );
                $row['id'] = $comment->attribute( 'id' );
                $row['contentobject_id'] = $contentobject_id;
                $row['content_url'] = $contentObject->mainNode()->attribute( 'url_alias' );
                $row['notification'] = $comment->attribute('notification');
                $row['text'] = $comment->attribute('text');
                $row['object_name'] = $objectName;
                $row['time'] = $comment->attribute('created');
                $resultComments[] = $row;
            }
            $result['comments'] = $resultComments;
            $result['total_count'] = $totalCount;
//            $result = $offset;
            $result = json_encode( $result );

        }
        else
        {
            $result = null;
        }
        return $result;
    }
    
    /**
     * 
     * @param $args: get args
     * @return string: update result message
     */
    public static function update_notification_comment( $args )
    {
        $http = eZHTTPTool::instance();
        
        //1. check the permission
        
        //2. get parameters
        $argObject = null;
        if( $http->hasPostVariable( 'args' ) )
        {
            $argsString = $http->postVariable( 'args' );
            $argObject = json_decode( $argsString, true ); 
        }
        $message = null;
        
        //3. buid update parameters and execute update
        $fields = array();
        $conditions = array();
        $updateResult = true;
        $message = "";
        foreach( $argObject as $row )
        {
            $fields['notification'] = $row['notification'];
            $conditions['id'] = $row['id'];
            ezcomComment::updateFields( $fields, $conditions );
            //to do: add error handle 
        }
        
        //4. return result
        if ( $updateResult )
        {
            $message = "Update success";
        }
        else
        {
            $message = "Update error";
        }
        return $message;
    }
    
    /**
     * update the notification setting.
     * 
     * Format of $recNotifications
     * =============================
     * id, notification
     * 5, 0
     * 6, 1
     * 8, 0
     * =============================
     * 
     * @param JSON object $recNotifications
     * @return boolean succeed/failed
     */
    public static function set_notification_setting( $recNotifications )
    {
        
    }
    
    /**
     * Get the default settings in ini file.
     * @return unknown_type
     */
    public static function get_default_settings()
    {
        
    }
    
    public static function get_view_comment_list()
    {
        $http = eZHTTPTool::instance();
        $offset = null;
        $length = null;
        $contentobject_id = null;
        $argObject = array();
        
        $ezcommentsINI = eZINI::instance( 'ezcomments.ini' );
        //1. check the permission
        
        //2. check user
        
        if( $http->hasPostVariable( 'args' ) )
        {
            $args = $http->postVariable( 'args' );
            $argObject = json_decode($args);
        }
//        if ( isset( $argObject->user_id ) )
//        {
//            $userID = $argObject->user_id;
//        }
//        else
//        {
//            $userID = eZUser::currentUserID();
//        }
            
        //3. check offset
        $defaultNumPerPage = $ezcommentsINI->variable( 'commentSettings', 'NumberPerPage' );
        if( $defaultNumPerPage != '-1' )
        {
            if ( isset( $argObject->offset ) )
            {
                $offset = $argObject->offset;
            }
            else
            {
                $offset = 0;
            }
            //4. check countPerPage
            if ( isset( $argObject->length ) )
            {
                $length = $argObject->length;
            }
            else
            {
                $length = $defaultNumPerPage;
            }
        }
        if( !isset($argObject->oid) )
        {
            return null;
        }
        else if(!is_int($argObject->oid))
        {
            return null;
        }
        else
        {
            $contentobjectID = $argObject->oid;
            $sorts = array( 'modified' => 'desc' );
            $comments = ezcomComment::fetchByContetentObjectID( $contentobjectID, $sorts, $offset, $length);
            $db = eZDB::instance();
            $countArray = $db->arrayQuery( 'select count(*) as count from ezcomment where contentobject_id ='.$contentobjectID );
            $totalCount = $countArray[0]['count'];
            
            $result = array();
            if( $comments == null )
            {
                return null;
            }
            else
            {
                $resultComments = array();
                foreach ( $comments as $comment )
                {
                    $row = array();
                    $row['id'] = $comment->attribute( 'id' );
                    $row['oid'] = $comment->attribute( 'contentobject_id' );
                    $row['modified'] = $comment->attribute( 'modified' );
                    $row['created'] = $comment->attribute( 'created' );
                    $row['title'] = $comment->attribute( 'title' );
                    $row['text'] = $comment->attribute( 'text' );
                    $row['author'] = "111";
                    $row['userid'] = $comment->attribute( 'user_id' );
                    $resultComments[] = $row;
                }
                $result['comments'] = $resultComments;
                $result['total_count'] = $totalCount;
                return json_encode($result);
            }
        }
    }

}