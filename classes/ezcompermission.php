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
    protected static $sectionKey = 'ContentSection';
    protected static $commentCreatorKey = 'CommentCreator';
    protected static $instance = null;

    /**
     * check if the user has Acceess to the object with limitation of class, section, owner, language, nodes, subtrees
     * 
     * return true if has, false if not
     */
    public function hasFunctionAccess( $user, $functionName, $contentObject, $languageCode, $comment = null, $scope = null )
    {
        $result = $user->hasAccessTo( self::$moduleName, $functionName );
        
        if( $result['accessWord'] !== 'limited' )
        {
            return ( $result['accessWord'] === 'yes' ) and ( $scope !== 'personal' );
        }
        else
        {
            foreach( $result['policies'] as $limitationArray )
            {
                foreach( $limitationArray as $limitationKey => $limitation )
                {
                    // deal with limitation checking
                    $resultItem = $this->checkPermission( $user, $limitationKey, $limitation,
                                                     $contentObject, $languageCode, $comment, $scope );
                    ezDebug::writeNotice( 'Permission checking result in ' . $functionName . ': key: ' . $limitationKey .
                                             ', result: ' . $resultItem, __METHOD__ );
                    if ( $resultItem == true )
                        return true;
                }
            }
            return false;
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
    * @param $comment comment object if the permaission is based on one comment.When the permission checking is for editing and delete, it's useful
    * @return true if the checking result is true, false otherwise
    */
    protected function checkPermission( $user, $limitationKey, $limitation, $contentObject, $languageCode, $comment = null, $scope = null )
    {
        switch( $limitationKey )
        {
            // section limited policy
            case self::$sectionKey:
                // this does not match when looking for personal policies
                if ( $scope == 'personal' )
                    return false;
                
                $contentSectionID = $contentObject->attribute( 'section_id' );
                return in_array( $contentSectionID, $limitation );
            
            // owner limited policy
            case self::$commentCreatorKey:
                // this does not match when looking for role wide policies
                if ( $scope == 'role' )
                    return false;
                
                if( $user->isAnonymous() )
                {
                    return false;
                }
                else
                {
                    $userID = $user->attribute( 'contentobject_id' );
                    $commentUserID = $comment->attribute( 'user_id' );
                    return ( $userID == $commentUserID );
                }
            default:
                return false;
        }
    }
    
    /**
    * Checks if the current user has a 'self' edit/delete policy
    * 
    * @param eZContentObject $contentObject Used to check with a possible section
    * 
    * @return array An array with edit and delete as keys, and booleans as values
    */
    public static function selfPolicies( $contentObject )
    {
        $return = array( 'edit' => false, 'delete' => false );
        $sectionID = $contentObject->attribute( 'section_id' );
        
        $user = eZUser::currentUser();
        foreach( array_keys( $return ) as $functionName )
        {
            $policies = $user->hasAccessTo( self::$moduleName, $functionName );
            
            // unlimited policy, not personal
            if( $policies['accessWord'] !== 'limited' )
            {
                $return[$functionName] = false;
            }
            else
            {
                // scan limited policies
                foreach( $policies['policies'] as $limitationArray )
                {
                    // a self limitation exists
                    if ( isset( $limitationArray[self::$commentCreatorKey] ) )
                    {
                        // but it also has a section limitation
                        if ( isset( $limitationArray[self::$sectionKey] ) )
                        {
                            if ( in_array( $sectionID, $limitationArray[self::$sectionKey] ) )
                            {
                                $return[$functionName] = true;
                                break;
                            }
                        }
                        else
                        {
                            $return[$functionName] = true;
                            break;
                        }
                    }
                }
            }
        }
        
        return array( 'result' => $return );
    }
    
    /**
    * @param $scope What access scope should be accepted.
    *        Default is any, but possible values are:
    *        - role: the permissions is identical for any user sharing the same role
    *        - personal: the permission is limited by ownership (edit self for instance)
    */
    public static function hasAccessToFunction( $functionName, $contentObject, $languageCode, $comment = null, $scope = null )
    {
        $user = eZUser::currentUser();
        $permission = ezcomPermission::instance();
        $result = $permission->hasFunctionAccess( $user, $functionName, $contentObject, $languageCode, $comment, $scope );
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