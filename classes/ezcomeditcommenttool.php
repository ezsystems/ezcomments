<?php
/**
 * File containing ezcomEditCommentTool class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

/**
 * Form tool for editing comment 
 */
class ezcomEditCommentTool extends ezcomFormTool
{
    private static $instance = null;
    
    /**
     * validate field in editing comment
     * @see extension/ezcomments/classes/ezcomFormTool#validateField($field, $value)
     */
    protected function validateField( $field, $value )
    {
        return true;
    }
    
    /**
     * isVariableRequire in editing comment.
     * When editing comment, the email and name variable is not required
     * @see extension/ezcomments/classes/ezcomFormTool#isVariableRequired($field)
     */
    public function isVariableRequired( $field )
    {
        switch ( $field )
        {
            case 'email':
                return false;
            case 'name':
                return false;
            default:
                return parent::isVariableRequired( $field );
        }
    }
    
    public static function instance()
    {
        if ( is_null( self::$instance ) )
        {
            $ini = eZINI::instance( 'ezcomments.ini' );
            $className = $ini->variable( 'ManagerClasses', 'EditCommentToolClass' );
            self::$instance = new $className();
        }
        return self::$instance;
    }
}
?>