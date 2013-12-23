<?php

namespace DP\PHPSeclibWrapperBundle\Server\Exception;

/**
 * @author Albin Kerouanton
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
class EmptyServerInfosException extends \Exception
{
    public function __construct()
    {
        parent::__construct('You need to set the IP address or the hostname.');
    }
}
