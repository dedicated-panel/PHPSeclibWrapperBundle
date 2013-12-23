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
        /**
         * Les IPv6 ne sont, pour l'instant, pas géré
         */
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
            // gethostbyname renvoie le hostname s'il n'a pas pu être résolu
            $ip = gethostbyname($this->hostname);
            
            if ($ip != $this->hostname) {
                return $ip;
            }
            // Renvoie tout de même le hostname si celui-ci correspond à une IPv4
            elseif (filter_var($this->hostname, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false) {
                return $this->hostname;
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
    
    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        $hostname = $this->getHostname();
        $host = $this->getIP();
        
        if (!empty($hostname)) {
            $host = $hostname;
        }
        
        return $this->user . '@' . $host;
    }
}
