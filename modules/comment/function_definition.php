<?php
$FunctionList = array();
$FunctionList['comment_list'] = array( 'name' => 'comment_list',
                                                  'operation_types' => array( 'read' ),
                                                  'call_method' => array( 'include_file' => 'extension/ezcomments/classes/ezcomfunctioncollection.php',
                                                  'class' => 'ezcomFunctionCollection',
                                                  'method' => 'fetchCommentList' ),
                                                  'parameter_type' => 'standard',
                                                  'parameters' => array(
                                                   array( 'name'=> 'contentobject_id',
                                                          'type'=>'integer',
                                                          'required'=>true,
                                                          'default'=>1
                                                    ),
                                                   array( 'name'=> 'language_id',
                                                          'type'=> 'integer',
                                                          'required' => true,
                                                          'default'  => 0
                                                      ),
                                                  array( 'name'=> 'status',
                                                          'type'=>'integer',
                                                          'required'=>false,
                                                          'default'=>null
                                                    ),
                                                  array( 'name'=> 'sort_field',
                                                      'type'=> 'string',
                                                      'required' => true,
                                                      'default'  => ''
                                                     ),
                                                 array( 'name'=> 'sort_order',
                                                  'type'=> 'string',
                                                  'required' => true,
                                                  'default'  => ''
                                                 ),
                                                 array( 'name'=> 'offset',
                                                      'type'=> 'integer',
                                                      'required' => true,
                                                      'default'  => 0
                                                  ),
                                                 array( 'name'=> 'length',
                                                      'type'=> 'integer',
                                                      'required' => true,
                                                      'default'  => 0
                                                  )
                                               )
                                             );
$FunctionList['comment_count'] = array( 'name' => 'comment_count',
                                                  'operation_types' => array( 'read' ),
                                                  'call_method' => array( 'include_file' => 'extension/ezcomments/classes/ezcomfunctioncollection.php',
                                                  'class' => 'ezcomFunctionCollection',
                                                  'method' => 'fetchCommentCount' ),
                                                  'parameter_type' => 'standard',
                                                  'parameters' => array(
                                                   array( 'name'=> 'contentobject_id',
                                                          'type'=>'integer',
                                                          'required'=>true,
                                                          'default'=>1
                                                    ),
                                                   array( 'name'=> 'language_id',
                                                          'type'=> 'integer',
                                                          'required' => true,
                                                          'default'  => 0
                                                      ),
                                                   array( 'name'=> 'status',
                                                          'type'=> 'integer',
                                                          'required' => false,
                                                          'default'  => null
                                                      )
                                                  )
                                             );
$FunctionList['has_access_to_function'] = array( 'name' => 'has_access_to_function',
                                                  'operation_types' => array( 'read' ),
                                                  'call_method' => array( 'include_file' => 'extension/ezcomments/classes/ezcomPermission',
                                                  'class' => 'ezcomPermission',
                                                  'method' => 'hasAccessToFunction' ),
                                                  'parameter_type' => 'standard',
                                                  'parameters' => array(
                                                   array( 'name'=> 'function',
                                                          'type'=>'string',
                                                          'required'=> true,
                                                          'default'=> ''
                                                    ),
                                                      array( 'name'=> 'contentobject',
                                                          'type'=>'object',
                                                          'required'=>true,
                                                          'default'=> null
                                                    ),
                                                   array( 'name'=> 'language_code',
                                                          'type'=> 'integer',
                                                          'required' => true,
                                                          'default'  => 0
                                                      ),
                                                   array( 'name'=> 'comment',
                                                          'type'=> 'object',
                                                          'required' => false,
                                                          'default'  => null
                                                      ),
                                                   array( 'name' => 'scope',
                                                          'type' => 'string',
                                                          'required' => false,
                                                          'default' => null
                                                      ),
                                                   array( 'name' => 'node',
                                                          'type' => 'object',
                                                          'required' => false,
                                                          'default' => null )
                                                  )
                                             );
$FunctionList['self_policies'] = array( 'name' => 'has_self_access',
                                        'operation_types' => array( 'read' ),
                                        'call_method' => array(
                                            'include_file' => 'extension/ezcomments/classes/ezcomPermission.php',
                                            'class' => 'ezcomPermission',
                                            'method' => 'selfPolicies'
                                        ),
                                        'parameter_type' => 'standard',
                                        'parameters' => array(
                                            array(
                                                'name' => 'contentobject',
                                                'type' => 'object',
                                                'required' => true
                                            ),
                                            array(
                                                'name' => 'node',
                                                'type' => 'object',
                                                'required' => false,
                                                'default' => null ),
                                        ) );
$FunctionList['recaptcha_html'] = array( 'name' => 'recaptcha_html',
                                        'operation_types' => array( 'read' ),
                                        'call_method' => array(
                                            'include_file' => 'extension/ezcomments/classes/ezcomfunctioncollection.php',
                                            'class' => 'ezcomFunctionCollection',
                                            'method' => 'fetchRecaptchaHTML'
                                        ),
                                        'parameter_type' => 'standard',
                                        'parameters' => array() );

$FunctionList['has_access_to_security'] = array( 'name' => 'has_access_to_security',
                                        'operation_types' => array( 'read' ),
                                          'call_method' => array( 'include_file' => 'extension/ezcomments/classes/ezcomPermission',
                                          'class' => 'ezcomPermission',
                                          'method' => 'hasAccessToSecurity' ),
                                          'parameter_type' => 'standard',
                                          'parameters' => array(
                                           array( 'name'=> 'limitation',
                                                  'type'=>'string',
                                                  'required'=> true,
                                                  'default'=> ''
                                            ),
                                            array( 'name'=> 'option_value',
                                                   'type'=>'string',
                                                   'required'=> true,
                                                   'default'=> ''
                                            )
   )
);
?>