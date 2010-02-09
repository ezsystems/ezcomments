<?php
/**
 * File containing logic of edit view
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

require_once( 'kernel/common/template.php' );
$tpl = templateInit();
$user = eZUser::currentUser();
$http = eZHttpTool::instance();

// get the action parameter
$Module = $Params['Module'];
$commentID = $Params['CommentID'];
// fetch comment object
if ( is_null( $commentID ) || $commentID == '' )
{
    eZDebug::writeError( 'The parameter comment id is null!', 'ezcomments' );
    return;
}
if ( !is_numeric( $commentID ) )
{
    eZDebug::writeError( 'The parameter comment id is not a number!', 'ezcomments' );
    return;
}
$comment = ezcomComment::fetch( $commentID );
if ( is_null( $comment ) )
{
    eZDebug::writeError( 'The comment doesn\'t exist!', 'ezcomments' );
    return;
}

//check the permission
$contentObject = $comment->contentObject();
$contentNode = $contentObject->mainNode();
$languageID = $comment->attribute( 'language_id' );
$languageCode = eZContentLanguage::fetch( $languageID )->attribute( 'locale' );
$canEdit = false;
$canEditResult = ezcomPermission::hasAccessToFunction( 'edit', $contentObject, $languageCode, $comment, null, $contentNode );
$canEdit = $canEditResult['result'];
$tpl->setVariable( 'can_edit', $canEdit );

if ( !$canEdit )
{
    $Result['path'] = array( array( 'url' => false,
                                    'text' => ezi18n( 'extension/ezcomments/edit', 'Edit comment' ) ) );
    $Result['content'] = $tpl->fetch( 'design:comment/edit.tpl' );
    return $Result;
}

$contentID = $comment->attribute( 'contentobject_id' );

$notified = ezcomSubscription::exists( $contentID,
                                       $languageID,
                                       'ezcomcomment',
                                       $comment->attribute( 'email' ) );

$tpl->setVariable( 'notified', $notified );


if ( $Module->isCurrentAction( 'UpdateComment' ) )
{
    //1. get the form values
    $title = $http->postVariable( 'CommentTitle' );
    $name = $http->postVariable( 'CommentName' );
    $website = $http->postVariable( 'CommentWebsite' );
    $email = $http->postVariable( 'CommentEmail' );
    $content = $http->postVariable( 'CommentContent' );
    $clientNotified = false;
    if ( $http->hasPostVariable( 'CommentNotified' ) )
    {
        if ( $http->postVariable( 'CommentNotified' ) == 'on' )
        {
            $clientNotified = true;
        }
    }

    $comment->setAttribute( 'title', $title );
    $comment->setAttribute( 'url', $website );
    $comment->setAttribute( 'text', $content );
    $time = time();
    $comment->setAttribute( 'modified', $time );
    $commentManager = ezcomCommentManager::instance();

    $updateResult = null;
    if ( $clientNotified == $notified )
    {
        $updateResult = $commentManager->updateComment( $comment, null, $time );
    }
    else
    {
        $updateResult = $commentManager->updateComment( $comment, null, $time, $clientNotified );
    }
    if ( $updateResult !== true )
    {
        $tpl->setVariable( 'message', ezi18n( 'extension/ezcomments/edit', 'Updating failed!') . $updateResult );
    }
    else
    {
        //clean up cache
        eZContentCacheManager::clearContentCache( $contentObject->attribute( 'id' ) );
        $redirectionURI = $http->postVariable('ezcomments_comment_redirect_uri');
        return $Module->redirectTo( $redirectionURI );
    }
    return showComment( $comment, $tpl );
}
else if ( $Module->isCurrentAction('Cancel') )
{
     $redirectionURI = $http->postVariable('ezcomments_comment_redirect_uri');
     return $Module->redirectTo( $redirectionURI );
}
else
{
     $redirectURI = null;
     if ( $http->hasSessionVariable( "LastAccessesURI" ) )
     {
         $redirectURI = $http->sessionVariable( 'LastAccessesURI' );
     }
     else
     {
         $redirectURI = '/comment/view/' . $comment->attribute( 'contentobject_id' );
         $redirectURI = eZURI::transformURI( $redirectURI );
     }
     $tpl->setVariable( 'redirect_uri', $redirectURI );
     return showComment( $comment, $tpl );
}

/**
 *
 * @param $comment
 * @param $tpl
 * @return array
 */
function showComment( $comment, $tpl )
{
    $tpl->setVariable( 'comment', $comment );
    $Result = array();
    $Result['path'] = array( array( 'url' => false,
                                        'text' => ezi18n( 'extension/ezcomments/edit', 'Edit comment' ) ) );
    $Result['content'] = $tpl->fetch( 'design:comment/edit.tpl' );
    return $Result;
}

?>