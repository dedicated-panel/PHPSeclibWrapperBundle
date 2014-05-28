<?php

namespace Dedipanel\PHPSeclibWrapperBundle\SFTP;

use Dedipanel\PHPSeclibWrapperBundle\Connection\ConnectionInterface;
use Dedipanel\PHPSeclibWrapperBundle\SFTP\Exception\InvalidPathException;
use Dedipanel\PHPSeclibWrapperBundle\SFTP\Exception\UnreachableItemException;

abstract class AbstractItem
{
    /** @var ConnectionInterface $conn */
    protected $conn;
    /** @var string $name */
    protected $name;
    /** @var string $path */
    protected $path;
    /** @var string $chrootDir */
    protected $chrootDir;
    /** @var boolean $retrieved */
    protected $retrieved;
    /** @var string $oldPath */
    protected $oldPath;
    /** @var string $oldName */
    protected $oldName;

    /**
     * @param ConnectionInterface $conn
     * @param $path
     * @param $name
     * @param null $chrootDir The constructor will automatically chroot
     *                        to the user home if no parameter is passed
     */
    public function __construct(ConnectionInterface $conn, $pathname, $chrootDir = null)
    {
        $this->conn = $conn;

        if (empty($chrootDir)) {
            $chrootDir = $this->conn->getServer()->getHome();
        }

        $this->chrootDir = rtrim($chrootDir, '/');

        $pathinfo = pathinfo($pathname);

        if ($pathinfo['dirname'] == '.' && $pathinfo['basename'] == '~') {
            $pathinfo['dirname']  = '~/';
            $pathinfo['basename'] = '';
            $pathinfo['filename'] = '';
        }
        elseif ($pathinfo['dirname'] == '~') {
            $pathinfo['dirname'] = '~/';
        }

        $this->setName($pathinfo['basename']);
        $this->setPath($pathinfo['dirname']);
    }

    /**
     * Get the item name
     *
     * @param $name
     * @return Abstractitem
     */
    public function setName($name)
    {
        $this->name = $name;

        if (empty($this->oldName)) {
            $this->oldName = $name;
        }

        return $this;
    }
    
    /**
     * Get directory name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the current item path
     * (relative to the chrootDir)
     *
     * @param $path
     * @return Abstractitem
     */
    public function setPath($path)
    {
        if (strpos($path, $this->chrootDir) === 0) {
            $path = substr_replace($path, '', 0, strlen($this->chrootDir));
        }
        elseif (substr($path, 0, 2) == '~/') {
            $path = substr($path, 2);
        }

        $this->path = trim($path, '/');

        if (!$this->validatePath()) {
            throw new InvalidPathException($this->path);
        }

        if (empty($this->oldPath)) {
            $this->oldPath = $this->path;
        }

        return $this;
    }
    
    /**
     * Get the directory path
     * 
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Validate the full path of the item
     *
     * @return bool
     */
    public function validatePath()
    {
        $path = $this->getFullPath();

        return
            strpos($path, $this->chrootDir) === 0
            && strpos($path, '..') === false;
    }

    /**
     * Get the dir path
     *
     * @return string
     */
    public function getChrootDir()
    {
        return $this->chrootDir;
    }
    
    /**
     * Get the directory full path (path + name)
     *
     * @param $itemPath string|null Can provide a string for resolving it
     * @param $name string|null Can provide a string for resolving it
     * @return string
     */
    public function getFullPath($itemPath = null, $name = null)
    {
        $path = $this->chrootDir . '/';

        if (!is_null($itemPath)) {
            $path .= $itemPath . '/';
        }
        elseif (!empty($this->path)) {
            $path .= $this->path . '/';
        }

        if (!is_null($name)) {
            return $path . $name;
        }

        return $path . $this->name;
    }

    /**
     * Retrieve the item content
     *
     * @return mixed
     */
    abstract public function retrieve();

    /**
     * Create the item
     *
     * @return boolean
     */
    abstract public function create();

    /**
     * Update the item
     *
     * @return boolean
     */
    abstract public function update();

    /**
     * Delete the item from server
     *
     * @return boolean
     * @throws UnreachableItemException
     */
    public function delete()
    {
        $path = $this->getFullPath();

        $removed = $this->conn->getSFTP()->delete($path);

        $this->conn->getLogger()->debug(get_class($this) . '::remove', array('phpseclib_logs' => $this->conn->getSFTP()->getSFTPLog()));
        $this->conn->getLogger()->info(get_class($this) . '::remove - Removing "{path}" on sftp server "{server}" {ret}', array(
            'path' => $path,
            'server' => $this->conn->getServer(),
            'ret' => ($removed != false) ? 'succeed' : 'failed',
        ));

        if ($removed == false) {
            throw new UnreachableItemException($this);
        }

        return $removed;
    }

    /**
     * Rename item from its old path to its new
     *
     * @return boolean
     * @throws UnreachableItemException
     */
    public function rename()
    {
        $oldPath = $this->getFullPath($this->oldPath, $this->oldName);
        $newPath = $this->getFullPath();

        $renamed = $this->conn->getSFTP()->rename($oldPath, $newPath);

        $this->conn->getLogger()->debug(get_class($this) . '::rename', array('phpseclib_logs' => $this->conn->getSFTP()->getSFTPLog()));
        $this->conn->getLogger()->info(get_class($this) . '::rename - Renaming "{old_path}" to "{path}" on sftp server "{server}" {ret}', array(
            'old_path' => $oldPath,
            'path' => $newPath,
            'server' => $this->conn->getServer(),
            'ret' => ($renamed != false) ? 'succeed' : 'failed',
        ));

        if ($renamed == false) {
            throw new UnreachableItemException($this);
        }

        return $renamed;
    }
}
