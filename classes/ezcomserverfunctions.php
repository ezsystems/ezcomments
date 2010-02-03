<?php
//
// Definition of ezcomServerFunctions class
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

/*
 * ezjscServerFunctions for ezcomments
 */

class ezcomServerFunctions extends ezjscServerFunctions
{
    /**
     *
     */
    public static function postComment()
    {
    }

    public static function userData()
    {
        unset( $_COOKIE['eZCommentsUserData'] );

        $sessionID = session_id();
        $userData = array();

        $currentUser = eZUser::currentUser();

        $userData[$sessionID] = array( 'email' => $currentUser->attribute( 'email' ),
                                       'name' => $currentUser->attribute( 'login' ) );

        setcookie( 'eZCommentsUserData', base64_encode( json_encode( $userData ) ), time()+3600, '/' );

        return $userData;
    }
}