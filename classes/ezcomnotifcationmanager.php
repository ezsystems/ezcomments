<?php
/**
 * File containing ezcomNotificationManger class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

/**
 * Business logic for notification
 */
abstract class ezcomNotificationManager
{
    public $subjectTemplatePath = 'design:comment/notification_subject.tpl';
    public $bodyTemplatePath = 'design:comment/notification_body.tpl';
    public $multiSubjectTemplatePath = 'design:comment/notification_multi_subject.tpl';
    public $multiBodyTemplatePath ='design:comment/notification_multi_body.tpl';

    protected static $instance;

    /**
    * Execute sending action. It can be in email, or notification in ez publish
    *
    * @param string $subject
    * @param string $body
    * @param ezcomSubscriber $subscriber
    * @return void
    */
    abstract public function executeSending( $subject, $body, $subscriber );

    /**
     * send one notification with one comment by one comment
     * Exception if error happens
     * @param $subscriber
     * @param $contentObject
     * @param $comment
     * @param $tpl
     * @return void
     */
    public function sendNotificationInMany( $subscriber, $contentObject, $comment, $tpl = null )
    {
         if ( is_null( $tpl ) )
         {
             require_once( 'kernel/common/template.php' );
             $tpl = templateInit();
         }
         $tpl->setVariable( 'subscriber', $subscriber );
         $tpl->setVariable( 'contentobject', $contentObject );
         $tpl->setVariable( 'comment', $comment );
         $subject = $tpl->fetch( $this->subjectTemplatePath );

         $body = $tpl->fetch( $this->bodyTemplatePath );
         $this->executeSending( $subject, $body, $subscriber );
    }

    /**
     * send notification with all comment in one notification
     * Exception if error happens
     * @param $subscriber
     * @param $contentObject
     * @param $commentList comment list to the subscriber, which can be null.
     * @param $tpl
     * @return void
     */
    public function sendNotificationInOne( $subscriber, $contentObject, $commentList = null, $tpl = null )
    {
         if ( is_null( $tpl ) )
         {
             require_once( 'kernel/common/template.php' );
             $tpl = templateInit();
         }
         $tpl->setVariable( 'subscriber', $subscriber );
         $tpl->setVariable( 'contentobject', $contentObject );
         if ( !is_null( $commentList ) )
         {
            $tpl->setVariable( 'comment_list', $commentList );
         }
         $subject = $tpl->fetch( $this->multiSubjectTemplatePath );
         $body = $tpl->fetch( $this->multiBodyTemplatePath );
         $this->executeSending( $subject, $body, $subscriber );
    }

    /**
     * create instance of the object
     * @param string $className
     * @return ezcomNotificationManager
     */
    public static function instance( $className = null )
    {
        if ( is_null( $className ) )
        {
            $ini = eZINI::instance( 'ezcomments.ini' );
            $className = $ini->variable( 'NotificationSettings', 'NotificationManagerClass' );
        }
        if ( !isset( self::$instance ) )
        {
            self::$instance = new $className();
        }
        return self::$instance;
    }

    /**
     * create instance of the object without using singleton
     * @param string $className
     * @return ezcomNotificationManager
     */
    public static function create( $className = null )
    {
        if ( is_null( $className ) )
        {
            $ini = eZINI::instance( 'ezcomments.ini' );
            $className = $ini->variable( 'NotificationSettings', 'NotificationManagerClass' );
        }
        return  new $className();
    }
}