<?php
/**
 * File containing ezcomUtilityTest class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 */
class ezcomUtilityTest extends ezpDatabaseTestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "ezcomUtility object test" );
    }

    public function setUp()
    {
        parent::setUp();
    }

    public function testValidateURLString()
    {
        $url1 = 'javascript:alert();';
        $url2 = 'mailto:xc@ez.no';
        $url3 = 'JavaScript:alert()';
        $url4 = 'mAilto:xc@ez.no';
        $url5 = 'http://ez.no';

        $result = ezcomUtility::validateURLString( $url1 );
        $this->assertInternalType( 'string', $result );

        $result = ezcomUtility::validateURLString( $url2 );
        $this->assertInternalType( 'string', $result );

        $result = ezcomUtility::validateURLString( $url3 );
        $this->assertInternalType( 'string', $result );

        $result = ezcomUtility::validateURLString( $url4 );
        $this->assertInternalType( 'string', $result );

        $result = ezcomUtility::validateURLString( $url5 );
        $this->assertSame( true, $result );
    }
}
?>
