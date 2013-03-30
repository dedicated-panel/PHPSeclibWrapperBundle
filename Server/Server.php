<?php

namespace DP\PHPSeclibWrapperBundle\Server;

use DP\PHPSeclibWrapperBundle\Server\ServerInterface;
use DP\PHPSeclibWrapperBundle\Server\Exception\ServerIPv6HostException;
use DP\PHPSeclibWrapperBundle\Server\Exception\EmptyServerInfosException;
use DP\PHPSeclibWrapperBundle\Server\Exception\HostnameUnresolvedException;

class Server implements ServerInterface
{
    protected $ip;
    protected $hostname;
    protected $port;
    protected $username;
    protected $home;
    protected $password;
    protected $privateKey;
    
    
    /**
     * {@inheritdoc}
     */
    public function setIP($ip)
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            throw new ServerIPv6HostException($ip);
        }
        
        $this->ip = $ip;
        
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getIP()
    {
        return $this->ip;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * {@inheritdoc}
     */
     public function getServerIP()
     {
         if (!empty($this->ip)) {
             return $this->ip;
         }
         elseif (!empty($this->hostname)) {
             $ip = gethostbyname($this->hostname);
             
             if ($ip != $this->hostname) {
                 return $ip;
             }
             else {
                 throw new HostnameUnresolvedException($this->hostname);
             }
         }
         else {
             throw new EmptyServerInfosException;
         }
     }
     
    /**
     * {@inheritdoc}
     */
    public function setPort($port)
    {
        $this->port = $port;
        
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getPort()
    {
        return $this->port;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setUsername($username)
    {
        $this->username = $username;
        
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setHome($home)
    {
        $this->home = $home;
        
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getHome()
    {
        return $this->home;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setPassword($password)
    {
        $this->password = $password;
        
        return $this;
    }
    
    /*
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;
        
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }
}
