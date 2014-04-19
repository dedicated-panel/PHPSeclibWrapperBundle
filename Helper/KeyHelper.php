<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Helper;

use Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\KeyNotExistsException;
use Dedipanel\PHPSeclibWrapperBundle\KeyStore\KeyStoreInterface;
use Dedipanel\PHPSeclibWrapperBundle\Connection\ConnectionManagerInterface;
use Dedipanel\PHPSeclibWrapperBundle\Server\ServerInterface;

/**
 * @author Albin Kerouanton
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
class KeyHelper
{
    /** @var \Dedipanel\PHPSeclibWrapperBundle\KeyStore\KeyStoreInterface $store */
    private $store;

    /** @var \Dedipanel\PHPSeclibWrapperBundle\Connection\ConnectionManagerInterface  */
    private $manager;

    /**
     * @param ConnectionManagerInterface $manager
     * @param KeyStoreInterface $store
     */
    public function __construct(ConnectionManagerInterface $manager, KeyStoreInterface $store)
    {
        $this->manager = $manager;
        $this->store = $store;
        
        if (!$store->isInitialized()) {
            $store->initialize();
        }
    }

    /**
     * Create a key pair and upload the public key on the $server
     *
     * @param ServerInterface $server
     * @param integer         $bits
     */
    public function createKeyPair(ServerInterface $server, $bits = 1024)
    {
        // Generates a key pair
        $rsa = new \Crypt_RSA();
        $rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_OPENSSH);
        $pair = $rsa->createKey($bits);

        // Stores the private key
        $name = uniqid('', true);
        if (!$this->store->store($name, $pair['privatekey'])) {
            return false;
        }

        $server->setPrivateKeyName($name);
        $server->setPrivateKey($pair['privatekey']);

        // Finally upload the public key
        $conn = $this->manager->getConnectionFromServer($server);

        if (!$conn->addKey($pair['publickey'])) {
            return false;
        }

        return true;
    }

    /**
     * Delete private key file and remove the public key on the $server
     *
     * @param ServerInterface $server
     */
    public function deleteKeyPair(ServerInterface $server)
    {
        try {
            $pubkey = $server->getPrivateKey()->getPublicKey(CRYPT_RSA_PUBLIC_FORMAT_OPENSSH);

            $conn = $this->manager->getConnectionFromServer($server);
            $conn->removeKey($pubkey);

            // Finally removes the private key from the store
            $this->store->remove($server->getPrivateKeyName());
            $server->setPrivateKeyName(null);
            $server->setPrivateKey(null);
        }
        catch (KeyNotExistsException $e) {
            $server->setPrivateKeyName(null);
        }

        return true;
    }
}
