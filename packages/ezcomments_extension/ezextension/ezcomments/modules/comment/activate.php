<?php
/**
 * File containing logic of activate view
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 */

$tpl = eZTemplate::factory();
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
                                    'text' => ezpI18n::tr( 'ezcomments/comment/activate', 'Activate subscription' ) ) );
    $Result['content'] = $tpl->fetch( 'design:comment/activate.tpl' );
    return $Result;
}
?>
