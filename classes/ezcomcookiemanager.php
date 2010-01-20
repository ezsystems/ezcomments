<?php
//
// Definition of ezcomCookieManager class
//
// Created on: <20-Jan-2009 16:48:00 xc>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Comments extension for eZ Publish
// SOFTWARE RELEASE: 1.0-0
// COPYRIGHT NOTICE: Copyright (C) 2010 eZ Systems AS
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
       setcookie( $this->notificationName, $comment->attribute( 'notification' ), $this->expiryTime, '/' );
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
        if( array_key_exists( $this->nameName, $_COOKIE ) )
        {
            $result['name'] = $_COOKIE[$this->nameName];
        }
        if( array_key_exists( $this->websiteName, $_COOKIE ) )
        {
            $result['website'] = $_COOKIE[$this->websiteName];
        }
        if( array_key_exists( $this->emailName, $_COOKIE ) )
        {
            $result['email'] = $_COOKIE[$this->emailName];
        }
        if( array_key_exists( $this->notificationName, $_COOKIE ) )
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