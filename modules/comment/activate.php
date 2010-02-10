<?php
/**
 * File containing logic of activate view
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

require_once( 'kernel/common/template.php' );
$tpl = templateInit();
$module = $Params['Module'];

if ( $module->isCurrentAction( 'Redirect' ) )
{
    $http = eZHTTPTool::instance();
    $redirectURI = $http->variable( 'RedirectURI' );
    $module->redirectTo( $redirectURI );
}
else
{
    $hashString = trim( $Params['HashString'] );
    $subscriptionManager =  new ezcomSubscriptionManager( $tpl, $Params, $module );
    $subscriptionManager->activateSubscription( $hashString );

    $Result['path'] = array( array( 'url' => false,
                                    'text' => ezi18n( 'extension/ezcomments/activate', 'Activate subscription' ) ) );
    $Result['content'] = $tpl->fetch( 'design:comment/activate.tpl' );
    return $Result;
}
?>