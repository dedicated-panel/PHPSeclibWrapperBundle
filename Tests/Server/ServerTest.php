<?php

namespace DP\PHPSeclibWrapperBundle\Tests\Server;

use DP\PHPSeclibWrapperBundle\Server\Server;

class ServerTest extends \PHPUnit_Framework_TestCase
{
    public function testIPv4Host()
    {
        $server = $this->getServer();
        $this->assertNull($server->getIP());
        
        $server->setIP('127.0.0.1');
        $this->assertEquals('127.0.0.1', $server->getIP());
    }
    
    public function testIPv6HostRestriction()
    {
        $this->setExpectedException('DP\PHPSeclibWrapperBundle\Server\Exception\ServerIPv6HostException');
        
        $server = $this->getServer();
        $this->assertNull($server->getIP());
        
        $server->setIP('::1');
    }
    
    public function testHostname()
    {        
        $server = $this->getServer();
        $this->assertNull($server->getHostname());        
        $server->setHostname('www.dedicated-panel.net');
        $this->assertEquals('www.dedicated-panel.net', $server->getHostname());
    }
    
    public function testBadHostnameResolution()
    {
        $this->setExpectedException('DP\PHPSeclibWrapperBundle\Server\Exception\HostnameUnresolvedException');
        
        $server = $this->getServer();
        $this->assertNull($server->getHostname());
        $this->assertNull($server->getIP());
        
        $server->setHostname('test.test');
        $this->assertEquals('test.test', $server->getHostname());
        
        // Gets the server info for exception testing
        $server->getServerIP();
    }
    
    public function testEmptyConnectionInfo()
    {
        $this->setExpectedException('DP\PHPSeclibWrapperBundle\Server\Exception\EmptyServerInfosException');
        
        $server = $this->getServer();
        $this->assertNull($server->getHostname());
        $this->assertNull($server->getIP());
        
        // Gets the server info for exception testing
        $server->getServerIP();
    }
    
    public function testHostnameResolution()
    {        
        $server = $this->getServer();
        $this->assertNull($server->getHostname());
        
        $server->setHostname('localhost');
        $this->assertEquals('localhost', $server->getHostname());
        $this->assertEquals('127.0.0.1', $server->getServerIP());
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

        $server->setUsername('test');
        $this->assertEquals('test', $server->getUsername());
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
