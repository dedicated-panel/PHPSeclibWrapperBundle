<?php

namespace DP\PHPSeclibWrapperBundle\Connection;

use DP\PHPSeclibWrapperBundle\Server\ServerInterface;
use DP\PHPSeclibWrapperBundle\Connection\ConnectionInterface;

interface ConnectionManagerInterface
{
    /**
     * Retrieves a connection, or open it accordingly to the $server instance 
     * and $cid connection id
     * 
     * @param ServerInterface $server 
     * @param interger        $cid    Connection id identifying the connection to the $server
     * 
     * @return ConnectionInterface    Return an already opened connection, or one freshly opened
     */
    public function getConnectionFromServer(ServerInterface $server, $cid = 0);
    
    /**
     * Retrieves the connection id associated to $connection instance
     * 
     * @param ConnectionInterface $connection
     * 
     * @return integer|null
     */
    public function getConnectionId(ConnectionInterface $connection);
}
