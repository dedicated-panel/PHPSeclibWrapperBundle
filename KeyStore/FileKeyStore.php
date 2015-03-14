<?php

namespace Dedipanel\PHPSeclibWrapperBundle\KeyStore;

use Dedipanel\PHPSeclibWrapperBundle\KeyStore\KeyStoreInterface;
use Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\KeyStoreInitializationException;
use Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\KeyStoreNotInitializedException;
use Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\KeyAlreadyExistsException;
use Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\KeyNotExistsException;
use Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\AlreadyInitializedKeyStore;

/**
 * @author Albin Kerouanton
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
class FileKeyStore implements KeyStoreInterface
{
    private $storePath;
    private $initialized = false;

    /**
     * @param string $storePath Absolute path (without trailing slash) containg keys
     */
    public function __construct($storePath)
    {
        $this->storePath = rtrim($storePath, '/');
        $this->initialized = false;

        $this->initialize();
    }
    
    /**
     * {@inheritdoc}
     */
    public function initialize()
    {
        if (!file_exists($this->storePath) && @mkdir($this->storePath, 0700, true) !== true) {
            throw new KeyStoreInitializationException();
        }
        
        $this->initialized = true;
        
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isInitialized()
    {
        return $this->initialized;
    }

    /**
     * {@inheritdoc}
     */
    public function store($name, $content)
    {
        if (!$this->isInitialized()) {
            throw new KeyStoreNotInitializedException();
        }
        
        $filepath = $this->getFilepath($name);
        
        if (file_exists($filepath)) {
            throw new KeyAlreadyExistsException();
        }
        
        return file_put_contents($filepath, $content) !== false && chmod($filepath, 0700);
    }

    /**
     * {@inheritdoc}
     */
    public function retrieve($name)
    {
        if (!$this->isInitialized()) {
            throw new KeyStoreNotInitializedException();
        }
        
        $filepath = $this->getFilepath($name);

        if (!file_exists($filepath)) {
            throw new KeyNotExistsException();
        }

        return file_get_contents($filepath);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($name)
    {
        if (!$this->isInitialized()) {
            throw new KeyStoreNotInitializedException();
        }
        
        $filepath = $this->getFilepath($name);

        if (!file_exists($filepath)) {
            throw new KeyNotExistsException();
        }

        return unlink($filepath);
    }
    
    /**
     * Set the store path (need to be done before initialization)
     * 
     * @param string $storePath
     * 
     * @return \Dedipanel\PHPSeclibWrapperBundle\KeyStore\FileKeyStore
     */
    public function setStorePath($storePath)
    {
        if ($this->isInitialized()) {
            throw new AlreadyInitializedKeyStore();
        }
        
        $this->storePath = $storePath;
        
        return $this;
    }
    
    /**
     * Get the store path
     * 
     * @return string
     */
    public function getStorePath()
    {
        return $this->storePath;
    }

    /**
     * Gets the absolute file path for the key $name
     *
     * @param string $name Key name
     *
     * @return string Absolute file path
     */
    private function getFilepath($name)
    {
        return $this->storePath . '/' . $name . '.key';
    }
}
