<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Server;

use Dedipanel\PHPSeclibWrapperBundle\Server\ServerInterface;

/**
 * @author Albin Kerouanton
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
abstract class AbstractServer implements ServerInterface
{
    protected $port = 22;
    protected $username;
    protected $home;
    protected $password;
    protected $privateKey;
    
    
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
