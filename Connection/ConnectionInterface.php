<?php

namespace DP\PHPSeclibWrapperBundle\Connection;

interface ConnectionInterface
{
    public function getServer();
    
    /**
     * Executes a shell command on the server
     *
     * @param string $cmd Command to execute
     *
     * @return string Return the shell command output
     */
    public function exec($cmd);
    
    /**
     * Upload $data in $filepath and modified file chmod
     * 
     * @param   $filepath   string          File path of the file on the server
     * @param   $data       string          Data to upload
     * @param   $chmod      integer|boolean Chmod of the file (octal integer in php need to start with a trailing 0)
     *                                      Can be false to disable file perms modification
     * @return  boolean
     */
    public function upload($filepath, $data, $chmod = 0750);
    
    /**
     * Download $filepath
     * 
     * @param $filepath string File path of the file to download
     * 
     * @return boolean|string  Return false if the file can't be accessed or the file content
     */
    public function download($filepath);
    
    /**
     * Verify that we can access the server with server credentials
     *
     * @return boolean Can we connect ?
     */
    public function connectionTest();
    
    /**
     * Verify if the file $filepath exists
     * 
     * @param $filepath string
     * 
     * @return boolean Return true if the file exists
     */
    public function fileExists($filepath);
    
    /**
     * Verify if the dir $dirpath exists
     * 
     * @param $dirpath string
     * 
     * @return boolean Return true if the dir exists
     */
    public function dirExists($dirpath);
    
    /**
     * Removes the file or directory
     * 
     * @param $path string
     * 
     * @return boolean Return true if the file (or directory) has been deleted
     */
     public function remove($path);
}
