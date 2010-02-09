<?php
/**
 * File containing logic of setting view
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */
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