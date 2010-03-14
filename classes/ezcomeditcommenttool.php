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
            default:
                return parent::isVariableRequired( $field );
        }
    }
    
    protected function setFieldValue( $field, $fieldPostName )
    {
        switch ( $field )
        {
            case 'notificationField':
                $http = eZHTTPTool::instance();
                $notification = false;
                if( $http->hasVariable( $fieldPostName ) 
                    && $http->postVariable( $fieldPostName ) === '1' )
                {
                    $notification = true;
                }
                $this->fieldValues[$field] = $notification;
                break;
            // don't change email value
            case 'email':
                break;
            default:
                parent::setFieldValue( $field, $fieldPostName );
                break;
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see extension/ezcomments/classes/ezcomFormTool#fillObject($comment, $fieldNames)
     */
    public function fillObject( $comment, $fieldNames = null )
    {
        if ( is_null( $fieldNames ) )
        {
            $fieldNames = array();
            foreach ( $this->fields as $field => $fieldSetup )
            {
                if( $field != 'email' )
                {
                    $fieldNames[] = $field;
                }
            }
        }
        parent::fillObject( $comment, $fieldNames );
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