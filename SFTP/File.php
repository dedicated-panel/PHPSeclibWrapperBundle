<?php

namespace Dedipanel\PHPSeclibWrapperBundle\SFTP;

class File extends AbstractItem
{
    /** @var string $content **/
    private $content;
    
    
    public function __construct($path = null, $name = null, $content = '')
    {
        $this->path    = $path;
        $this->name    = $name;
        $this->content = $content;
        
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
}
