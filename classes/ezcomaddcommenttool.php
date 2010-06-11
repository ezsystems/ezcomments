<?php
/**
 * File containing ezcomAddCommentTool class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

/**
 * Form tool for adding comment 
 *
 */
class ezcomAddCommentTool extends ezcomFormTool
{
    private static $instance = null;
    
    
    /**
     * isVariableRequire in adding comment.
     * When adding comment, for logined user the email is not required
     * @see extension/ezcomments/classes/ezcomFormTool#isVariableRequired($field)
     */
    public function isVariableRequired( $field )
    {
        switch ( $field )
        {
            case 'email':
                $user = eZUser::currentUser();
                if( !$user->isAnonymous() )
                {
                    return false;
                }
                return true;
            case 'recaptcha':
                // if the user bypasses captcha, don't validate field
                $bypassCaptcha = ezcomPermission::hasAccessToSecurity( 'AntiSpam' , 'bypass_captcha' );
                if( $bypassCaptcha['result'] )
                {
                    return false;
                }
                return true;
            default:
            return parent::isVariableRequired( $field );
        }
    }
    
    /**
     * Implement the validatation in adding comment
     * @see extension/ezcomments/classes/ezcomFormTool#validateField($field)
     */
    protected function validateField( $field, $value )
    {
        switch ( $field )
        {
            case 'email':
                // just validate anonymous's input email
                $user = eZUser::currentUser();
                if( $user->isAnonymous() )
                {
                    $result = eZMail::validate( $value );
                    if ( !$result )
                    {
                        return ezi18n( 'ezcomments/comment/add', 'Not a valid email address.' );
                    }
                }
                return true;
            case 'recaptcha':
                require_once 'recaptchalib.php';
                $ini = eZINI::instance( 'ezcomments.ini' );
                $privateKey = $ini->variable( 'RecaptchaSetting' , 'PrivateKey' );
                $http = eZHTTPTool::instance();
                if( $http->hasPostVariable( 'recaptcha_challenge_field' ) &&
                    $http->hasPostVariable( 'recaptcha_response_field' ) )
                {
                    $ip = $_SERVER["REMOTE_ADDR"];
                    $challengeField = $http->postVariable( 'recaptcha_challenge_field' );
                    $responseField = $http->postVariable( 'recaptcha_response_field' );
                    $capchaResponse = recaptcha_check_answer( $privateKey, $ip, $challengeField, $responseField );
                    if( !$capchaResponse->is_valid )
                    {
                         return ezi18n( 'ezcomments/comment/add', 'The words you input are incorrect.' );
                    }
                }
                else
                {
                    return ezi18n( 'ezcomments/comment/add', 'Captcha parameter error.' );
                }
                return true;
            default:
                return true;
        }
    }
    
    /**
     * Implement the setFieldValue in adding comment
     * @see extension/ezcomments/classes/ezcomFormTool#setFieldValue($fieldPostName)
     */
    protected function setFieldValue( $field, $fieldPostName )
    {
        $user = eZUser::currentUser();
        switch ( $field )
        {
            case 'email':
                if( !$user->isAnonymous() )
                {
                    $this->fieldValues[$field] = $user->attribute( 'email' );
                }
                else
                {
                    parent::setFieldValue( $field, $fieldPostName );
                }
                break;
            case 'notificationField':
                $http = eZHTTPTool::instance();
                $notification = false;
                if( $http->hasPostVariable( $fieldPostName ) && $http->postVariable( $fieldPostName ) == '1')
                {
                    $notification = true;
                }
                $this->fieldValues[$field] = $notification;
                break;
            default:
                parent::setFieldValue( $field, $fieldPostName );
                break;
        }
    }
    
    public static function instance()
    {
       if ( is_null( self::$instance ) )
        {
            $ini = eZINI::instance( 'ezcomments.ini' );
            $className = $ini->variable( 'ManagerClasses', 'AddCommentToolClass' );
            self::$instance = new $className();
        }
        return self::$instance;
    }
}
?>