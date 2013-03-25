<?php

namespace DP\PHPSeclibWrapperBundle\Tests\Server;

use DP\PHPSeclibWrapperBundle\Server\Server;

class ServerTest extends \PHPUnit_Framework_TestCase
{
    public function testHost()
    {
        $server = $this->getServer();
        $this->assertNull($server->getHost());
        var_dump($server->getHost());
        $server->setHost('127.0.0.1');
        
        $this->assertEquals('127.0.0.1', $server->getHost());
    }
    
    public function testPort()
    {
        $server = $this->getServer();
        $this->assertNull($server->getPort());
        
        $server->setPort(22);
        $this->assertEquals(22, $server->getPort());
    }
    
    public function testUsername()
    {        
        $server = $this->getServer();
        $this->assertNull($server->getUsername());

        $server->setUsername('tony');
        $this->assertEquals('tony', $server->getUsername());
    }
    
    public function testHome()
    {
        $server = $this->getServer();
        $this->assertNull($server->getHome());
        
        $server->setHome('/home/test/');
        $this->assertEquals('/home/test/', $server->getHome());
    }
    
    public function testPassword()
    {
        $server = $this->getServer();
        $this->assertNull($server->getPassword());
        
        $server->setPassword('test');
        $this->assertEquals('test', $server->getPassword());
    }
    
    public function testPrivateKey()
    {
        $server = $this->getServer();
        $this->assertNull($server->getPrivateKey());
        
        $server->setPrivateKey('test');
        $this->assertEquals('test', $server->getPrivateKey());
    }
    
    protected function getServer()
    {
        return new Server();
    }
}
