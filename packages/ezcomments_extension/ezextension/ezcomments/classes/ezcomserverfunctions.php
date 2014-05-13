<?php
/**
 * File containing ezcomServerFunctions class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 */

/*
 * ezjscServerFunctions for ezcomments
 */

class ezcomServerFunctions extends ezjscServerFunctions
{

    public static function userData()
    {
        unset( $_COOKIE['eZCommentsUserData'] );
        $cookie = ezcomCookieManager::instance();
        return $cookie->storeCookie();
    }
}