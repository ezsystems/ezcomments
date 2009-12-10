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
    public static function get_notification_comment_list( $offset = null, $length = null, $userID = false )
    {
        $ezcommentsINI = eZINI::instance( 'ezcomments.ini' );
        //1. check the permission
        
        
        //2. check user
        if ( $userID === false )
        {
            $userID = eZUser::currentUserID();
        }
            
        //3. check offset
        $defaultNumPerPage = $ezcommentsINI->variable( 'ezcommentsSettings', 'NumberPerPage' );
        if( $defaultNumPerPage == '-1' )
        {
            $offset = null;
            $length = null;
        }
        else
        {
            if ( is_null( $offset ) )
            {
                $offset = 0;
            }
            //4. check countPerPage
            if( is_null( $length ) )
            {
                $length = $defaultNumPerPage;
            }
        }
        //5. fetch comment
        $comments = ezcomComment::fetchForUser( $userID, null, null, null );
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
                $objectName = eZContentObject::fetch( $contentobject_id ) -> attribute( 'name' );
                $row['id'] = $comment->attribute( 'id' );
                $row['contentobject_id'] = $contentobject_id;
                $row['notification'] = $comment->attribute('notification');
                $row['text'] = $comment->attribute('text');
                $row['object_name'] = $objectName;
                $resultComments[] = $row;
            }
            $result['comments'] = $resultComments;
            $result['total_count'] = $totalCount;
            $result = json_encode( $result );

        }
        else
        {
            $result = null;
        }
//        if( $length == array() )
//        {
//            $result = 'is null';
//        }
//        else
//        {
//            $result = 'not null';
//        }
        return $result;
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

}