<?php

namespace Dedipanel\PHPSeclibWrapperBundle\KeyStore;

use Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\KeyStoreInitializationException;
use Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\KeyNotExistsException;
use Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\KeyAlreadyExistsException;

/**
 * @author Albin Kerouanton
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
interface KeyStoreInterface
{
    /**
     * Initialize the key store
     * 
     * @throws \Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\KeyStoreInitializationException
     * 
     * @return KeyStoreInterface
     */
    public function initialize();
    
    /**
     * Is the store initialized and ready to use ?
     * 
     * @return boolean
     */
    public function isInitialized();
    
    /**
     * Stores key content by its $name
     *
     * @param string $name    Key name
     * @param string $content Key content
     * 
     * @throws \Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\KeyStoreNotInitializedException
     * @throws \Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\KeyAlreadyExistsException
     *
     * @return boolean
     */
    public function store($name, $content);

    /**
     * Retrieves key content by its name
     *
     * @param string $name Key name
     * 
     * @throws \Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\KeyStoreNotInitializedException
     * @throws \Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\KeyNotExistsException
     *
     * @return string
     */
    public function retrieve($name);

    /**
     * Removes a key
     *
     * @param string $name Key name
     * 
     * @throws \Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\KeyStoreNotInitializedException
     * @throws \Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\KeyNotExistsException
     *
     * @return boolean Return true if file has been successfully deleted
     *                 or if the file is already deleted
     */
    public function remove($name);
}
