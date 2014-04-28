<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Connection\Exception;

use Dedipanel\PHPSeclibWrapperBundle\Server\ServerInterface;

/**
 * @author Albin Kerouanton
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
class ConnectionErrorException extends \RuntimeException
{
    public function __construct(ServerInterface $server = null)
    {
        if (is_null($server)) {
            parent::__construct('Connection failed.');
        }
        else {
            parent::__construct('Connection to ' . $server->getUsername() . '@' . $server->getServerIP() . ':' . $server->getPort() . ' failed.');
        }
    }
}
