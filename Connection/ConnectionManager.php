<?php

namespace DP\PHPSeclibWrapperBundle\Connection;

use Symfony\Component\DependencyInjection\ContainerAware;
use DP\PHPSeclibWrapperBundle\Connection\ConnectionManagerInterface;
use Psr\Log\LoggerInterface;
use DP\PHPSeclibWrapperBundle\Server\ServerInterface;
use DP\PHPSeclibWrapperBundle\Connection\ConnectionInterface;
use DP\PHPSeclibWrapperBundle\Connection\Connection;

class ConnectionManager extends ContainerAware implements ConnectionManagerInterface
{
    protected $connections;
    protected $debug;
    protected $logger;
    
    public function __construct(LoggerInterface $logger, $debug = false)
    {
        $this->servers = array();
        $this->connections = array();
        
        $this->debug = $debug;
        $this->logger = $logger;
    }
    
    public function getConnectionFromServer(ServerInterFace $server, $cid = 0)
    {
        $key = $this->getServerHash($server);
        
        if (!array_key_exists($key, $this->connections)) {
            $this->connections[$key] = array();
        }
        
        if (!isset($this->connections[$key][$cid]) || empty($this->connections[$key][$cid])) {
            $conn = new Connection($server, $this->logger, $this->debug);
            $conn->setConnectionId($cid);
            
            $this->connections[$key][$cid] = $conn;
        }
        
        return $this->connections[$key][$cid];
    }
    
    private function getServerHash(ServerInterface $server)
    {
        return $server->getUsername() . '@' . $server->getServerIP() . ':' . $server->getPort();
    }
    
    public function getConnectionId(ConnectionInterface $connection)
    {
        $server = $connection->getServer();
        $hash = $this->getServerHash($server);
        
        $ret = array_keys($this->connections[$hash], $connection, true);
        
        return (!empty($ret) ? array_pop($ret) : null);
    }
}
