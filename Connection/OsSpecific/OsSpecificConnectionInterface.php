<?php

namespace DP\PHPSeclibWrapperBundle\Connection;

use DP\PHPSeclibWrapperBundle\Connection\OsSpecific\Exception\MethodNotImplementedException;
use DP\PHPSeclibWrapperBundle\Connection\OsSpecific\Exception\UnavailableMethodException;

interface OsSpecificConnectionInterface
{
    /**
     * Creates $filepath or modify its modification time
     * 
     * @param $filepath string    File path of the file to create or to update
     * @param $mtime    \DateTime Mtime you want to set on the file
     * 
     * @throws 
     * 
     * @return boolean
     */
    public function touch($filepath, \DateTime $mtime = null);
}
