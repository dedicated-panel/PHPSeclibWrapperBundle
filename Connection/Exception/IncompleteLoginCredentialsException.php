<?php

namespace DP\PHPSeclibWrapperBundle\Connection\Exception;

use DP\PHPSeclibWrapperBundle\Server\ServerInterface;

/**
 * @author Albin Kerouanton
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
class IncompleteLoginCredentialsException extends \Exception
{
    public function __construct(ServerInterface $server)
    {
        parent::__construct('Incomplete login credentials for ' . $server->getHostname() . ':' . $server->getPort());
    }
}
