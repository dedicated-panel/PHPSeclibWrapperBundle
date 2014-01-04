<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Server;

/**
 * @author Albin Kerouanton
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
interface ServerInterface
{
    /**
     * Sets the host IP
     *
     * @param int $ip IP address
     *
     * @return \Dedipanel\PHPSeclibWrapperBundle\Server\ServerInterface
     */
    public function setIP($ip);

    /**
     * Gets the host IP
     *
     * @return int
     */
    public function getIP();

    /**
     * Sets the hostname
     *
     * @param string $hostname Server hostname
     *
     * @return \Dedipanel\PHPSeclibWrapperBundle\Server\ServerInterface
     */
    public function setHostname($hostname);

    /**
     * Gets the hostname
     *
     * @return string
     */
    public function getHostname();

    /**
     * Gets the server IP from $ip (if set) or from $host (resolved)
     *
     * @return string
     */
    public function getServerIP();

    /**
     * Resolves the hostname
     *
     * @throws \Dedipanel\PHPSeclibWrapperBundle\Server\Exception\UnresolvedHostnameException
     *
     * @return string|null Return the hostname (resolved)
     */
    public function resolveHostname();

    /**
     * Sets the port
     *
     * @param integer $port SSH Port
     *
     * @return \Dedipanel\PHPSeclibWrapperBundle\Server\ServerInterface
     */
    public function setPort($port);

     /**
      * Gets the port
      *
      * @return integer
      */
     public function getPort();

      /**
       * Sets the SSH username
       *
       * @param string $username Username
       *
       * @return \Dedipanel\PHPSeclibWrapperBundle\Server\ServerInterface
       */
      public function setUsername($username);

      /**
       * Gets the SSH username
       *
       * @return string
       */
      public function getUsername();

      /**
       * Sets the user home dir
       *
       * @param string $home Absolute home dir path
       *
       * @return \Dedipanel\PHPSeclibWrapperBundle\Server\ServerInterface
       */
      public function setHome($home);

      /**
       * Gets the user home dir (absolute path)
       *
       * @return string
       */
      public function getHome();

      /**
       * Sets the SSH user password
       *
       * @param string $password User password
       *
       * @return \Dedipanel\PHPSeclibWrapperBundle\Server\ServerInterface
       */
      public function setPassword($password);

      /**
       * Gets the user password
       *
       * @return string
       */
      public function getPassword();

      /**
       * Sets the private key for SSH authent
       *
       * @param string $privateKey Private key used for ssh/sftp connections
       *
       * @return \Dedipanel\PHPSeclibWrapperBundle\Server\ServerInterface
       */
      public function setPrivateKey($privateKey);

      /**
       * Gets the private key
       *
       * @return string
       */
      public function getPrivateKey();

      /**
       * Gets the server string representation
       */
      public function __toString();
}
