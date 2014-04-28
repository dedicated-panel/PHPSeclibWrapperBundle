<?php

namespace Dedipanel\PHPSeclibWrapperBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Dedipanel\PHPSeclibWrapperBundle\Server\Server;
use Dedipanel\PHPSeclibWrapperBundle\Connection\Exception\ConnectionErrorException;

/**
 * @author Albin Kerouanton
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
class DedipanelPHPSeclibWrapperBundle extends Bundle
{
    public function boot()
    {
        set_error_handler(array($this, 'errorHandler'), E_USER_NOTICE | E_USER_ERROR | E_USER_WARNING);
    }

    public static function errorHandler($errno, $errstr, $errfile, $errline, $errcontext)
    {
        if ($errno == E_USER_NOTICE
        && (strpos($errstr, 'Error 110. Connection timed out') !== false
        || strpos($errstr, 'Network is unreachable') !== false
        || strpos($errstr, 'Connection closed prematurely') !== false)) {
            $server = null;

            if (isset($errcontext['host'])) {
                list($ip,$port) = explode(':', $errcontext['host']);
                $server = new Server();
                $server->setIp($ip)->setPort($port);
            }

            throw new ConnectionErrorException($server);
        }
    }
}
