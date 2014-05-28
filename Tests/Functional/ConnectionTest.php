<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Tests\Functional;

use Dedipanel\PHPSeclibWrapperBundle\Connection\Connection;

class PasswordConnectionTest extends \PHPUnit_Framework_TestCase
{
    private $publicKey;

    const HOSTNAME = HOSTNAME;
    const PORT     = PORT;
    const USERNAME = USERNAME;
    const PASSWORD = PASSWORD;


    public function __construct()
    {
        $this->publicKey = file_get_contents(__DIR__ . '/../id_rsa.pub');
    }

    public function mockServer($fakeUser = false, $fakePassword = false, $fakeIp = false)
    {
        $mock = $this->getMock('Dedipanel\PHPSeclibWrapperBundle\Server\ServerInterface');

        $hostname = self::HOSTNAME;
        if ($fakeIp) {
            $hostname = '10.10.10.10';
        }

        $mock
            ->expects($this->any())
            ->method('getServerIP')
            ->will($this->returnValue($hostname))
        ;

        $mock
            ->expects($this->any())
            ->method('getPort')
            ->will($this->returnValue(self::PORT))
        ;

        $username = self::USERNAME;
        if ($fakeUser) {
            $username = 'test';
        }

        $mock
            ->expects($this->any())
            ->method('getUsername')
            ->will($this->returnValue($username))
        ;

        $passwd = self::PASSWORD;
        if ($fakePassword) {
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

    public function getConnection($fakeUser = false, $fakePassword = false, $fakeIp = false)
    {
        $server = $this->mockServer($fakeUser, $fakePassword, $fakeIp);
        $logger = $this->mockLogger();

        return new Connection($server, $logger);
    }

    public function testSSHConnectionWithCorrectPassword()
    {
        $conn = $this->getConnection();

        $this->assertTrue($conn->testSSHConnection());
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testConnectionWithBadIP()
    {
        $conn = $this->getConnection(false, false, true);
        $conn->testSSHConnection();
    }

    public function testConnectionWithBadUser()
    {
        $conn = $this->getConnection(true, false, false);
        $conn->testSSHConnection();
    }

    public function testSSHConnectionWithIncorrectPassword()
    {
        $conn = $this->getConnection(false, true);

        $this->assertFalse($conn->testSSHConnection());
    }

    public function testSFTPConnectionWithCorrectPassword()
    {
        $conn = $this->getConnection();

        $this->assertTrue($conn->testSFTPConnection());
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

    /**
     * @group sftp
     */
    public function testStat()
    {
        $conn = $this->getConnection();

        $this->assertNotEmpty($conn->stat('~/.ssh/authorized_keys'));
    }
}
