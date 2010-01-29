<?php
//
// Created on: <08-Dec-2009 00:00:00 xc>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Comments extension for eZ Publish
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

$ViewList['setting'] = array(
       'functions' => array( 'setting' ),
       'single_post_actions' => array( 'SaveButton' => 'Save' ),
       'script' => 'setting.php',
       'params' => array( 'HashString', 'Page' ),
       );

$ViewList['view'] = array(
       'functions' => array( 'read' ),
       'script' => 'view.php',
       'params' => array( 'ViewMode', 'ContentObjectID', 'Page' ),
       );
$ViewList['add'] = array(
       'functions' => array( 'add' ),
       'script' => 'add.php',
       'params' => array(),
       );
$ViewList['edit'] = array(
       'functions' => array( 'edit' ),
       'script' => 'edit.php',
       'single_post_actions' => array( 'UpdateCommentButton' => 'UpdateComment',
                                       'CancelButton'=>'Cancel' ),
       'params' => array( 'CommentID' ),
       );
$ViewList['activate'] = array(
       'functions' => array( ' ' ),
       'script' => 'activate.php',
       'single_post_actions' => array( 'RedirectButton'=>'Redirect' ),
       'params' => array( 'HashString' ),
       );
$ViewList['delete'] = array(
       'functions' => array( 'delete' ),
       'script' => 'delete.php',
       'single_post_actions' => array( 'DeleteCommentButton' => 'DeleteComment',
                                       'CancelButton'=>'Cancel' ),
       'params' => array( 'CommentID' ),
       );

$SectionID = array(
    'name'=> 'ContentSection',
    'values'=> array(),
    'path' => 'classes/',
    'file' => 'ezsection.php',
    'class' => 'eZSection',
    'function' => 'fetchList',
    'parameter' => array( false )
    );

$Creator = array(
    'name' => 'CommentCreator',
    'values' => array(
            array(
                'Name' => 'Self',
                'value' => '1'
                )
        )
    );


$FunctionList = array();
$FunctionList['read'] = array( 'ContentSection' => $SectionID );
$FunctionList['add'] = array( 'ContentSection' => $SectionID );

$FunctionList['edit'] = array( 'ContentSection' => $SectionID,
                               'CommentCreator' => $Creator );

$FunctionList['delete'] = array( 'ContentSection' => $SectionID,
                                 'CommentCreator' => $Creator );
$FunctionList['setting'] = array();
$FunctionList['activate'] = array();
?>