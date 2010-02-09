<?php
//
// Created on: <23-Jan-2010 22:00:00 xc>
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
require_once( 'kernel/common/template.php' );
$tpl = templateInit();
$http = eZHTTPTool::instance();
$user = eZUser::instance();

$ini = eZINI::instance( 'ezcomments.ini' );
$hashStringLength = $ini->variable( 'NotificationSettings' ,'SubscriberHashStringLength' );
$hashString = null;
$page = 1;

if ( $user->isAnonymous() )
{
    $hashString = trim( $Params[ 'HashString' ] );
    if ( !is_null( $Params['Page'] ) )
    {
        $page = $Params['Page'];
    }
    if ( is_null( $hashString ) || strlen( $hashString ) != $hashStringLength )
    {
        $Result = array();
        $Result['content'] = $tpl->fetch( 'design:comment/setting.tpl' );
        $Result['path'] = array( array( 'url' => false,
                                    'text' => ezi18n( 'extension/comment/setting', 'Comment settings') ) );
        return $Result;
    }
}
else
{
    if ( !is_null( $Params['HashString'] )
         && $Params['HashString'] !='' )
    {
        $page = $Params['HashString'];
    }
}
$tpl->setVariable( 'current_page', $page );
//TODO: validate page
if ( !is_numeric( $page ) )
{
    eZDebug::writeError( 'Page is not numeric!', 'Setting' );
    return;
}

$subscriber = null;
if ( !$user->isAnonymous() )
{
    $email = $user->attribute( 'email' );
    $subscriber = ezcomSubscriber::fetchByEmail( $email );
}
else
{
    $subscriber = ezcomSubscriber::fetchByHashString( $hashString );
}
if ( is_null( $subscriber ) )
{
    $Result = array();
    $Result['content'] = $tpl->fetch( 'design:comment/setting.tpl' );
    $Result['path'] = array( array( 'url' => false,
                                    'text' => ezi18n( 'extension/comment/setting', 'Comment settings' ) ) );
    return $Result;
}

$tpl->setVariable( 'subscriber',  $subscriber );

$email = $subscriber->attribute( 'email' );
$module = $Params['Module'];
if ( $module->isCurrentAction( 'Save' ) )
{
    $subscriberID = $http->postVariable( 'SubscriberID' );
    if ( $http->hasPostVariable( 'CheckboxName' ) )
    {
        $checkboxNameList = $http->postVariable( 'CheckboxName' );
        foreach( $checkboxNameList as $checkboxName )
        {
            $subscriptionID = substr( $checkboxName, strlen( 'Checkbox' ) );
            $subscribed = false;
            if ( $http->hasPostVariable( $checkboxName ) )
            {
                $subscribed = true;
            }
            
            $subscription = ezcomSubscription::fetch( $subscriptionID );
            
            if ( !$subscribed )
            {
                $subscription->remove();
            }
        }
        $tpl->setVariable( 'update_success', 1 );
        $redirectURI = 'comment/setting';
        if ( !is_null( $hashString ) )
        {
            $redirectURI = $redirectURI . '/' . $hashString;
        }
        $module->redirectTo( $redirectURI );
        return;
    }
}
//1.fetch Contents
$ini = eZINI::instance( 'ezcomments.ini' );
$numberPerPage = $ini->variable( 'NotificationSettings', 'NumberPerPage' );
$limit = array();
$limit['offset'] = ( $page - 1 ) * $numberPerPage;
$limit['length'] = $numberPerPage;
$sorts = array();
$sorts = array( 'subscription_time' => 'desc' );

$iniSite = eZINI::instance();
$languageCode = $iniSite->variable( 'RegionalSettings', 'Locale' );
$language = eZContentLanguage::fetchByLocale( $languageCode );
$languageID = $language->attribute( 'id' );

$subscriptionList = ezcomSubscription::fetchListBySubscriberID( $subscriber->attribute( 'id' ),
                                                              $languageID,
                                                              1,
                                                              $sorts,
                                                              $limit );
$totalCount = ezcomSubscription::countWithSubscriberID( $subscriber->attribute( 'id' ), 
                                                        $languageID, 
                                                        1 );

$tpl->setVariable( 'subscription_list',  $subscriptionList );
$tpl->setVariable( 'total_count',  $totalCount );

$Result['content'] = $tpl->fetch( 'design:comment/setting.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( '', 'Comment settings' ) ) );

?>