<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Connection;

use Symfony\Component\DependencyInjection\ContainerAware;
use Dedipanel\PHPSeclibWrapperBundle\Connection\ConnectionManagerInterface;
use Psr\Log\LoggerInterface;
use Dedipanel\PHPSeclibWrapperBundle\Server\ServerInterface;
use Dedipanel\PHPSeclibWrapperBundle\Connection\ConnectionInterface;
use Dedipanel\PHPSeclibWrapperBundle\Connection\Connection;

/**
 * @author Albin Kerouanton
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
class ConnectionManager extends ContainerAware implements ConnectionManagerInterface
{
    protected $connections;
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->servers = array();
        $this->connections = array();

        $this->logger = $logger;
    }

    public function getConnectionFromServer(ServerInterFace $server, $cid = 1)
    {
        $hash = $this->getServerHash($server);

        if (!isset($this->connections[$hash])) {
            $this->connections[$hash] = array();
        }

        if ($cid == 0) {
            $cid = (count($this->connections[$hash]) > 0 ? max(array_keys($this->connections[$hash])) + 1 : 1);
        }

        if (!isset($this->connections[$hash][$cid]) || empty($this->connections[$hash][$cid])) {
            $conn = new Connection($server, $this->logger);
            $conn->setConnectionId($cid);

            $this->connections[$hash][$cid] = $conn;
        }

        return $this->connections[$hash][$cid];
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
