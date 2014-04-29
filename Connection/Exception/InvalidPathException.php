<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Connection\Exception;

class InvalidPathException extends \RuntimeException
{
    public function __construct($action, $path)
    {
        parent::__construct("Can't $action the path: $path");
    }
}
