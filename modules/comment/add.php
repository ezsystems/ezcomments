<?php
/**
 * File containing logic of add view
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

// We are reloading the debug.ini settings here to get override values from extensions
$ini = eZINI::instance( 'debug.ini' );
$ini->loadCache();

require_once( 'kernel/common/template.php' );
$tpl = templateInit();

$module = $Params['Module'];
$http = eZHTTPTool::instance();

$Result = array();
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'ezcomments/comment/add', 'Add comment' ) ) );
$Result['content'] ='';

if ( $http->hasVariable( 'RedirectURI' ) )
{
    $redirectURI = $http->variable( 'RedirectURI' );
}

if ( $http->hasVariable( 'BackButton' ) &&
    $http->variable( 'BackButton') == 'Back')
{
     return $module->redirectTo( $redirectURI );
}

if ( $module->isCurrentAction( 'AddComment' ) )
{
    $contentCheck = ezcomPostHelper::checkContentRequirements( $module, $http );
    extract( $contentCheck );

    // Check to see if commenting is turned on, on the object level
    $commentContent = ezcomPostHelper::checkCommentPermission( $contentObject, $languageCode, $foundCommentAttribute );
    if ( !$commentContent['show_comments'] || !$commentContent['enable_comment'] )
    {
        $tpl->setVariable( 'error_message', ezi18n( 'ezcomments/comment/add', 'Commenting has been turned off for this content.'  ) );
        $Result['content'] = $tpl->fetch( 'design:comment/add.tpl' );
        return $Result;
    }
    else
    {
        // Validate given input date against form setup
        $formTool = ezcomAddCommentTool::instance();
        $formStatus = $formTool->checkVars();

        if ( !$formStatus )
        {
            // missing form data
            $tpl->setVariable( 'error_message', ezi18n( 'ezcomments/comment/add/form', 'There is a problem with your comment form ' ) );
            $tpl->setVariable( 'validation_messages', $formTool->messages() );
            $Result['content'] = $tpl->fetch( 'design:comment/add.tpl' );
            return $Result;
        }

         //TODO: from 63, most of the code can be implemented in a class see another TODO in edit.php
         $title = $formTool->fieldValue( 'title' );
         $name = $formTool->fieldValue( 'name' );
         $website = $formTool->fieldValue( 'website' );
         $email = $formTool->fieldValue( 'email' );
         $content = $formTool->fieldValue( 'comment' );
         $sessionKey = $http->getSessionKey();
         $util = ezcomUtility::instance();
         $ip = $util->getUserIP();

         $comment = ezcomComment::create();
         $comment->setAttribute( 'title', $title );
         $comment->setAttribute( 'name', $name );
         $comment->setAttribute( 'url', $website );
         $comment->setAttribute( 'email', $email );
         $comment->setAttribute( 'text', $content );
         $comment->setAttribute( 'session_key', $sessionKey );
         $comment->setAttribute( 'ip', $ip );

         $languageId = eZContentLanguage::idByLocale( $languageCode );

         $existingNotification = ezcomSubscription::exists( $contentObjectId,
                                                            $languageId,
                                                            'ezcomcomment',
                                                            $email );

         if ( $http->hasPostVariable( 'CommentNotified' ) &&
             $http->postVariable( 'CommentNotified' ) == 'on' )
         {
             $notification = true;
         }
         else
         {
             $notification = false;
         }

         $user = eZUser::currentUser();

         $comment->setAttribute( 'contentobject_id', $contentObjectId );
         $comment->setAttribute( 'language_id', $languageId );
         $currentTime = time();
         $comment->setAttribute( 'user_id', $user->attribute( 'contentobject_id' ) );
         $comment->setAttribute( 'created', $currentTime );
         $comment->setAttribute( 'modified', $currentTime );

         $commentManager = ezcomCommentManager::instance();
         $commentManager->tpl = $tpl;


         // toggle notification state on change in state
         if ( $notification == $existingNotification )
         {
             $addingResult = $commentManager->addComment( $comment, $user );
         }
         else
         {
             $addingResult = $commentManager->addComment( $comment, $user, null, $notification );
         }

         if ( $addingResult === true )
         {
             //remember cookies
             if ( $user->isAnonymous() )
             {
                 $cookieManager = ezcomCookieManager::instance();
                 if ( $http->hasPostVariable( 'CommentRememberme') &&
                     $http->postVariable( 'CommentRememberme' ) == 'on' )
                 {
                     $cookieManager->storeCookie( $comment );
                 }
                 else
                 {
                     $cookieManager->clearCookie();
                 }
             }

             eZContentCacheManager::clearContentCacheIfNeeded( $contentObjectId );

             $tpl->setVariable( 'success', true );
             $tpl->setVariable( 'redirect_uri', $redirectURI );
         }

         else
         {
            $tpl->setVariable( 'error_message', $addingResult );
         }
    }
}
else
{
    $tpl->setVariable( 'error_message', "You should not access this view directly" );
}

$Result['content'] = $tpl->fetch( 'design:comment/add.tpl' );
return $Result;

?>