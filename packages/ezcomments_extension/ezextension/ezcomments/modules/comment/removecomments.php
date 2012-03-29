<?php
/**
 * File containing logic of removecomments view
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

$Module = $Params['Module'];
$http = eZHTTPTool::instance();

if ( $http->hasPostVariable( 'ConfirmButton' ) )
{
    $deleteIDArray = $http->hasSessionVariable( 'DeleteCommentsIDArray' ) ? $http->sessionVariable( 'DeleteCommentsIDArray' ) : array();

    if ( is_array( $deleteIDArray ) && !empty( $deleteIDArray ) )
    {
        $db = eZDB::instance();
        $db->begin();
        $commentManager = ezcomCommentManager::instance();

        foreach ( $deleteIDArray as $deleteID )
        {
            $commentToRemove = ezcomComment::fetch( $deleteID );
            $deleteResult = $commentManager->deleteComment( $commentToRemove );
            if ( $deleteResult === true )
            {
                eZContentCacheManager::clearContentCacheIfNeeded( $commentToRemove->attribute( 'contentobject_id' ) );
            }
        }

        $db->commit();
    }

    $Module->redirectTo( '/comment/list/' );
}
if ( $http->hasPostVariable( 'CancelButton' ) )
{
    $Module->redirectTo( '/comment/list/' );
}

$contentInfoArray = array();

$tpl = eZTemplate::factory();

$tpl->setVariable( 'persistent_variable', false );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:comment/removecomments.tpl' );
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'ezcomments/comment/removecomments', 'Remove comments' ),
                                'url' => false ) );

$contentInfoArray['persistent_variable'] = false;
if ( $tpl->variable( 'persistent_variable' ) !== false )
    $contentInfoArray['persistent_variable'] = $tpl->variable( 'persistent_variable' );

$Result['content_info'] = $contentInfoArray;

?>
