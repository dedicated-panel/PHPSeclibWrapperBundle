<?php

namespace DP\PHPSeclibWrapperBundle\Connection\Exception;

use DP\PHPSeclibWrapperBundle\Server\ServerInterface;

class IncompleteLoginIDException extends \Exception
{
    public function __construct(ServerInterface $server)
    {
        parent::__construct('Incomplete login IDs for ' . $server->getUser() . '@' . $server->getHost() . ':' . $server->getPort());
    }
}
