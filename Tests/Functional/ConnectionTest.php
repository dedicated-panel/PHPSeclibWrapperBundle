<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Tests\Functional;

use Dedipanel\PHPSeclibWrapperBundle\Connection\Connection;

class PasswordConnectionTest extends \PHPUnit_Framework_TestCase
{
    const HOSTNAME = '127.0.0.1';
    const PORT     = 22;
    const USERNAME = 'dedipanel';
    const PASSWORD = 'dedipanel';

    public function mockServer($fake = false)
    {
        $mock = $this->getMock('Dedipanel\PHPSeclibWrapperBundle\Server\ServerInterface');

        $mock
            ->expects($this->any())
            ->method('getServerIP')
            ->will($this->returnValue(self::HOSTNAME))
        ;

        $mock
            ->expects($this->any())
            ->method('getPort')
            ->will($this->returnValue(self::PORT))
        ;

        $mock
            ->expects($this->any())
            ->method('getUsername')
            ->will($this->returnValue(self::USERNAME))
        ;

        $passwd = self::PASSWORD;
        if ($fake) {
            $passwd = 'fake';
        }

        $mock
            ->expects($this->any())
            ->method('getPassword')
            ->will($this->returnValue($passwd))
        ;

        return $mock;
    }

    public function mockLogger()
    {
        return $this->getMock('Psr\Log\NullLogger');
    }

    public function getConnection($fake = false)
    {
        $server = $this->mockServer($fake);
        $logger = $this->mockLogger();

        return new Connection($server, $logger);
    }

    public function testSSHConnectionWithCorrectPassword()
    {
        $conn = $this->getConnection();

        $this->assertTrue($conn->testSSHConnection());
    }

    public function testSSHConnectionWithIncorrectPassword()
    {
        $conn = $this->getConnection(true);

        $this->assertFalse($conn->testSSHConnection());
    }

    public function testSFTPConnectionWithCorrectPassword()
    {
        $conn = $this->getConnection();

        $this->assertTrue($conn->testSFTPConnection());
    }

    public function testSFTPConnectionWithIncorrectPassword()
    {
        $conn = $this->getConnection(true);

        $this->assertFalse($conn->testSFTPConnection());
    }

    public function testPacketStatus()
    {
        $conn = $this->getConnection();

        $this->assertTrue($conn->isInstalled('ifupdown'));
        $this->assertFalse($conn->isInstalled('test'));
    }

    public function testGetHome()
    {
        $conn = $this->getConnection();

        $this->assertEquals('/home/' . self::USERNAME, $conn->getHome());
    }

    public function testResolvePath()
    {
        $conn = $this->getConnection();

        $expected = '/home/' . self::USERNAME . '/test.sh';
        $this->assertEquals($expected, $conn->resolvePath('~/test.sh'));
    }
}
