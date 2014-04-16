<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Tests\Functional\Connection;

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

    public function testSSHConnectionWithCorrectPassword()
    {
        $server = $this->mockServer();
        $logger = $this->mockLogger();

        $connection = new Connection($server, $logger);

        $this->assertTrue($connection->testSSHConnection());
    }

    public function testSSHConnectionWithIncorrectPassword()
    {
        $server = $this->mockServer(true);
        $logger = $this->mockLogger();

        $connection = new Connection($server, $logger);

        $this->assertFalse($connection->testSSHConnection());
    }

    public function testSFTPConnectionWithCorrectPassword()
    {
        $server = $this->mockServer();
        $logger = $this->mockLogger();

        $connection = new Connection($server, $logger);

        $this->assertTrue($connection->testSFTPConnection());
    }

    public function testSFTPConnectionWithIncorrectPassword()
    {
        $server = $this->mockServer(true);
        $logger = $this->mockLogger();

        $connection = new Connection($server, $logger);

        $this->assertFalse($connection->testSFTPConnection());
    }
}
