<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Tests\Server;

use Dedipanel\PHPSeclibWrapperBundle\Server\Server;

class ServerTest extends \PHPUnit_Framework_TestCase
{
    public function testResolveGoodHostname()
    {
        $server = new Server();
        $server->setHostname('localhost');
        
        $this->assertEquals('127.0.0.1', $server->resolveHostname());
    }
    
    /**
     * @expectedException \Dedipanel\PHPSeclibWrapperBundle\Server\Exception\UnresolvedHostnameException
     */
    public function testResolveBadHostname()
    {
        $server = new Server();
        $server->setHostname('hostname.unknown');
        
        $server->resolveHostname();
    }
    
    /**
     * @expectedException \Dedipanel\PHPSeclibWrapperBundle\Server\Exception\UnresolvedHostnameException
     */
    public function testGetServerIPBadHostname()
    {
        $server = new Server();
        $server->setHostname('hostname.unknown');
        
        $server->getServerIP();
    }
    
    public function testGetServerIPHierarchy()
    {
        $server = new Server();
        $server->setIP('8.8.8.8');
        $server->setHostname('www.google.fr');
        
        $this->assertEquals('8.8.8.8', $server->getServerIP());
        
        $server->setIP(null);
        $this->assertNotEquals('8.8.8.8', $server->getServerIP());
    }
    
    /**
     * @expectedException \Dedipanel\PHPSeclibWrapperBundle\Server\Exception\EmptyServerInfosException
     */
    public function testGestServerIPEmptyInfos()
    {
        $server = new Server();
        $server->getServerIP();
    }
}
