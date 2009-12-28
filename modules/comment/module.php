<?php
//
// Created on: <8-Dec-2009 00:00:00 xc>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ JSCore extension for eZ Publish
// SOFTWARE RELEASE: 1.0-1
// COPYRIGHT NOTICE: Copyright (C) 2009 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
// 
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
// 
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
// 
// 
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//
$Module = array( 'name' => 'ezcom Module and Views' );

$ViewList = array();
$ViewList['settings'] = array(
       'script' => 'settings.php',
       'params' => array('HashString'),
       );
$ViewList['view'] = array(
       'functions' => array( 'read' ),
       'script' => 'view.php',
       'params' => array( 'ViewMode', 'ContentObjectID', 'Page' ),
       );
       
$ClassID = array(
    'name'=> 'Class',
    'values'=> array(),
    'path' => 'classes/',
    'file' => 'ezcontentclass.php',
    'class' => 'eZContentClass',
    'function' => 'fetchList',
    'parameter' => array( 0, false, false, array( 'name' => 'asc' ) )
    );
    
$SectionID = array(
    'name'=> 'Section',
    'values'=> array(),
    'path' => 'classes/',
    'file' => 'ezsection.php',
    'class' => 'eZSection',
    'function' => 'fetchList',
    'parameter' => array( false )
    );
    
$Assigned = array(
    'name'=> 'Owner',
    'values'=> array(
        array(
            'Name' => 'Self',
            'value' => '1')
        )
    );
    
$Language = array(
    'name'=> 'Language',
    'values'=> array(),
    'path' => 'classes/',
    'file' => 'ezcontentlanguage.php',
    'class' => 'eZContentLanguage',
    'function' => 'fetchLimitationList',
    'parameter' => array( false )
    );
    
$Node = array(
    'name'=> 'Node',
    'values'=> array()
    );
    
$Subtree = array(
    'name'=> 'Subtree',
    'values'=> array()
    );
    
$FunctionList = array();
$FunctionList['read'] = array( 'Class' => $ClassID,
                               'Section' => $SectionID,
                               'Owner' => $Assigned,
                               'Language' => $Language,
                               'Node' => $Node,
                               'Subtree' => $Subtree );


$FunctionList['edit'] = array( 'Class' => $ClassID,
                               'Section' => $SectionID,
                               'Owner' => $Assigned,
                               'Language' => $Language,
                               'Node' => $Node,
                               'Subtree' => $Subtree );

$FunctionList['remove'] = array( 'Class' => $ClassID,
                               'Section' => $SectionID,
                               'Owner' => $Assigned,
                               'Language' => $Language,
                               'Node' => $Node,
                               'Subtree' => $Subtree );
?>