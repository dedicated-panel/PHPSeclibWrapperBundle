<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Tests\Functional;

use Dedipanel\PHPSeclibWrapperBundle\Connection\Connection;

class CrontabTest extends \PHPUnit_Framework_TestCase
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

        $mock
            ->expects($this->any())
            ->method('getPassword')
            ->will($this->returnValue(self::PASSWORD))
        ;

        return $mock;
    }

    public function mockLogger()
    {
        return $this->getMock('Psr\Log\NullLogger');
    }

    public function testAddingToCrontab()
    {
        $server = $this->mockServer();
        $logger = $this->mockLogger();

        $connection = new Connection($server, $logger);

        $this->assertEmpty($connection->getCrontab());
        $this->assertTrue($connection->updateCrontab('~/test.sh', 2));
        $this->assertNotEmpty($connection->getCrontab());
    }

    public function testRemovingFromCrontab()
    {
        $server = $this->mockServer();
        $logger = $this->mockLogger();

        $connection = new Connection($server, $logger);

        $this->assertTrue($connection->updateCrontab('~/test.sh', 2));
        $this->assertTrue($connection->removeFromCrontab('~/test.sh'));
        $this->assertEmpty($connection->getCrontab());
    }
}
