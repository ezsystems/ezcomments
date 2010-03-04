<?php
/**
 * File containing ezcomFormTool class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

class ezcomFormTool
{
    private static $instance = null;

    const REQUIRED = 0;
    const DISPLAY = 1;
    const VARNAME = 2;

    protected $fields = array();
    protected $validationMessage = array();
    protected $validationStatus = null;
    protected $fieldValues = array();

    public function __construct()
    {
        $ini = eZINI::instance( 'ezcomments.ini' );
        $fields = $ini->variable( 'FormSettings', 'AvailableFields' );
        $fieldSetting = array();
        foreach( $fields as $field )
        {
            $fieldSetting[$field] = $ini->variableMulti( $field, array( 'Required', 'Display', 'PostVarName' ) );

        }
        $this->fields = $fieldSetting;
    }

    /**
     * If the variable is required in current operation. This is different from required in field. 
     * Sometimes the field variable is not required for instace, user's email in edit operation.
     * The system first check this method and then the default setting.
     * @param field identifier
     * @return boolean if the field variable is required in current operation. True if required, false otherwise.
     */
    public function isVariableRequired( $field )
    {
        return true;
    }
    
    /**
     * validate field
     * @param string $field identifier
     * @return boolean|string true if validation succeeds, string if validation fails
     */
    protected function validateField( $field, $value )
    {
        return true;
    }
    
    /**
     * Adjust field if needed. The adjusted value is inside $this->fieldValues
     * @param string $field field identifier
     */
    protected function setFieldValue( $field, $fieldPostName )
    {
        $http = eZHTTPTool::instance();
        $this->fieldValues[$field] = $http->postVariable( $fieldPostName );
    }
    
    /**
     * check variable from client
     */
    public function checkVars()
    {
        $http = eZHTTPTool::instance();
        $user = eZUser::currentUser();
        $isAnon = $user->isAnonymous();
        $status = true;

        foreach ( $this->fields as $field => $fieldSetup )
        {  
            $fieldPostName = $fieldSetup[self::VARNAME];
            $this->setFieldValue( $field, $fieldPostName );
            if( !$this->isVariableRequired( $field ) )
            {
                continue;
            }
            else
            {
                $fieldRequired = $fieldSetup[self::REQUIRED] == 'true' ? true : false;
                $fieldExists = $http->hasPostVariable( $fieldPostName );

                if ( $fieldRequired && !$fieldExists )
                {
                    $status = false;
                    $this->validationMessage[$field] = ezi18n( 'ezcomments/comment/add',
                                                               '%1 is missing.',
                                                               null,
                                                               array( $field ) );
                    continue;
                }
                else if ( $fieldRequired && $fieldExists )
                {
                    $val = $http->postVariable( $fieldPostName );
                    if ( empty( $val ) )
                    {
                        $status = false;
                        $this->validationMessage[$field] = ezi18n( 'ezcomments/comment/add',
                                                                   'The field [%1] is empty.',
                                                                   null,
                                                                   array( $field ) );
                        continue;
                    }
                    else
                    {
                        $validationResult = $this->validateField( $field, $val );
                        if ( $validationResult !== true )
                        {
                            $status = false;
                            $this->validationMessage[$field] = $validationResult;
                            continue;
                        }
                    }
                }
            }
            
        }
        $this->validationStatus = $status;
        return $status;
    }

    public function status()
    {
        return $this->validationStatus();
    }

    public function messages()
    {
        return $this->validationMessage;
    }

    public function fieldValue( $name = false )
    {
        if ( !$name )
        {
            return $this->fieldValues;
        }
        else if ( !empty( $name ) && isset( $this->fieldValues[$name] ) )
        {
            return $this->fieldValues[$name];
        }
    }

    public static function instance()
    {
        if ( self::$instance === null )
        {
            self::$instance = new ezcomFormTool();
        }
        return self::$instance;
    }
}

?>