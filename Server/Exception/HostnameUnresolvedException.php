<?php

namespace DP\PHPSeclibWrapperBundle\Server\Exception;

class HostnameUnresolvedException extends \Exception
{
    public function __construct($host)
    {
        parent::__construct('The "' . $host . '" hostname can\'t be resolved.');
    }
}
