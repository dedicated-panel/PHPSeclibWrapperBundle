<?php

namespace Dedipanel\PHPSeclibWrapperBundle\SFTP;

class AbstractItem
{
    /** @var string $name **/
    protected $name;
    /** @var string $path **/
    protected $path;
    /** @var boolean $invalid **/
    protected $invalid;
    
    
    public function __construct($path = null, $name = null)
    {
        $this->path = rtrim($path, '/');
        $this->name = $name;
        
        $this->invalid = false;
    }
    
    /**
     * Set item name (with extension)
     * 
     * @param string $name
     * 
     * @return AbstractItem
     */
    public function setName($name)
    {
        $this->name = $name;
        
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
     * Set and trim the directory path
     * 
     * @param string $path
     * 
     * @return AbstractItem
     */
    public function setPath($path)
    {
        $this->path = rtrim($path, '/');
        
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
     * Get the directory full path (path + name)
     * 
     * @return string
     */
    public function getFullPath()
    {
        return $this->path . '/' . $this->name;
    }
    
    /**
     * Set whether the full path is invalid
     * 
     * @param boolean $invalid
     * 
     * @return AbstractItem
     */
    public function setInvalid($invalid)
    {
        $this->invalid = $invalid;
        
        return $this;
    }
    
    /**
     * Is the full path invalid ?
     * 
     * @return boolean
     */
    public function isInvalid()
    {
        return $this->invalid;
    }

    public function __toString()
    {
        return $this->getFullPath();
    }
}
