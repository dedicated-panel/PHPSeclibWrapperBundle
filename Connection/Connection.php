<?php

namespace DP\PHPSeclibWrapperBundle\Connection;

use DP\PHPSeclibWrapperBundle\Server\ServerInterface;
use DP\PHPSeclibWrapperBundle\Connection\Exception\IncompleteLoginIDException;
use DP\PHPSeclibWrapperBundle\Connection\Exception\ConnectionErrorException;

class Connection
{
    protected $server;
    protected $debug;
    
    protected $ssh;
    protected $sftp;
    
    public function __construct(ServerInterface $server, $debug = false)
    {
        $this->server = $server;
        $this->debug = $debug;
    }
    
    public function setDebug($debug)
    {
        $this->debug = $debug;
        
        return $this;
    }
    
    public function getDebug()
    {
        return $this->debug;
    }
    
    public function getSSH()
    {
        if (!isset($this->ssh)) {
            $ssh = new \Net_SSH2($this->server->getHost(), $this->server->getPort());
            
            $privateKey = $this->server->getPrivateKey();
            $password = $this->server->getPassword();
            
            if (!empty($privateKey)) {
                $login = $ssh->login($this->server->getUser(), $privateKey);
            }
            elseif (!empty($password)) {
                $login = $ssh->login($this->server->getUser(), $password);
            }
            else {
                throw new IncompleteLoginIDException($this);
            }
            
            if ($login === false) {
                throw new ConnectionErrorException($this);
            }
            
            $this->ssh = $ssh;
        }
        
        return $this->ssh;
    }
    
    public function getSFTP()
    {
        if (!isset($this->sftp)) {
            $sftp = new \Net_SFTP($this->server->getHost(), $this->server->getPort());
            
            $privateKey = $this->server->getPrivateKey();
            $password = $this->server->getPassword();
            
            if (!empty($privateKey)) {
                $login = $sftp->login($this->server->getUser(), $privateKey);
            }
            elseif (!empty($password)) {
                $login = $sftp->login($this->server->getUser(), $password);
            }
            else {
                throw new IncompleteLoginIDException($this);
            }
            
            if ($login === false) {
                throw new ConnectionErrorException($this);
            }
            
            $this->sftp = $sftp;
        }
        
        return $this->sftp;
    }
}
