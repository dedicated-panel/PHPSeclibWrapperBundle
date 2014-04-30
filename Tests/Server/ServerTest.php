<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Tests\Server;

use Dedipanel\PHPSeclibWrapperBundle\Server\Server;

class ServerTest extends \PHPUnit_Framework_TestCase
{
    private $privateKey;

    public function __construct()
    {
        $this->privateKey = file_get_contents(__DIR__ . '/../id_rsa');
    }

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
        $server->setUsername('dedipanel');
        $server->setIP('8.8.8.8');
        $server->setHostname('google.fr');
        
        $this->assertNotEquals('8.8.8.8', $server->getServerIP());
        $this->assertEquals('dedipanel@google.fr', strval($server));
        
        $server->setHostname(null);
        $this->assertEquals('8.8.8.8', $server->getServerIP());
        $this->assertNotEquals('dedipanel@google.fr', strval($server));
    }
    
    /**
     * @expectedException \Dedipanel\PHPSeclibWrapperBundle\Server\Exception\EmptyServerInfosException
     */
    public function testGestServerIPEmptyInfos()
    {
        $server = new Server();
        $server->getServerIP();
    }

    public function testConvertStringToKey()
    {
        $server = new Server();
        $server->setPrivateKey($this->privateKey);

        $this->assertInstanceOf('\Crypt_RSA', $server->getPrivateKey());
    }
}
