<?php

namespace DP\PHPSeclibWrapperBundle\Server\Exception;

class ServerIPv6HostException extends \Exception
{
    public function __construct($ip)
    {
        parent::__construct('You can\'t use an IPv6 address (' . $ip . ').');
    }
}
