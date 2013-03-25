<?php

namespace DP\PHPSeclibWrapperBundle\Server;

interface ServerInterface
{
    /**
     * Sets the host IP
     * 
     * @param string $host IP address
     * 
     * @return \DP\PHPSeclibWrapperBundle\Server\ServerInterface 
     */
    public function setHost($host);
    
    /**
     * Gets the host IP
     * 
     * @return string
     */
    public function getHost();
    
    /**
     * Sets the port
     * 
     * @param integer $port SSH Port
     * 
     * @return \DP\PHPSeclibWrapperBundle\Server\ServerInterface
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
       * @return \DP\PHPSeclibWrapperBundle\Server\ServerInterface
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
       * @return \DP\PHPSeclibWrapperBundle\Server\ServerInterface
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
       * @return \DP\PHPSeclibWrapperBundle\Server\ServerInterface
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
       * @return \DP\PHPSeclibWrapperBundle\Server\ServerInterface
       */
      public function setPrivateKey($privateKey);
      
      /**
       * Gets the private key
       * 
       * @return string
       */
      public function getPrivateKey();
}
