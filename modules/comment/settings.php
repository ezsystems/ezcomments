<?php
//
// Created on: <09-Dec-2009 00:00:00 xc>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Comments extension for eZ Publish
// SOFTWARE RELEASE: 1.0-1
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
 *  Logic for view notification to set set user notifications
 */
require_once( 'kernel/common/template.php' );

//2. get user's ID
$tpl = templateInit();

$user = eZUser::currentUser();
$Result = array();
$displayTemplate = false;

if($user->isAnonymous())
{
    $hashString = $Params['HashString'];
    if( !is_null( $hashString ) && $hashString!="" )
    {
        $subscriber = ezcomSubscriber::fetchByHashString($hashString);
        if( !is_null( $subscriber ) )
        {
            $tpl->setVariable( 'subscriber', $subscriber );
            $displayTemplate = true;
        }
        
    }
}
else if($user->isLoggedin())
{
    $displayTemplate = true;
}
if( $displayTemplate )
{
$Result['content'] = $tpl->fetch( 'design:comment/settings.tpl' );
}
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( '', 'Comment settings' ) ) );
?>