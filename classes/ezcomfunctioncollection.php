<?php
//
// Definition of ezcomFunctionCollection class
//
// Created on: <13-Jan-2010 00:00:00 xc>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Comments extension for eZ Publish
// SOFTWARE RELEASE: 1.0-0
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


class ezcomFunctionCollection
{
 
 static function fetchCommentList( $contentObjectID, $languageID, $sortField, $sortOrder, $length )
 {
    $sort = array( $sortField=>$sortOrder );
    $result = ezcomComment::fetchByContentObjectID( $contentObjectID, $languageID, $sort, 0, $length );
    return array( 'result' => $result );
 }
 
static function fetchCommentCount( $contentObjectID, $languageID )
 {
    $result = ezcomComment::countByContent( $contentObjectID, $languageID );
    return array( 'result' => $result );
 }
}
 
?>