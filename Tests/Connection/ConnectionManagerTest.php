<?php

namespace DP\PHPSeclibWrapperBundle\Tests\Connection;

use DP\PHPSeclibWrapperBundle\Connection\ConnectionManager;
use DP\PHPSeclibWrapperBundle\Server\Server;

class ConnectionManagerTest extends \PHPUnit_Framework_TestCase
{
    public function getGoodServer()
    {
        $server = new Server;
        $server->setHostname(SERVER_HOST);
        $server->setPort(SERVER_PORT);
        $server->setUsername(SERVER_USER);
        $server->setPassword(SERVER_PASSWD);
        
        return $server;
    }
    
    public function testGetConnFromServer()
    {
        $server = $this->getGoodServer();
        $manager = new ConnectionManager;
        
        $conn = $manager->getConnectionFromServer($server);
        
        $this->assertEquals('DP\\PHPSeclibWrapperBundle\\Connection\\Connection', get_class($conn));
    }
    
    public function testGetMultipleConnFromManager()
    {
        $server = $this->getGoodServer();
        $manager = new ConnectionManager;
        
        $conn1 = $manager->getConnectionFromServer($server);
        $conn2 = $manager->getConnectionFromServer($server);
        
        $this->assertEquals($conn1, $conn2);
    }
    
    public function testGetDifferentConnIdFromManager()
    {
        $server = $this->getGoodServer();
        $manager = new ConnectionManager;
        
        $conn1 = $manager->getConnectionFromServer($server, 1);
        $conn2 = $manager->getConnectionFromServer($server, 2);
        
        $this->assertNotEquals($conn1, $conn2);
    }
}
