<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Server;

use Dedipanel\PHPSeclibWrapperBundle\Server\ServerInterface;
use Dedipanel\PHPSeclibWrapperBundle\Server\AbstractServer;
use Dedipanel\PHPSeclibWrapperBundle\Server\Exception\ServerIPv6HostException;
use Dedipanel\PHPSeclibWrapperBundle\Server\Exception\EmptyServerInfosException;
use Dedipanel\PHPSeclibWrapperBundle\Server\Exception\UnresolvedHostnameException;

/**
 * @author Albin Kerouanton
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
class Server extends AbstractServer
{
    protected $ip;
    protected $hostname;
    
    
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
        } else {
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
}
