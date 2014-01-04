<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Server\Exception;

/**
 * @author Albin Kerouanton
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
class HostnameUnresolvedException extends \Exception
{
    public function __construct($host)
    {
        parent::__construct('The "' . $host . '" hostname can\'t be resolved.');
    }
}
