<?php
//
// Created on: <19-Jan-2010 15:49:00 xc>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Comments extension for eZ Publish
// SOFTWARE RELEASE: 1.0-1
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
 * Business logic for notification 
 */
class ezcomNotificationEmailManager extends ezcomNotificationManager
{
    var $emailContentType = 'text/html';
    var $emailFrom = '';
    
    
    protected function executeSending( $subject, $body, $subscriber )
    {
        $email = $subscriber->attribute( 'email' );
        $parameters = array();
        $parameters['content_type'] = $this->emailContentType;
        $parameters['from'] = $this->emailFrom;
        $transport = eZNotificationTransport::instance( 'ezmail' );
        $result = $transport->send( array( $email ), $subject, $body, null, $parameters );
        if( $result === false )
        {
            throw new Exception( 'Send email error! Subscriber id:' .$subscriber->attribute( 'id' ) );
        }
        eZDebug::writeNotice( 'An email has been sent to:'. $email . '.subject:' . $subject, 'Send mail' );
    }
}