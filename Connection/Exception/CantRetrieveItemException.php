<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Connection\Exception;

use Dedipanel\PHPSeclibWrapperBundle\SFTP\AbstractItem;
use Dedipanel\PHPSeclibWrapperBundle\SFTP\File;

class CantRetrieveItemException extends \RuntimeException
{
    public function __construct(AbstractItem $item)
    {
        if ($item instanceof File) {
            parent::__construct("Can't retrieve file content ($item).");
        }
        else {
            parent::__construct("Can't retrieve dir content ($item).");
        }
    }
}
