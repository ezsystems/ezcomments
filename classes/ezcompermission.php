<?php
//
// Definition of ezcomPermission class
//
// Created on: <21-Jan-2010 13:12:00 xc>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Comments extension for eZ Publish
// SOFTWARE RELEASE: 1.0-0
// COPYRIGHT NOTICE: Copyright (C) 2010 eZ Systems AS
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

/**
 * class dealing with the comment permission
 *
 */
class ezcomPermission
{
    protected static $moduleName = 'comment';
    protected static $classKey = 'ContentClass';
    protected static $sectionKey = 'ContentSection';
    protected static $ownerKey = 'ContentOwner';
    protected static $languageKey = 'ContentLanguage';
    protected static $nodeKey = 'Node';
    protected static $subtreeKey = 'Subtree';
    protected static $commentCreatorKey = 'CommentCreator';
    protected static $instance = null;

    /**
     * check if the user has Acceess to the object with limitation of class, section, owner, language, nodes, subtrees
     * 
     * return true if has, false if not
     */
    public function hasFunctionAccess( $user, $functionName, $contentObject, $languageCode, $comment = null )
    {
        $result = $user->hasAccessTo( self::$moduleName, $functionName );
        if( $result['accessWord'] !== 'limited' )
        {
            return $result['accessWord'] === 'yes';
        }
        else
        {
            $checkingResult = true;
            foreach( $result['policies'] as $limitationArray )
            {
                foreach( $limitationArray as $limitationKey => $limitation )
                {
                    // deal with limitation checking
                    $resultItem = $this->checkPermission( $user, $limitationKey, $limitation,
                                                     $contentObject, $languageCode, $comment );
                    ezDebug::writeNotice( 'Permission checking result in ' . $functionName . ': key: ' . $limitationKey .
                                             ', result: ' . $resultItem, 'ezcomPermission' );
                    $checkingResult = $checkingResult & $resultItem;
                }
            }
            return $checkingResult;
        }
    }
    
   /**
    * Check the permission given user contentobject, language and comment(optional)
    * To extend the permission checking, please extend the class and override this method 
    * @param $user
    * @param $limitationKey key name of the limitiation, for instance 'Language'
    * @param $limitation limitation array, for instance '{eng-GB,nor-NO}'
    * @param $contentObject contentobject of the comments
    * @param $languageCode language code of the content object
    * @param $comment comment object if the permission is based on one comment.When the permission checking is for editing and delete, it's useful
    * @return true if the checking result is true, false otherwise
    */
    protected function checkPermission( $user, $limitationKey, $limitation, $contentObject, $languageCode, $comment = null )
    {
        switch( $limitationKey )
        {
            case self::$classKey:
                //check class
                 $contentClassID = $contentObject->attribute( 'contentclass_id' );
                 return in_array( $contentClassID, $limitation );
            case self::$sectionKey:
                //check section
                $contentSectionID = $contentObject->attribute( 'section_id' ); 
                return in_array( $contentSectionID, $limitation );
            case self::$ownerKey:
                //check owner
                $result = false;
                $ownerID = $contentObject->attribute( 'owner_id' );
                if( in_array( '1', $limitation ) )
                {
                    if( $user->attribute( 'contentobject_id' ) == $ownerID )
                    {
                        $result = true;
                    }
                }
                return $result;
            case self::$languageKey:
                return in_array( $languageCode, $limitation );
            case self::$nodeKey:
                //TODO: check permission of node
                return true;
            case self::$subtreeKey:
                return true;
                //TODO: check permission of subtree
            case self::$commentCreatorKey:
                if( $user->isAnonymous() )
                {
                    return false;
                }
                else
                {
                    $userID = $user->attribute( 'contentobject_id' );
                    $commentUserID = $comment->attribute( 'user_id' );
                    return  $userID == $commentUserID ;
                }
            default:
                return false;
        }
    }
    
    public static function hasAccessToFunction( $functionName, $contentObject, $languageCode, $comment = null )
    {
        $user = eZUser::currentUser();
        $permission = ezcomPermission::instance();
        $result = $permission->hasFunctionAccess( $user, $functionName, $contentObject, $languageCode, $comment );
        return array( 'result' => $result );
    }
    
    public static function instance()
    {
        if( is_null( self::$instance ) )
        {
            $ini = eZINI::instance( 'ezcomments.ini' );
            $className = $ini->variable( 'ManagerClasses', 'PermissionClass' );
            self::$instance = new $className(); 
        }
        return  self::$instance;
    }
}

?>