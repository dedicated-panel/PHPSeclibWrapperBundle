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
}
