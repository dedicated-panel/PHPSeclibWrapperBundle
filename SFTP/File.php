<?php

namespace Dedipanel\PHPSeclibWrapperBundle\SFTP;

use Dedipanel\PHPSeclibWrapperBundle\Connection\ConnectionInterface;
use Dedipanel\PHPSeclibWrapperBundle\SFTP\Exception\UnreachableItemException;

class File extends AbstractItem
{
    /** @var string $content */
    protected $content;
    /** @var string $size */
    private $size;


    public function __construct(ConnectionInterface $conn, $pathname, $chrootDir = null, $new = false, $content = null)
    {
        parent::__construct($conn, $pathname, $chrootDir, $new);

        $this->setContent($content);
    }

    /**
     * Set the file content
     *
     * @param string $content
     * @return File
     */
    public function setContent($content = '')
    {
        $this->content = $content;
        $this->size    = strlen($content);

        return $this;
    }

    /**
     * Set the file size
     *
     * @param $size
     * @return File
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get file size
     *
     * @return File
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @{inheritdoc}
     */
    public function retrieve()
    {
        $path = $this->getFullPath();

        $content = $this->conn->getSFTP()->get($path);

        $this->conn->getLogger()->debug(get_class($this) . '::retrieve', array('phpseclib_logs' => $this->conn->getSFTP()->getSFTPLog()));
        $this->conn->getLogger()->info(get_class($this) . '::retrieve - Retrieving file "{path}" on sftp server "{server}" {ret}', array(
            'path' => $path,
            'server' => $this->conn->getServer(),
            'ret' => ($content != false) ? 'succeed' : 'failed',
        ));

        if ($content === false) {
            throw new UnreachableItemException($this);
        }

        $this->setContent($content);
        $this->retrieved = true;

        return $content;
    }

    /**
     * @{inheritdoc}
     */
    public function create()
    {
        $this->conn->getLogger()->info(get_class($this) . '::create - Creating file "{path}" on sftp server "{server}".', array(
            'path' => $this->getFullPath(),
            'server' => $this->conn->getServer(),
        ));

        return $this->update();
    }

    /**
     * @{inheritdoc}
     */
    public function update()
    {
        $oldPath = $this->getFullPath($this->oldPath, $this->oldName);
        $newPath = $this->getFullPath();

        if ($oldPath != $newPath && !$this->new) {
            $this->rename();
        }

        $pushed = $this->conn->getSFTP()->put($newPath, $this->content);

        $this->conn->getLogger()->debug(get_class($this) . '::update', array('phpseclib_logs' => $this->conn->getSFTP()->getSFTPLog()));
        $this->conn->getLogger()->info(get_class($this) . '::update - Updating file "{path}" on sftp server "{server}" {ret}', array(
            'path' => $newPath,
            'server' => $this->conn->getServer(),
            'ret' => ($pushed != false) ? 'succeed' : 'failed',
        ));

        if ($pushed == false) {
            throw new UnreachableItemException($this);
        }

        return $pushed;
    }
}
