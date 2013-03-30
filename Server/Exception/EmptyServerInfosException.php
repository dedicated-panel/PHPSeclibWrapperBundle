<?php

namespace DP\PHPSeclibWrapperBundle\Server\Exception;

class EmptyServerInfosException extends \Exception
{
    public function __construct()
    {
        parent::__construct('You need to set the IP address or the hostname.');
    }
}
