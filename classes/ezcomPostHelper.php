<?php
/**
 * File containing ezcomPostHelper class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

class ezcomPostHelper
{
    public static function checkContentRequirements( $module, $http )
    {
        // Check that the object params are 'ok'
        if( !$http->hasPostVariable( 'ContentObjectID' ) )
        {
            eZDebug::writeError( 'No content object id is provided', 'ezcomments' );
            return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
        }
        $contentObjectId = (int)$http->postVariable('ContentObjectID');

        // Either use provided language code, or fallback on siteaccess default
        if ( $http->hasPostVariable( 'CommentLanguageCode' ) )
        {
            $languageCode = $http->postVariable( 'CommentLanguageCode' );
            $language = eZContentLanguage::fetchByLocale( $languageCode );
            if ( $language === false )
            {
                eZDebug::writeError( "The language code [$languageCode] given is not valid in the system.", 'ezcomments' );
                return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
            }
        }
        else
        {
            $defaultLanguage = eZContentLanguage::topPriorityLanguage();
            $languageCode = $defaultLanguage->attribute( 'locale' );
        }

        // Check that our object is actually a valid holder of comments
        $contentObject = eZContentObject::fetch( $contentObjectId );
        if ( !($contentObject instanceof eZContentObject ) )
        {
            eZDebug::writeError( 'No content object exists for the given id.', 'ezcomments' );
            return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
        }

        $dataMap = $contentObject->fetchDataMap( false, $languageCode );
        $foundCommentAttribute = false;
        foreach( $dataMap as $attr )
        {
            if( $attr->attribute( 'data_type_string' ) === 'ezcomcomments' )
            {
                $foundCommentAttribute = $attr;
                break;
            }
        }

        // if there is no ezcomcomments attribute inside the content, return
        if( !$foundCommentAttribute )
        {
            eZDebug::writeError( "Content object with id [$contentObjectId], does not contain an ezcomments attribute.", 'ezcomments' );
            return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
        }

        return compact( 'contentObjectId', 'languageCode', 'contentObject', 'foundCommentAttribute' );
    }

    public static function checkCommentPermission( $contentObject, $languageCode, $foundCommentAttribute )
    {
        //check permission
        $canAddComment = ezcomPermission::hasAccessToFunction( 'add', $contentObject, $languageCode,  null, null, $contentObject->mainNode() );
        if ( !$canAddComment['result'] )
        {
            eZDebug::writeWarning( 'No access to adding comments.', 'ezcomments' );
            return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
        }

        // Check to see if commenting is turned on, on the object level
        $commentContent = $foundCommentAttribute->content();

        return $commentContent;
    }

    public static function validateFormData()
    {
    }
}

?>