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

    /**
     * @see ezcomComment::fetchByObjectIDList
     */
    public static function fetchCommentListByContentObjectIDList( $contentObjectIDList, $userID, $languageCode, $status, $sortField, $sortOrder, $offset, $length, $extraCondition )
    {
        $sorts = array( $sortField => $sortOrder );
        $result = ezcomComment::fetchByContentObjectIDList( $contentObjectIDList, $userID, $languageCode, $status, $sorts, $offset, $length, $extraCondition );
        return array( 'result' => $result );
    }


    /**
     * get latest comment list.
     * userID can userEmail can be used together or separate.
     * if $after is a specified time(not null), it will fetch comment after this time, then length can be used or not
     * if useModified is true, it uses modified time to judge 'latest' instead of created time
     *
     * @param integer $userID
     * @param string $userEmail
     * @param integer $length
     * @param boolean $useModified
     * @param integer|null $after
     * @param string $sortOrder
     * @return array<ezcomComment>|null|array()
     */
    public static function fetchLatestCommentList( $userID, $userEmail, $length, $useModified, $after, $sortOrder )
    {
        $extraCondition = array();
        if( $userEmail !== null )
        {
            $extraCondition['email'] = $userEmail;
        }
        $sortField = 'created';
        if( $useModified === true )
        {
            $sortField = 'modified';
        }

        if( $after !== null )
        {
            if( $useModified === true )
            {
                $extraCondition['modified'] = array( '>', $after );
            }
            else
            {
                $extraCondition['created'] = array( '>', $after );
            }
        }
        return self::fetchCommentListByContentObjectIDList( null, $userID, null, 1, $sortField, $sortOrder, null, $length, $extraCondition );
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