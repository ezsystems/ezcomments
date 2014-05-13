<?php
/**
 * File containing logic of list view
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 */

$Module = $Params['Module'];

$http = eZHTTPTool::instance();
if ( $http->hasPostVariable( 'RemoveCommentsButton' ) )
{
    if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $deleteIDArray = $http->postVariable( 'DeleteIDArray' );
        if ( $deleteIDArray !== null )
        {
            $http->setSessionVariable( 'DeleteCommentsIDArray', $deleteIDArray );
            $Module->redirectTo( $Module->functionURI( 'removecomments' ) . '/' );
        }
    }
}

$contentInfoArray = array();

$tpl = eZTemplate::factory();

$tpl->setVariable( 'persistent_variable', false);

if ( isset( $Params['UserParameters'] ) )
{
    $UserParameters = $Params['UserParameters'];
}
else
{
    $UserParameters = array();
}
if ( isset( $Params['Offset'] ) ) $offset = (int) $Params['Offset'];
$viewParameters = array( 'offset' => $offset );
$viewParameters = array_merge( $viewParameters, $UserParameters );
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:comment/list.tpl' );
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'ezcomments/comment/list', 'Comments list' ),
                                'url' => false ) );

$contentInfoArray['persistent_variable'] = false;
if ( $tpl->variable( 'persistent_variable' ) !== false )
    $contentInfoArray['persistent_variable'] = $tpl->variable( 'persistent_variable' );

$Result['content_info'] = $contentInfoArray;

?>