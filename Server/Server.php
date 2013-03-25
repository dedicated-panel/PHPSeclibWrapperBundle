<?php

namespace DP\PHPSeclibWrapperBundle\Server;

use DP\PHPSeclibWrapperBundle\Server\ServerInterface;

class Server implements ServerInterface
{
    protected $host;
    protected $port;
    protected $username;
    protected $home;
    protected $password;
    protected $privateKey;
    
    /**
     * {@inheritdoc}
     */
    public function setHost($host)
    {
        $this->host = $host;
        
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getHost()
    {
        return $this->host;
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
