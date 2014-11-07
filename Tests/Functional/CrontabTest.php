<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Tests\Functional;

use Dedipanel\PHPSeclibWrapperBundle\Connection\Connection;
use Dedipanel\PHPSeclibWrapperBundle\Crontab\Crontab;
use Dedipanel\PHPSeclibWrapperBundle\Crontab\CrontabItem;

/**
 * @group crontab
 */
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

        $this->assertEmpty($crontab->getItems());

        $item = new CrontabItem('~/test.sh', 2);

        $crontab->addItem($item);
        $this->assertTrue($crontab->update());
        $this->assertNotEmpty($crontab->getItems());

        $crontab->removeItem($item);
        $this->assertTrue($crontab->update());
        $this->assertEmpty($crontab->getItems());
    }

    public function testRetrieveContrab()
    {

        $conn = $this
            ->getMockBuilder('Dedipanel\PHPSeclibWrapperBundle\Connection\Connection')
            ->disableOriginalConstructor()
            ->getMock();
        $conn
            ->expects($this->once())
            ->method('exec')
            ->will($this->returnValue(<<<EOF
01 01 * * * /home/test/css1/hlds.sh restart
02 02 * * * /home/test/css2/hlds.sh restart >> /home/like/css2/cron-dp.log
EOF
        ));

        $crontab = new Crontab($conn);
        $items   = $crontab->getItems();

        $this->assertCount(2, $items);
        $this->assertEquals('/home/test/css1/hlds.sh restart', $items[0]->getCommand());
        $this->assertEquals('/home/test/css2/hlds.sh restart >> /home/like/css2/cron-dp.log', $items[1]->getCommand());
    }

    public function testRemoveItem()
    {
        $conn = $this
            ->getMockBuilder('Dedipanel\PHPSeclibWrapperBundle\Connection\Connection')
            ->disableOriginalConstructor()
            ->getMock();
        $crontab = new Crontab($conn);

        $obj1 = new CrontabItem('test');
        $obj2 = new CrontabItem('test');
        $this->assertNotEquals(spl_object_hash($obj1), spl_object_hash($obj2));

        $crontab->addItem($obj1);
        $this->assertNotEmpty($crontab->getItems());

        $crontab->removeItem($obj2);
        $this->assertEmpty($crontab->getItems());
    }
}
