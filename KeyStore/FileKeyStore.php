<?php

namespace Dedipanel\PHPSeclibWrapperBundle\KeyStore;

use Dedipanel\PHPSeclibWrapperBundle\KeyStore\KeyStoreInterface;
use Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\KeyNotExistsException;

/**
 * @author Albin Kerouanton
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
class FileKeyStore implements KeyStoreInterface
{
    private $dir;

    /**
     * @param string $dir Directory absolute path (without trailing slash) containg keys
     */
    public function __construct($dir)
    {
        $this->dir = $dir;
    }

    /**
     * @{inheritDoc}
     */
    public function store($name, $content)
    {
        return file_put_contents($this->getFilepath($name), $content) !== false;
    }

    /**
     * @{inheritDoc}
     */
    public function retrieve($name)
    {
        $filepath = $this->getFilepath($name);

        if (!file_exists($filepath)) {
            throw new KeyNotExistsException;
        }

        return file_get_contents($filepath);
    }

    public function remove($name)
    {
        $filepath = $this->getFilepath($name);

        if (!file_exists($filepath)) {
            return true;
        }

        return unlink($filepath);
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
        return $this->dir . '/' . $name . '.key';
    }
}
