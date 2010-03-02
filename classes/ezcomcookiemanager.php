<?php
/**
 * File containing ezcomCookieManager class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

/**
 *
 * handle cookies in comment system
 *
 */
class ezcomCookieManager
{
    public $expiryTime = 0 ;
    public $nameName = 'ezcommentsName';
    public $websiteName = 'ezcommentsWebiste';
    public $emailName = 'ezcommentsEmail';
    public $notificationName = 'ezcommentsNotified';

    /**
     * construct
     */
    public function __construct()
    {
        $this->expiryTime = time() + 60 * 60 * 24 * 365;
    }

    /**
     * store data into cookie
     * if field is null, set cookie based on user data, other wise set cookie based on fields
     * @param $comment comment object
     * @return arrary stored data
     */
    public function storeCookie( $comment = null )
    {
        $userData = array();
        $sessionID = session_id();
        if( is_null( $comment ) )
        {
            $currentUser = eZUser::currentUser();
            if( $currentUser->isAnonymous() )
            {
                return '';
            }
            else
            {
                $userData[$sessionID] = array( 'email' => $currentUser->attribute( 'email' ),
                                               'name' => $currentUser->attribute( 'login' ) );
            }
        }
        else
        {
            $userData[$sessionID] = array( 'email' => $comment->attribute( 'email' ),
                                           'name' => $comment->attribute( 'name' ) );
        }
        setcookie( 'eZCommentsUserData', base64_encode( json_encode( $userData ) ), time()+3600, '/' );
        return $userData;
//       setcookie( $this->nameName, $comment->attribute( 'name' ), $this->expiryTime, '/' );
//       setcookie( $this->websiteName, $comment->attribute( 'url' ), $this->expiryTime, '/' );
//       setcookie( $this->emailName, $comment->attribute( 'email' ), $this->expiryTime, '/' );
       //TODO: check the notified for anonymous users
//       setcookie( $this->notificationName, $comment->attribute( 'notification' ), $this->expiryTime, '/' );
    }

    /**
     * clear all cookies
     * @return
     */
    public function clearCookie()
    {
        $deleteTime = time() - 3600;
        setcookie( $this->nameName, '', $deleteTime, '/' );
        setcookie( $this->websiteName, '', $deleteTime, '/' );
        setcookie( $this->emailName, '', $deleteTime, '/' );
        setcookie( $this->notificationName, '', $deleteTime, '/' );
    }


    /**
     * create instance
     * @return ezcomCookieMangaer
     */
    public static function instance()
    {
        return new ezcomCookieManager();
    }

}
?>