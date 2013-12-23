<?php

namespace DP\PHPSeclibWrapperBundle;

use DP\PHPSeclibWrapperBundle\Connection\OsSpecific\OsSpecificConnectionInterface;
use DP\PHPSeclibWrapperBundle\KeyStore\AbstractKeyStore;

/**
 * @author Albin Kerouanton
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
class KeyHelper
{
    /** @var AbstractKeyStore $store **/
    private $store;
    
    /**
     * @param AbstractKeyStore $store
     */
    public function __construct(AbstractKeyStore $store)
    {
        $this->store = $store;
    }
    
    /**
     * Creates key pair using the $connection on the underlying server
     * 
     * @param OsSpecificConnectionInterface $connection Opened connecton to the server for 
     *                                                  which we want to create a key
     * @param string $keyName Public/Private key name
     * 
     * @return string Public key
     */
    public function createKeyPair(OsSpecificConnectionInterface $connection, $keyName)
    {        
        $rsa = new \Crypt_RSA();
        $rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_OPENSSH);
        $pair = $rsa->createKey();
        
        $this->store->store($keyName, $pair['privatekey']);
        $this->connection->addKey($pair['publickey']);
        
        return $pair['publickey'];
    }
    
    /**
     * Deletes key pair using the $connection on the underlying server 
     * 
     * @param OsSpecificConnectionInterface $connection Opened connecton to the server for 
     *                                                  which we want to delete the key
     * @param string $keyName Public/Private key name
     * 
     * @return boolean
     */
    public function deleteKeyPair(OsSpecificConnectionInterface $connection, $keyName)
    {
        $this->connection->removeKey($this->store->get($keyName));
        $this->store->remove($keyName);
        
        return true;
    }
}
