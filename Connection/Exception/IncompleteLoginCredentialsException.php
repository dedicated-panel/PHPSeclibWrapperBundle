<?php

namespace DP\PHPSeclibWrapperBundle\Connection\Exception;

use DP\PHPSeclibWrapperBundle\Server\ServerInterface;

class IncompleteLoginCredentialsException extends \Exception
{
    public function __construct(ServerInterface $server)
    {
        parent::__construct('Incomplete login credentials for ' . $server->getHostname() . ':' . $server->getPort());
    }
}
