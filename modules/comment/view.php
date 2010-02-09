<?php
/**
 * File containing logic of comment view(comment/view in uri)
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

/**
 *  Logic for view notification to set user notifications
 */
require_once( 'kernel/common/template.php' );

$http = eZHTTPTool::instance();

// fetch the content object
if ( !is_numeric( $Params['ContentObjectID'] ) )
{
    eZDebug::writeError( 'The parameter ContentObjectID is not a number!', 'ezcomments' );
    return;
}
$contentObjectID = (int)$Params['ContentObjectID'];
$contentObject = eZContentObject::fetch( $contentObjectID );

// fetch the language
$ini =eZINI::instance();
$languageCode = $ini->variable( 'RegionalSettings', 'Locale' );
$language = eZContentLanguage::fetchByLocale( $languageCode );
$languageID = $language->attribute( 'id' );

// fetch the content object attribute
$objectAttributes = $contentObject->fetchDataMap( false, $languageCode );
$objectAttribute = null;
foreach( $objectAttributes as $attribute )
{
    if ( $attribute->attribute( 'data_type_string' ) === 'ezcomcomments' )
    {
        $objectAttribute = $attribute;
        break;
    }
}

// if there is no ezcomcomments attribute inside the content, return
if ( is_null( $objectAttribute ) )
{
    eZDebug::writeError( 'The object doesn\'t have a ezcomcomments attribute!', 'ezcomments' );
    return;
}

$tpl = templateInit();
$tpl->setVariable( 'contentobject', $contentObject );
$tpl->setVariable( 'node', $contentObject->mainNode() );
$tpl->setVariable( 'objectattribute', $objectAttribute );
$tpl->setVariable( 'language_id', $languageID );
$tpl->setVariable( 'language_code', $languageCode );

$canAdd = false;
$canAddResult = ezcomPermission::hasAccessToFunction( 'add', $contentObject, $languageCode );
$canAdd = $canAddResult['result'];

$canRead = false;
$canReadResult = ezcomPermission::hasAccessToFunction( 'read', $contentObject, $languageCode );
$canRead = $canReadResult['result'];

$user = eZUser::currentUser();
$userID = $user->attribute( 'contentobject_id' );
$Module = $Params['Module'];

$Page = null;
if ( !is_null( $Params['Page'] ) )
{
 if ( !is_numeric( $Params['Page'] ) )
 {
     eZDebug::writeError( 'The parameter for page is not a number!', 'ezcomments' );
     return;
 }
 else
 {
    $Page = $Params['Page'];
 }
}
else
{
 $Page = 1;
}

$tpl->setVariable( 'can_add', $canAdd );
$tpl->setVariable( 'can_read', $canRead );
// get the comment list
$count = ezcomComment::countByContent( $contentObjectID, $languageID );

$ezcommentsINI = eZINI::instance( 'ezcomments.ini' );
$defaultNumPerPage = $ezcommentsINI->variable( 'CommentSettings', 'NumberPerPage' );
$offset =  ( $Page - 1 ) * $defaultNumPerPage;

if ( $offset > $count || $offset < 0 )
{
 eZDebug::writeError( 'Offset overflowed!', 'ezcomments' );
 return;
}

$length = $defaultNumPerPage;

$defaultSortField = $ezcommentsINI->variable( 'CommentSettings', 'DefaultSortField' );
$defaultSortOrder = $ezcommentsINI->variable( 'CommentSettings', 'DefaultSortOrder' );
$sorts = array( $defaultSortField => $defaultSortOrder );

$comments = ezcomComment::fetchByContentObjectID( $contentObjectID, $languageID, $sorts, $offset, $length);

$tpl->setVariable( 'comments', $comments );
$tpl->setVariable( 'total_count', $count );
$tpl->setVariable( 'total_page', ceil( $count / $defaultNumPerPage) );
$tpl->setVariable( 'current_page', $Page );
$tpl->setVariable( 'number_per_page', $defaultNumPerPage );

$Result['content'] = $tpl->fetch( 'design:comment/view/view.tpl' );
$Result['path'] = array( array( 'url' => false,
                            'text' => ezi18n( 'extension/ezcomments/view', 'Comment/View' ) ) );
return $Result;
?>