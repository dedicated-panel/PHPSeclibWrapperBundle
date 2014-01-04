<?php

namespace Dedipanel\PHPSeclibWrapperBundle;

use Dedipanel\PHPSeclibWrapperBundle\Connection\OsSpecific\OsSpecificConnectionInterface;
use Dedipanel\PHPSeclibWrapperBundle\KeyStore\AbstractKeyStore;

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
     * @param string                        $keyName    Public/Private key name
     *
     * @return string Public key
     */
    public function createKeyPair($keyName, OsSpecificConnectionInterface $connection = null)
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
     * @param OsSpecificConnectionInterface $connection Opened connecton to the server for
     *                                                  which we want to delete the key
     * @param string                        $keyName    Public/Private key name
     *
     * @return boolean
     */
    public function deleteKeyPair($keyName, OsSpecificConnectionInterface $connection = null)
    {
        $this->store->remove($keyName);

        if (!is_null($connection)) {
            $connection->removeKey($this->store->get($keyName));
        }

        return true;
    }
}
