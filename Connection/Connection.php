<?php

namespace DP\PHPSeclibWrapperBundle\Connection;

use DP\PHPSeclibWrapperBundle\Server\ServerInterface;
use DP\PHPSeclibWrapperBundle\Connection\Exception\IncompleteLoginCredentialsException;
use DP\PHPSeclibWrapperBundle\Connection\Exception\ConnectionErrorException;
use Psr\Log\LoggerInterface;

class Connection
{
    protected $server;
    protected $logger;
    protected $debug;
    
    protected $ssh;
    protected $sftp;
    
    /**
     * @param ServerInterface   $server Server representation containing informations about it
     * @param LoggerInterface   $logger The logger instance used for logging error and debug messages
     * @param boolean           $debug  Indicates whether connection need to be in debug mode
     * 
     * @return Connection       Current instance, for method chaining
     */
    public function __construct(ServerInterface $server, LoggerInterface $logger, $debug = false)
    {
        $this->server = $server;
        $this->logger = $logger;
        $this->debug = $debug;
        
        return $this;
    }
    
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
     * Execute $cmd through the PHPSeclib ssh instance
     * 
     * @param string $cmd SSH command to execute
     * 
     * @return string
     */
    public function exec($cmd)
    {
        if ($this->debug) {
            $this->logger->debug("Envoi de la commande \"$cmd\" sur le serveur $this->server.");
        }
        
        $ret = $this->getSSH()->exec($cmd);
        $ret = trim($ret);
        
        if ($this->debug) {
            $this->logger->debug("Retour de la commande \"$cmd\" : \"$ret\".");
        }
        
        return $ret;
    }
}
