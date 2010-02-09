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

    public function checkVars()
    {
        $http = eZHTTPTool::instance();
        $user = eZUser::currentUser();
        $isAnon = $user->isAnonymous();
        $status = true;

        foreach (  $this->fields as $field => $fieldSetup )
        {
            $valueAdjusted = false;

            $fieldRequired = $fieldSetup[self::REQUIRED] == 'true' ? true : false;
            $fieldPostName = $fieldSetup[self::VARNAME];

            $fieldExists = $http->hasPostVariable( $fieldPostName );

            // Enforce certain requirements on the form
            switch ( $field )
            {
                case 'comment':
                    $fieldRequired = true;
                    break;

                // Use the values from the user objects for logged in users
                case 'email':
                case 'name':
                    if ( !$isAnon )
                    {
                        if ( $field == 'name' )
                        {
                            $this->fieldValues[$field] = $user->contentObject()->name();
                        }
                        else if ( $field == 'email' )
                        {
                            $this->fieldValues[$field] = $user->attribute( 'email' );
                        }
                        $fieldExists = true;
                        $valueAdjusted = true;
                    }
                    break;

                default:
                    break;
            }

            if ( $fieldRequired && !$fieldExists )
            {
                $status = false;
                $this->validationMessage[$field] = "$field is missing.";
                continue;
            }
            else if ( $fieldRequired && $fieldExists )
            {
                $val = $http->postVariable( $fieldPostName );
                if ( empty( $val ) )
                {
                    $status = false;
                    $this->validationMessage[$field] = "The field [$field] is empty";
                    continue;
                }
            }

            if ( $fieldExists && !$valueAdjusted )
            {
                $this->fieldValues[$field] = $http->postVariable( $fieldPostName );
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