<?php

namespace DP\PHPSeclibWrapperBundle\Connection;

use DP\PHPSeclibWrapperBundle\Connection\OsSpecific\Exception\MethodNotImplementedException;
use DP\PHPSeclibWrapperBundle\Connection\OsSpecific\Exception\UnavailableMethodException;

interface OsSpecificConnectionInterface
{
    /**
     * Gets the user home
     * 
     * @throws MethodNotImplementedException
     * @throws UnavailableMethodException
     * 
     * @return string
     */
    public function getHome();
    
    /**
     * Creates $filepath or modify its modification time
     * 
     * @param $filepath string    File path of the file to create or to update
     * @param $mtime    \DateTime Mtime you want to set on the file
     * 
     * @throws MethodNotImplementedException
     * @throws UnavailableMethodException
     * 
     * @return boolean
     */
    public function touch($filepath, \DateTime $mtime = null);
    
    /**
     * Creates directory $dirpath
     * 
     * @param $dirpath string Absolute path of the directory to create
     * 
     * @throws MethodNotImplementedException
     * @throws UnavailableMethodException
     * 
     * @return boolean
     */
    public function createDirectory($dirpath);
    
    /**
     * Determine whether the os is a 64 bit system
     * 
     * @throws MethodNotImplementedException
     * @throws UnavailableMethodException
     * 
     * @return boolean
     */
    public function is64BitSystem();
    
    /**
     * Determine whether the $program is installed
     * 
     * @throws MethodNotImplementedException
     * @throws UnavailableMethodException
     * 
     * @return boolean
     */
    public function isInstalled($program);
    
    /**
     * Determine whether java is installed
     * 
     * @throws MethodNotImplementedException
     * @throws UnavailableMethodException
     * 
     * @return boolean
     */
    public function isJavaInstalled();
    
    /**
     * Determine whether if the 32/64 bits compatability library is installed (ia32-libs)
     * 
     * @throws MethodNotImplementedException
     * @throws UnavailableMethodException
     * 
     * @return boolean
     */
    public function hasCompatLib();
}
