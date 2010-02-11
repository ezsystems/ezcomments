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
     * Implement the validatation in adding comment
     * @see extension/ezcomments/classes/ezcomFormTool#validateField($field)
     */
    protected function validateField( $field, $value )
    {
        switch ( $field )
        {
            case 'email':
                $result = eZMail::validate( $value );
                if ( !$result )
                {
                    return 'Not a valid email address.';
                }
                else
                {
                    return true;
                }
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
            case 'name':
                if( !$user->isAnonymous() )
                {
                    $this->fieldValues[$field] = $user->contentObject()->name();
                }
                else
                {
                    parent::setFieldValue( $field, $fieldPostName );
                }
                break;
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