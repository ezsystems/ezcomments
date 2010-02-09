<?php
/**
 * File containing ezcomNotificationEmailManager class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

/**
 * Business logic for notification
 */
class ezcomNotificationEmailManager extends ezcomNotificationManager
{
    var $emailContentType = 'text/html';
    var $emailFrom = '';

    /**
     * construct
     * Load the configuration from ini file
     */
    public function __construct()
    {
        //load configuration from ini
        $ini = eZINI::instance( 'ezcomments.ini' );
        $this->emailContentType = $ini->variable( 'NotificationSettings', 'MailContentType' );
        $this->emailFrom = $ini->variable( 'NotificationSettings', 'MailFrom' );
    }

    /**
     * Execute sending process in Email
     * @see extension/ezcomments/classes/ezcomNotificationManager#executeSending($subject, $body, $subscriber)
     */
    public function executeSending( $subject, $body, $subscriber )
    {
        $email = $subscriber->attribute( 'email' );
        $parameters = array();
        $parameters['content_type'] = $this->emailContentType;
        $parameters['from'] = $this->emailFrom;
        $transport = eZNotificationTransport::instance( 'ezmail' );
        $result = $transport->send( array( $email ), $subject, $body, null, $parameters );
        if ( $result === false )
        {
            throw new Exception( 'Send email error! Subscriber id:' .$subscriber->attribute( 'id' ) );
        }
        eZDebugSetting::writeNotice( 'extension-ezcomments', "An email has been sent to '$email' (subject: $subject)", __METHOD__ );
    }
}