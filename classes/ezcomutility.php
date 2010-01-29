<?php
//
// Definition of ezcomUtility class
//
// Created on: <18-Dec-2009 15:08:00 xc>
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
 * Utility library for comment system
 *
 */
class ezcomUtility
{

    public function generateSusbcriberHashString( $subscriber )
    {
        return strtoupper( hash( 'md5', uniqid( '', true ). time() ) );
    }

    /**
     * generate the hashstring of a subscription
     * @param $input
     * @return string the hashed string
     */
    public function generateSubscriptionHashString( $subscription )
    {
        return strtoupper( hash( 'md5', uniqid( '', true ). time() ) );
    }

    /**
     * create new instance of the object
     * TODO: load the class dynamically
     * @return ezcomUtility
     */
    public static function instance()
    {
        return new ezcomUtility();
    }
}

?>