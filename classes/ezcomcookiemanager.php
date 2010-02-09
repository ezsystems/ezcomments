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
     * @param $comment
     * @return unknown_type
     */
    public function storeCookie( $comment )
    {
       setcookie( $this->nameName, $comment->attribute( 'name' ), $this->expiryTime, '/' );
       setcookie( $this->websiteName, $comment->attribute( 'url' ), $this->expiryTime, '/' );
       setcookie( $this->emailName, $comment->attribute( 'email' ), $this->expiryTime, '/' );
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
     * fetchCookie for template use
     * @return
     */
    public function fetchCookie()
    {
        $result = array();
        if ( array_key_exists( $this->nameName, $_COOKIE ) )
        {
            $result['name'] = $_COOKIE[$this->nameName];
        }
        if ( array_key_exists( $this->websiteName, $_COOKIE ) )
        {
            $result['website'] = $_COOKIE[$this->websiteName];
        }
        if ( array_key_exists( $this->emailName, $_COOKIE ) )
        {
            $result['email'] = $_COOKIE[$this->emailName];
        }
        if ( array_key_exists( $this->notificationName, $_COOKIE ) )
        {
            $result['notified'] = $_COOKIE[$this->notificationName];
        }
        return array( 'result' => $result );
    }

    /**
     * create instance
     * @return ezcomCookieMangaer
     */
    public static function instance()
    {
        return new ezcomCookieManager();
    }

    /**
     * function implementation for fetch( 'comment', 'comment_cookie' ) in template
     * @return array
     */
    public static function fetch()
    {
        $cookieManager = self::instance();
        return $cookieManager->fetchCookie();

    }
}
?>