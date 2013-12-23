<?php

namespace DP\PHPSeclibWrapperBundle\Server\Exception;

/**
 * @author Albin Kerouanton
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
class ServerIPv6HostException extends \Exception
{
    public function __construct($ip)
    {
        parent::__construct('You can\'t use an IPv6 address (' . $ip . ').');
    }
}
