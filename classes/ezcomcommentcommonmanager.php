<?php
//
// Definition of ezcomCommentCommonManager class
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

class ezcomCommentCommonManager extends ezcomCommentManager
{ 
    public function beforeAddingComment( $comment, $user )
    {
        return true;
    }
    
    /**
     * common implementation of validteInput method
     * valudate that if the name and text(content) is empty
     * @see extension/ezcomments/classes/ezcomCommentManager#validateInput($comment)
     */
    public function validateInput( $comment )
    {
        if( is_null( $comment ) )
        {
            return ezi18n( 'comment/view/validateInput', 'Parameter is empty!' );
        }
        if( $comment->attribute( 'name' ) == '' )
        {
            return ezi18n( 'comment/view/validateInput', 'Name is empty!' );
        }
        if( $comment->attribute( 'email' ) == '' )
        {
            return ezi18n( 'comment/view/validateInput', 'Email is empty!' );
        }
        else
        {   
            if( eZMail::validate( $comment->attribute( 'email' ) ) == false )
            {
                return ezi18n( 'comment/view/validateInput', 'Not a valid email address!' );
            }
        }
        if( $comment->attribute( 'text' ) == '' )
        {
            return ezi18n( 'comment/view/validateInput', 'Content is empty!' );
        }
        if ( $comment->attribute( 'language_id' ) == '' || !is_numeric( $comment->attribute( 'language_id' ) ) )
        {
            return ezi18n( 'comment/view/validateInput', 'Language is empty or not int!' );
        }
        if ( $comment->attribute( 'contentobject_id' ) == '' || !is_numeric( $comment->attribute( 'contentobject_id' ) ) )
        {
            return ezi18n( 'comment/view/validateInput', 'Object ID can not be empty or string!' );
        }
        return true;
    }
}
?>