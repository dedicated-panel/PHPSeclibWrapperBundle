<?php

namespace DP\PHPSeclibWrapperBundle\Connection;

use DP\PHPSeclibWrapperBundle\Server\ServerInterface;
use DP\PHPSeclibWrapperBundle\Connection\Exception\IncompleteLoginCredentialsException;
use DP\PHPSeclibWrapperBundle\Connection\Exception\ConnectionErrorException;

class Connection
{
    protected $server;
    protected $debug;
    protected $connectionId;
    
    protected $ssh;
    protected $sftp;
    
    /**
     * @param ServerInterface   $server Server representation containing informations about it
     * @param boolean           $debug  Indicates whether connection need to be in debug mode
     * 
     * @return Connection       Current instance, for method chaining
     */
    public function __construct(ServerInterface $server, $debug = false)
    {
        $this->server = $server;
        $this->debug = $debug;
        
        return $this;
    }
    
    /**
     * Interdit le clonage de l'objet
     */
    private function __clone() {}
    
    /**
     * Sets the debug mode
     * 
     * @param boolean $debug Indicates whether connection need to be in debug mode
     * 
     * @return Connection Current instance, for method chaining  
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
        
        return $this;
    }
    
    /**
     * Gets the debug mode
     * 
     * @return boolean Current debug mode for ssh/sftp connections
     */
    public function getDebug()
    {
        return $this->debug;
    }
    
    /**
     * Sets the connection id
     * 
     * @param integer $connectionId Connection Id
     * 
     * @return Connection Current instance, for method chaining
     */
    public function setConnectionId($connectionId)
    {
        $this->connectionId = $connectionId;
        
        return $this;
    }
    
    /**
     * Gets the connection id assigned by the manager
     * 
     * @return integer Connection id
     */
    public function getConnectionId()
    {
        return $this->connectionId;
    }
    
    /**
     * Gets the PHPSeclib SSH instance associated to this Connection instance.
     * If not already openned, we tried to connect with the server informations.
     * 
     * @throws IncompleteLoginCredentialsException  If no private key and no password are defined
     * @throws ConnectionErrorException             If the connection can't be done (mostly due to bad credentials or timeout)
     * 
     * @return \Net_SSH2        PHPSeclib SSH connection
     */
    public function getSSH()
    {
        if (!isset($this->ssh)) {
            $ssh = new \Net_SSH2($this->server->getHostname(), $this->server->getPort());
            
            $username = $this->server->getUsername();
            $password = $this->server->getPassword();
            $privateKey = $this->server->getPrivateKey();
            
            if (!empty($privateKey)) {
                $login = $ssh->login($username, $privateKey);
            }
            elseif (!empty($password)) {
                $login = $ssh->login($username, $password);
            }
            else {
                throw new IncompleteLoginCredentialsException($this->server);
            }
            
            if ($login === false) {
                throw new ConnectionErrorException($this->server);
            }
            
            $this->ssh = $ssh;
        }
        
        return $this->ssh;
    }

    /**
     * Gets the PHPSeclib SFTP instance associated to this Connection instance.
     * If not already openned, we tried to connect with the server informations.
     * 
     * @throws IncompleteLoginCredentialsException  If no private key and no password are defined
     * @throws ConnectionErrorException             If the connection can't be done (mostly due to bad credentials or timeout)
     * 
     * @return \Net_SFTP        PHPSeclib SFTP connection
     */    
    public function getSFTP()
    {
        if (!isset($this->sftp)) {
            $sftp = new \Net_SFTP($this->server->getHostname(), $this->server->getPort());
            
            $username = $this->server->getUsername();
            $password = $this->server->getPassword();
            $privateKey = $this->server->getPrivateKey();
            
            if (!empty($privateKey)) {
                $login = $sftp->login($username, $privateKey);
            }
            elseif (!empty($password)) {
                $login = $sftp->login($username, $password);
            }
            else {
                throw new IncompleteLoginCredentialsException($this->server);
            }
            
            if ($login === false) {
                throw new ConnectionErrorException($this->server);
            }
            
            $this->sftp = $sftp;
        }
        
        return $this->sftp;
    }
    
    /**
     * Executes a shell command on the server
     * 
     * @param string $cmd Command to execute
     * 
     * @return string Return the shell command output
     */
    public function exec($cmd)
    {
        $ret = $this->getSSH()->exec($cmd);
        $ret = trim($ret);
        
        return $ret;
    }
    
    /**
     * Verifies that we can access the server with server credentials
     * 
     * @return boolean Can we connect ?
     */
    public function connectionTest()
    {
        try {
            $echo = $this->exec('echo dedipanel');
            
            if (empty($echo) || $echo != 'dedipanel') {
                return false;
            }
        }
        catch (\Exception $e) {
            return false;
        }
        
        return true;
    }
}
