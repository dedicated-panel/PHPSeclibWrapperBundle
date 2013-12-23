<?php

namespace DP\PHPSeclibWrapperBundle\Connection\OsSpecific;

use DP\PHPSeclibWrapperBundle\Connection\Connection;
use DP\PHPSeclibWrapperBundle\Connection\OsSpecific\OsSpecificConnectionInterface;

class DebianConnection extends Connection implements OsSpecificConnectionInterface
{
    protected $home;
    
    /**
     * @{inheritDoc}
     */
    public function getHome()
    {
        if (!isset($this->home)) {
            $this->home = $this->exec('cd ~ && pwd');
        }
        
        return $this->home;
    }
    
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
        
        return $this->exec($cmd) == '';
    }
    
    /**
     * @{inheritDoc}
     */
    public function createDirectory($dirpath)
    {
        return $this->exec('mkdir ' . $dirpath) == '';
    }
    
    /**
     * @{inheritDoc}
     */
    public function is64BitSystem()
    {
        return strlen($this->exec('uname -r | grep "\-64"')) > 0;
    }
    
    /**
     * @{inheritDoc}
     */
    public function isInstalled($packet)
    {
        $ret = trim($this->exec('dpkg-query -W --showformat=\'${Status}\n\' ' . $packet . ' | grep \'install ok installed\''));
        
        return $ret == 'install ok installed';
    }
    
    /**
     * @{inheritDoc}
     */
    public function isJavaInstalled()
    {
        return strlen($this->exec('type java 2>/dev/null')) > 0;
    }
    
    /**
     * @{inheritDoc}
     */
    public function hasCompatLib()
    {
        // On récupère la version du système debian utilisé
        // puisque le paquet à vérifier diffère avec la debian wheezy
        $os_version = floatval(trim($this->exec('cat /etc/debian_version')));
        
        if ($os_version >= 7) {
            return $this->isInstalled('libc6:i386');
        }
        else {
            return $this->isInstalled('ia32-libs');
        }
    }
    
    /**
     * @{inheritDoc}
     */
    public function getScreenContent($screenName)
    {
        $tmpFile = '/tmp/' . uniqid();
        $cmd = 'screen -S "' . $screenName . '" -X hardcopy ' . $tmpFile . '; sleep 1s;';
        $cmd .= 'if [ -e ' . $tmpFile . ' ]; then cat ' . $tmpFile . '; rm -f ' . $tmpFile . '; fi';
        
        return $this->exec($cmd);
    }
}