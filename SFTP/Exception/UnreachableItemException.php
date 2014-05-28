<?php

namespace Dedipanel\PHPSeclibWrapperBundle\SFTP\Exception;

use Dedipanel\PHPSeclibWrapperBundle\SFTP\AbstractItem;

class UnreachableItemException extends \RuntimeException
{
    public function __construct(AbstractItem $item)
    {
        parent::__construct('SFTP item (' . $item->getFullPath() . ') is unreachable.');
    }
}
