<?php
/**
 * File containing ezcomFunctionCollection class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */


class ezcomFunctionCollection
{

    public static function fetchCommentList( $contentObjectID, $languageID, $status, $sortField, $sortOrder, $offset, $length )
    {
        $sort = array( $sortField=>$sortOrder );
        $result = ezcomComment::fetchByContentObjectID( $contentObjectID, $languageID, $status, $sort, $offset, $length );
        return array( 'result' => $result );
    }

    public static function fetchCommentCount( $contentObjectID, $languageID, $status = null )
    {
        $result = ezcomComment::countByContent( $contentObjectID, $languageID, $status );
        return array( 'result' => $result );
    }

    public static function fetchRecaptchaHTML()
    {
        require_once 'recaptchalib.php';
        $ini = eZINI::instance( 'ezcomments.ini' );
        $publicKey = $ini->variable( 'RecaptchaSetting', 'PublicKey' );
        $useSSL = false;
        if( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] )
        {
            $useSSL = true;
        }
        return array( 'result' => recaptcha_get_html( $publicKey ), null, $useSSL );
    }
}

?>