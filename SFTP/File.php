<?php

namespace Dedipanel\PHPSeclibWrapperBundle\SFTP;

class File extends AbstractItem
{
    /** @var string $content */
    private $content;
    /** @var string size */
    
    public function __construct($path = null, $name = null, $content = '')
    {
        $this->path    = $path;
        $this->name    = $name;
        $this->content = $content;
        
        $this->setSize(strlen($content));
        
        $this->invalid = false;
    }
    
    /**
     * Set directory content
     * 
     * @param string $content
     * 
     * @return File
     */
    public function setContent($content)
    {
        $this->content = $content;
        $this->setSize(strlen($content));
        
        return $this;
    }
    
    /**
     * Get directory content
     * 
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set file size
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
}
