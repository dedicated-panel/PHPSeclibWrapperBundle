<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Extension;

use Dedipanel\PHPSeclibWrapperBundle\SFTP\File;
use Dedipanel\PHPSeclibWrapperBundle\SFTP\Directory;

class SFTPExtension extends \Twig_Extension
{
    /**
     * @{inheritdoc}
     */
    public function getName()
    {
        return 'sftp_extension';
    }

    /**
     * @{inheritdoc}
     */
    public function getTests()
    {
        return array(
            new \Twig_SimpleTest('file', function ($event) { return $event instanceof File; }),
            new \Twig_SimpleTest('directory', function ($event) { return $event instanceof Directory; }),
        );
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('human_readable', array($this, 'humanReadable')),
        );
    }

    public function humanReadable($size)
    {
        if ($size >= 1 << 30) {
            return number_format($size / (1 << 30), 2). 'GB';
        }
        elseif ($size >= 1 << 20) {
            return number_format($size / (1 << 20), 2) . 'MB';
        }
        elseif ($size >= 1 << 10) {
            return number_format($size / (1 << 10), 2). 'KB';
        }

        return number_format($size) . ' bytes';
    }
}
