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

if( $user->isAnonymous() )
{
    $hashString = trim( $Params[ 'HashString' ] );
    if( !is_null( $Params['Page'] ) )
    {
        $page = $Params['Page'];
    }
    if( is_null( $hashString ) || strlen( $hashString ) != $hashStringLength )
    {
        return;
    }
}
else
{
    if( !is_null( $Params['HashString'] ) )
    {
        $page = $Params['HashString'];
    }
}
$tpl->setVariable( 'current_page', $page );
//TODO: validate page
if( !is_numeric( $page ) )
{
    eZDebug::writeError( 'Page is not numeric!', 'Setting' );
    return;
}
//TODO: support paging

$subscriber = null;
if( !$user->isAnonymous() )
{
    $email = $user->attribute( 'email' );
    $subscriber = ezcomSubscriber::fetchByEmail( $email );
}
else
{
    $subscriber = ezcomSubscriber::fetchByHashString( $hashString );
}
if( is_null( $subscriber ) )
{
    return;
}
$tpl->setVariable( 'subscriber',  $subscriber );

$email = $subscriber->attribute( 'email' );
$module = $Params['Module'];
if( $module->isCurrentAction( 'Save' ) )
{
    $subscriberID = $http->postVariable( 'SubscriberID' );
    if( $http->hasPostVariable( 'CheckboxName' ) )
    {
        $checkboxNameList = $http->postVariable( 'CheckboxName' );
        foreach( $checkboxNameList as $checkboxName )
        {
            $contentID = substr( $checkboxName, strlen( 'Checkbox' ) );
            $subscribed = false;
            if( $http->hasPostVariable( $checkboxName ) )
            {
                $subscribed = true;
            }
            $subscription = ezcomSubscription::fetchByCond( 
                                array( 'subscriber_id' => $subscriberID,
                                       'content_id' => $contentID
                                      ));
            if( is_null( $subscription ) )
            {
                if( $subscribed )
                {
                    $subscriptionAdded = ezcomSubscription::create();
                    $subscriptionAdded->setAttribute( 'user_id', $user->attribute( 'contentobject_id' ) );
                    $subscriptionAdded->setAttribute( 'subscriber_id', $subscriberID );
                    $subscriptionAdded->setAttribute( 'subscription_type', 'ezcomcomment' );
                    $subscriptionAdded->setAttribute( 'content_id', $contentID );
                    $subscriptionAdded->setAttribute( 'subscription_time', time() );
                    $subscriptionAdded->setAttribute( 'enabled', 1 );
                    $subscriptionAdded->store();
                }
            }
            else
            {
                if( $subscribed )
                {
                    $subscription->setAttribute( 'enabled', 0 );
                    $subscription->store();
                }
                else
                {
                    $subscription->remove();
                }
            }
        }
        $tpl->setVariable( 'update_success', 1 );
    }
}
//1.fetch Contents
$contentObjectIDList = ezcomComment::fetchContentObjectByEmail( $email, false, null, null, null);
$contentObjectList = array();
if( is_array( $contentObjectIDList ) )
{
    foreach( $contentObjectIDList as $contentObjectID )
    {
        $row = array();
        $row['language_id'] = $contentObjectID['language_id'];
        $row['contentobject'] = eZContentObject::fetch( $contentObjectID['contentobject_id'] );
        $row['comment_count'] =  $contentObjectID['comment_count'];
        $contentID = $contentObjectID['contentobject_id'] . '_' . $contentObjectID['language_id'];
        $row['subscribed'] = ezcomSubscription::exists( $contentID, 'ezcomcomment', $email, 1 );
        $contentObjectList[] = $row;
    }
}

$totalCount = ezcomComment::countContentObjectByEmail( $email );

$tpl->setVariable( 'contentobject_list',  $contentObjectList );
$tpl->setVariable( 'total_count',  $totalCount );

$Result['content'] = $tpl->fetch( 'design:comment/setting.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( '', 'Comment settings' ) ) );

?>