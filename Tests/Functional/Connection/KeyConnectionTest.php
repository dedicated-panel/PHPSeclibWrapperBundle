<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Tests\Functional\Connection;

use Dedipanel\PHPSeclibWrapperBundle\Connection\Connection;

class KeyConnectionTest extends \PHPUnit_Framework_TestCase
{
    const HOSTNAME = '127.0.0.1';
    const PORT     = 22;
    const USERNAME = 'dedipanel';

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

        $key = '';
        if (!$fake) {
            $key = file_get_contents(__DIR__ . '/../../id_rsa');
        }

        $mock
            ->expects($this->any())
            ->method('getPassword')
            ->will($this->returnValue(null))
        ;

        $mock
            ->expects($this->any())
            ->method('getPrivateKey')
            ->will($this->returnValue($key))
        ;

        return $mock;
    }

    public function mockLogger()
    {
        return $this->getMock('Psr\Log\NullLogger');
    }

    public function testSSHConnectionWithCorrectKey()
    {
        $server = $this->mockServer();
        $logger = $this->mockLogger();

        $connection = new Connection($server, $logger);

        $this->assertTrue($connection->testSSHConnection());
    }

    public function testSSHConnectionWithIncorrectKey()
    {
        $server = $this->mockServer(true);
        $logger = $this->mockLogger();

        $connection = new Connection($server, $logger);

        $this->assertFalse($connection->testSSHConnection());
    }

    public function testSFTPConnectionWithCorrectKey()
    {
        $server = $this->mockServer();
        $logger = $this->mockLogger();

        $connection = new Connection($server, $logger);

        $this->assertTrue($connection->testSFTPConnection());
    }

    public function testSFTPConnectionWithIncorrectKey()
    {
        $server = $this->mockServer(true);
        $logger = $this->mockLogger();

        $connection = new Connection($server, $logger);

        $this->assertFalse($connection->testSFTPConnection());
    }
}
