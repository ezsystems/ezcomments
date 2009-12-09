<?php
require_once( 'kernel/common/template.php' );

$commentList = ezcomComment::fetchForUser( 1 );

$tpl = templateInit();
$Result = array();
$tpl->setVariable( 'comment_list', $commentList );
$Result['content'] = $tpl->fetch( 'design:comment/notifications.tpl' );
?>