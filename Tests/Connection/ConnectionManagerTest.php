<?php

namespace DP\PHPSeclibWrapperBundle\Tests\Connection;

use DP\PHPSeclibWrapperBundle\Connection\ConnectionManager;
use DP\PHPSeclibWrapperBundle\Server\Server;
use Psr\Log\NullLogger;

class ConnectionManagerTest extends \PHPUnit_Framework_TestCase
{
    public function getGoodServer()
    {
        $server = new Server;
        $server
            ->setHostname('localhost')
            ->setUsername('test')
            ->setPassword('test')
        ;
        
        return $server;
    }
    
    public function testGetConnFromServer()
    {
        $server = $this->getGoodServer();
        $manager = new ConnectionManager(new NullLogger);
        
        $conn = $manager->getConnectionFromServer($server);
        
        $this->assertEquals('DP\\PHPSeclibWrapperBundle\\Connection\\Connection', get_class($conn));
    }
    
    public function testGetMultipleConnFromManager()
    {
        $server = $this->getGoodServer();
        $manager = new ConnectionManager(new NullLogger);
        
        $conn1 = $manager->getConnectionFromServer($server);
        $conn2 = $manager->getConnectionFromServer($server);
        
        $this->assertEquals($conn1, $conn2);
    }
    
    public function testGetDifferentConnIdFromManager()
    {
        $server = $this->getGoodServer();
        $manager = new ConnectionManager(new NullLogger);
        
        $conn1 = $manager->getConnectionFromServer($server, 0);
        $conn2 = $manager->getConnectionFromServer($server, 0);
        
        $this->assertNotEquals($conn1, $conn2);
    }
    
    public function testGetConnId()
    {
        $server = $this->getGoodServer();
        $manager = new ConnectionManager(new NullLogger);
        
        $conn2 = $manager->getConnectionFromServer($server, 2);
        $conn1 = $manager->getConnectionFromServer($server, 1);
        
        $this->assertEquals($manager->getConnectionId($conn2), 2);
        $this->assertEquals($manager->getConnectionId($conn1), 1);
    }
}
