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
    if ( $http->hasVariable( 'BackButton' ) )
    {
         return $module->redirectTo( $redirectURI );
    }
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
        // Build ezcomcomment object
        $comment = ezcomComment::create();

        $formTool->fillObject( $comment );

        $comment->setAttribute( 'contentobject_id', $contentObjectId );
        
        $languageId = eZContentLanguage::idByLocale( $languageCode );
        $comment->setAttribute( 'language_id', $languageId );

        $sessionKey = $http->getSessionKey();
        $comment->setAttribute( 'session_key', $sessionKey );

        $util = ezcomUtility::instance();
        $ip = $util->getUserIP();
        $comment->setAttribute( 'ip', $ip );

        $user = eZUser::currentUser();
        $comment->setAttribute( 'user_id', $user->attribute( 'contentobject_id' ) );

        $currentTime = time();
        $comment->setAttribute( 'created', $currentTime );
        $comment->setAttribute( 'modified', $currentTime );

        // toggle notification state on change in state
        // only when notification is enabled, the notification can be changed
        // when email is enabled or email is disabled in setting but user logged in, change notification 
        $notification = $formTool->fieldValue( 'notificationField' );
        $email = $comment->attribute( 'email' );
        $changeNotification = false;
        if ( $notification === true )
        {
            // email is enabled in setting
            if ( !is_null( $email ) )
            {
                $changeNotification = true;
            }
            else
            {
                //email is disabled in setting but user logged in
                if ( is_null( $email ) && !$user->isAnonymous() )
                {
                    $changeNotification = true;
                    $email = $user->attribute( 'email' );
                    $comment->setAttribute( 'email', $email );
                }
            }
        }
        $commentManager = ezcomCommentManager::instance();
        $commentManager->tpl = $tpl;
        $existingNotification = false;
        $addingResult = false;
        if ( $changeNotification )
        {
            $existingNotification = ezcomSubscription::exists( $contentObjectId,
                                                            $languageId,
                                                            'ezcomcomment',
                                                            $email );
            if ( !$existingNotification )
            {
                $addingResult = $commentManager->addComment( $comment, $user, null, true );
            }
            else
            {
                $addingResult = $commentManager->addComment( $comment, $user );
            }
        }
        else
        {
            $addingResult = $commentManager->addComment( $comment, $user );
        }
        
        if ( $addingResult !== true )
        {
            $tpl->setVariable( 'error_message', $addingResult );
            $Result['content'] = $tpl->fetch( 'design:comment/add.tpl' );
            return $Result;
        }
        $tpl->setVariable( 'success', true );
        // add additional success message
        if ( $changeNotification )
        {
            if ( !$user->isAnonymous() )
            {
                if ( $existingNotification )
                {
                    $tpl->setVariable( 'success_message', ezi18n( 'ezcomments/comment/add',
                                                             'You have already subscribed to comment updates on this content.' ) );
                }
                else
                {
                    $tpl->setVariable( 'success_message', ezi18n( 'ezcomments/comment/add',
                                                             'You will receive comment updates on the content.' ) );
                }
            }
            else
            {
                $tpl->setVariable( 'success_message', ezi18n( 'ezcomments/comment/add',
                                                         'A confirmation email has been sent to your email address. You will receive comment updates after confirmation.' ) );
            }
        }
       
        //remember cookies
        if ( $user->isAnonymous() )
        {
            $cookieManager = ezcomCookieManager::instance();
            if ( $http->hasPostVariable( 'CommentRememberme') &&
                 $http->postVariable( 'CommentRememberme' ) == '1' )
            {
                $cookieManager->storeCookie( $comment );
            }
            else
            {
                $cookieManager->clearCookie();
            }
        }
        
         eZContentCacheManager::clearContentCacheIfNeeded( $contentObjectId );
         if( !$changeNotification )
         {
             $commentINI = eZINI::instance( 'ezcomments.ini' );
             if( $commentINI->variable( 'GlobalSettings', 'RedirectAfterCommenting' ) === 'true' )
             {
                 $module->redirectTo( $redirectURI );
             }
         }
         else
         {
             $tpl->setVariable( 'redirect_uri', $redirectURI );
         }
    }
}
else
{
    $tpl->setVariable( 'error_message', ezi18n( 'ezcomments/comment/add', 'You should not access this view directly.' ) );
}

$Result['content'] = $tpl->fetch( 'design:comment/add.tpl' );
return $Result;

?>