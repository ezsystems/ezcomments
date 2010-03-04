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