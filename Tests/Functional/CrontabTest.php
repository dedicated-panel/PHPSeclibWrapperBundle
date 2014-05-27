<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Tests\Functional;

use Dedipanel\PHPSeclibWrapperBundle\Connection\Connection;
use Dedipanel\PHPSeclibWrapperBundle\Crontab\Crontab;
use Dedipanel\PHPSeclibWrapperBundle\Crontab\CrontabItem;

class CrontabTest extends \PHPUnit_Framework_TestCase
{
    const HOSTNAME = HOSTNAME;
    const PORT     = PORT;
    const USERNAME = USERNAME;
    const PASSWORD = PASSWORD;

    public function setUp()
    {
        $server = $this->mockServer();
        $logger = $this->mockLogger();

        $connection = new Connection($server, $logger);
        $connection->exec('echo "" | crontab -');
    }

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

    public function testCrontab()
    {
        $server = $this->mockServer();
        $logger = $this->mockLogger();

        $connection = new Connection($server, $logger);
        $crontab = new Crontab($connection);

        $item = new CrontabItem('~/test.sh', 2);

        $this->assertEmpty($crontab->getItems());

        $crontab->addItem($item);
        $this->assertTrue($crontab->update());
        $this->assertNotEmpty($crontab->getItems());

        $crontab->removeItem($item);
        $this->assertTrue($crontab->update());
        $this->assertEmpty($crontab->getItems());
    }
}
