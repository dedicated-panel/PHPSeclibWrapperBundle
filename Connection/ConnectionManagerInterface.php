<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Connection;

use Dedipanel\PHPSeclibWrapperBundle\Server\ServerInterface;
use Dedipanel\PHPSeclibWrapperBundle\Connection\ConnectionInterface;

/**
 * @author Albin Kerouanton
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
interface ConnectionManagerInterface
{
    /**
     * Retrieves a connection, or open it accordingly to the $server instance
     * and $cid connection id
     *
     * @api
     *
     * @param ServerInterface $server
     * @param interger        $cid    0 if creating a new one or cid
     *
     * @return ConnectionInterface Return an already opened connection, or one freshly opened
     */
    public function getConnectionFromServer(ServerInterface $server, $cid = 1);

    /**
     * Retrieves the connection id associated to $connection instance
     *
     * @api
     *
     * @param ConnectionInterface $connection
     *
     * @return integer|null
     */
    public function getConnectionId(ConnectionInterface $connection);
}
