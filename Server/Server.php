<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Server;

use Dedipanel\PHPSeclibWrapperBundle\Server\Exception\ServerIPv6HostException;
use Dedipanel\PHPSeclibWrapperBundle\Server\Exception\EmptyServerInfosException;
use Dedipanel\PHPSeclibWrapperBundle\Server\Exception\UnresolvedHostnameException;

/**
 * @author Albin Kerouanton
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
class Server implements ServerInterface
{
    protected $ip;
    protected $hostname;
    protected $port = 22;
    protected $username;
    protected $home;
    protected $password;
    protected $privateKey;
    protected $privateKeyName;
    
    
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

        return $this;
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
        if (!empty($this->hostname)) {
            return $this->resolveHostname();
        } elseif (empty($this->ip)) {
            throw new EmptyServerInfosException;
        }
        
        return $this->ip;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveHostname()
    {
        if (isset($this->hostname)) {
            // gethostbyname renvoie le hostname s'il n'a pas pu être résolu
            $ip = gethostbyname($this->hostname);

            if ($ip != $this->hostname) {
                return $ip;
            } else {
                throw new UnresolvedHostnameException($this->hostname);
            }
        }

        return null;
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
        if (is_string($privateKey)) {
            $privateKey = (new \Crypt_RSA())->loadKey($privateKey);
        }

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
    public function setPrivateKeyName($privateKeyName = null)
    {
        $this->privateKeyName = $privateKeyName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPrivateKeyName()
    {
        return $this->privateKeyName;
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

        return $this->username . '@' . $host;
    }
}
