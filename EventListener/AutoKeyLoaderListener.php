<?php

namespace Dedipanel\PHPSeclibWrapperBundle\EventListener;

use Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\BaseKeyStoreException;
use Dedipanel\PHPSeclibWrapperBundle\KeyStore\KeyStoreInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Dedipanel\PHPSeclibWrapperBundle\Server\ServerInterface;

class AutoKeyLoaderListener
{
    private $store;

    public function __construct(KeyStoreInterface $store)
    {
        $this->store = $store;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof ServerInterface) {
            try {
                $entity->setPrivateKey($this->store->retrieve($entity->getPrivateKeyName()));
            }
            // Ignore the loading for this server
            // if any exception from the key store is thrown
            catch (BaseKeyStoreException $e) {}
        }
    }
}
