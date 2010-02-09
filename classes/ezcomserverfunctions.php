<?php
/**
 * File containing ezcomServerFunctions class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

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