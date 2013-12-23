<?php

namespace DP\PHPSeclibWrapperBundle\Connection\OsSpecific;

use DP\PHPSeclibWrapperBundle\Connection\Connection;
use DP\PHPSeclibWrapperBundle\Connection\OsSpecific\OsSpecificConnectionInterface;

class DebianConnection extends Connection implements OsSpecificConnectionInterface
{
    /**
     * @{inheritDoc}
     */
    public function touch($filepath, \DateTime $mtime = null)
    {
        $cmd = 'touch ' . $filepath;
        
        if (!is_null($mtime)) {
            $mtime = $mtime->format('Ymdhi.s');
            
            $cmd = 'touch -t ' . $mtime . ' ' . $filepath; 
        }
        
        // La commande ne renvoie rien
        return $this->getSSH()->exec($cmd) == '';
    }
}
