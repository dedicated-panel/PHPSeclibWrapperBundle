<?php

namespace Dedipanel\PHPSeclibWrapperBundle\SFTP;

use Dedipanel\PHPSeclibWrapperBundle\Connection\ConnectionInterface;

class SFTPItemFactory
{
    public function getItem(ConnectionInterface $conn, $path, $chrootDir = null)
    {
        $stat = $conn->stat($path, $chrootDir);

        if ($stat['type'] == 1) {
            return $this->getFile($conn, $path, $chrootDir);
        }

        return $this->getDirectory($conn, $path, $chrootDir);
    }

    public function getFile(ConnectionInterface $conn, $path, $chrootDir = null, $new = false)
    {
        return new File($conn, $path, $chrootDir, $new);
    }

    public function getDirectory(ConnectionInterface $conn, $path, $chrootDir = null, $new = false)
    {
        return new Directory($conn, $path, $chrootDir, $new);
    }
}
