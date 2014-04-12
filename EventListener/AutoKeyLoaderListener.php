<?php

namespace Dedipanel\PHPSeclibWrapperBundle\EventListener;

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
            $entity->setPrivateKey($this->store->retrieve($entity->getPrivateKeyName()));
        }
    }
} 