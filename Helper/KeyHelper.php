<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Helper;

use Dedipanel\PHPSeclibWrapperBundle\Connection\ConnectionInterface;
use Dedipanel\PHPSeclibWrapperBundle\KeyStore\KeyStoreInterface;

/**
 * @author Albin Kerouanton
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
class KeyHelper
{
    /** @var \Dedipanel\PHPSeclibWrapperBundle\KeyStore\KeyStoreInterface $store **/
    private $store;

    /**
     * @param \Dedipanel\PHPSeclibWrapperBundle\KeyStore\KeyStoreInterface $store
     */
    public function __construct(KeyStoreInterface $store)
    {
        $this->store = $store;
        
        if (!$store->isInitialized()) {
            $store->initialize();
        }
    }

    /**
     * Creates key pair using the $connection on the underlying server
     * 
     * @param string $keyName Public/Private key name
     * @param \Dedipanel\PHPSeclibWrapperBundle\Connection\ConnectionInterface
     *        $connection Opened connecton to the server for
     *        which we want to create a key
     * 
     * @return string Public key
     */
    public function createKeyPair($keyName, ConnectionInterface $connection = null)
    {
        $rsa = new \Crypt_RSA();
        $rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_OPENSSH);
        $pair = $rsa->createKey();

        $this->store->store($keyName, $pair['privatekey']);

        if (!is_null($connection)) {
            $connection->addKey($pair['publickey']);
        }

        return $pair['publickey'];
    }

    /**
     * Deletes key pair using the $connection on the underlying server
     * 
     * @param string $keyName Public/Private key name
     * @param \Dedipanel\PHPSeclibWrapperBundle\Connection\ConnectionInterface
     *        $connection Opened connecton to the server for
     *        which we want to delete the key
     * 
     * @return boolean
     */
    public function deleteKeyPair($keyName, ConnectionInterface $connection = null)
    {
        $removed = $this->store->remove($keyName);

        if ($removed && !is_null($connection)) {
            $connection->removeKey($this->store->retrieve($keyName));
        }

        return $removed;
    }
}
