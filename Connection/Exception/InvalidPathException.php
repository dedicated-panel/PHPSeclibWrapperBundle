<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Connection\Exception;

class InvalidPathException extends \RuntimeException
{
    public function __construct($path)
    {
        parent::__construct('Invalid path "' . $path . '".');
    }
}
