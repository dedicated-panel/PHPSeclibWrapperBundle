<?php

namespace DP\PHPSeclibWrapperBundle\Connection\Exception;

use DP\PHPSeclibWrapperBundle\Server\ServerInterface;

class ConnectionErrorException extends \exception
{
    public function __construct(ServerInterface $server)
    {
        parent::__construct('Connection to ' . $server->getUsername() . '@' . $server->getHostname() . ':' . $server->getPort() . ' failed.');
    }
}
