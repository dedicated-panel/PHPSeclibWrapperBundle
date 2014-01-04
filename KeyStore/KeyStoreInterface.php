<?php

namespace Dedipanel\PHPSeclibWrapperBundle\KeyStore;

use Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\KeyNotExistsException;

/**
 * @author Albin Kerouanton
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
interface KeyStoreInterface
{
    /**
     * Stores key content by its $name
     *
     * @api
     *
     * @param string $name    Key name
     * @param string $content Key content
     *
     * @return boolean
     */
    public function store($name, $content);

    /**
     * Retrieves key content by its name
     *
     * @api
     *
     * @param string $name Key name
     *
     * @throws KeyNotExistsException
     *
     * @return string
     */
    public function retrieve($name);

    /**
     * Removes a key
     *
     * @api
     *
     * @param string $name Key name
     *
     * @throws KeyNotExistsException
     *
     * @return boolean Return true if file has been successfully deleted
     *                 or if the file is already deleted
     */
    public function remove($name);
}
