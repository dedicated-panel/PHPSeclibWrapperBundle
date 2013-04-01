<?php

namespace DP\PHPSeclibWrapperBundle\Connection;

use DP\PHPSeclibWrapperBundle\Server\ServerInterface;
use DP\PHPSeclibWrapperBundle\Connection\Connection;
use Symfony\Component\DependencyInjection\ContainerAware;

class ConnectionManager extends ContainerAware
{
    protected $connections;
    protected $debug;
    
    public function __construct($debug = false)
    {
        $this->servers = array();
        $this->connections = array();
        
        $this->debug = $debug;
    }
    
    public function getConnectionFromServer(ServerInterFace $server, $cid = 0)
    {
        $key = $this->getServerHash($server);
        
        if (!array_key_exists($key, $this->connections)) {
            $this->connections[$key] = array();
        }
        
        if (!isset($this->connections[$key][$cid]) || empty($this->connections[$key][$cid])) {
            $conn = new Connection($server, $this->debug);
            $conn->setConnectionId($cid);
            
            $this->connections[$key][$cid] = $conn;
        }
        
        return $this->connections[$key][$cid];
    }
    
    public function getServerHash(ServerInterface $server)
    {
        return $server->getUsername() . '@' . $server->getServerIP() . ':' . $server->getPort();
    }
}
