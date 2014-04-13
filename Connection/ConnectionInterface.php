<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Connection;

/**
 * @author Albin Kerouanton
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
interface ConnectionInterface
{
    public function getServer();

    /**
     * Gets the PHPSeclib SSH instance associated to this Connection instance.
     * If not already openned, we tried to connect with the server informations.
     *
     * @api
     *
     * @throws IncompleteLoginCredentialsException If no private key and no password are defined
     * @throws ConnectionErrorException            If the connection can't be done (mostly due to bad credentials or timeout)
     *
     * @return \Net_SSH2 PHPSeclib SSH connection
     */
    public function getSSH();

    /**
     * Gets the PHPSeclib SFTP instance associated to this Connection instance.
     * If not already openned, we tried to connect with the server informations.
     *
     * @api
     *
     * @throws IncompleteLoginCredentialsException If no private key and no password are defined
     * @throws ConnectionErrorException            If the connection can't be done (mostly due to bad credentials or timeout)
     *
     * @return \Net_SFTP PHPSeclib SFTP connection
     */
    public function getSFTP();

    /**
     * Executes a shell command on the server
     *
     * @api
     *
     * @param string $cmd Command to execute
     *
     * @return string Return the shell command output
     */
    public function exec($cmd);

    /**
     * Upload $data in $filepath and modified file chmod
     *
     * @api
     *
     * @param          $filepath string          File path of the file on the server
     * @param          $data     string          Data to upload
     * @param          $chmod    integer|boolean Chmod of the file (octal integer in php need to start with a trailing 0)
     *                           Can be false to disable file perms modification
     * @return boolean
     */
    public function upload($filepath, $data, $chmod = 0750);

    /**
     * Download $filepath
     *
     * @api
     *
     * @param $filepath string File path of the file to download
     *
     * @return boolean|string Return false if the file can't be accessed or the file content
     */
    public function download($filepath);

    /**
     * Verify that we can access the server with server credentials
     *
     * @api
     *
     * @return boolean Can we connect ?
     */
    public function connectionTest();

    /**
     * Verify if the file $filepath exists
     *
     * @api
     *
     * @param $filepath string
     *
     * @return boolean Return true if the file exists
     */
    public function fileExists($filepath);

    /**
     * Verify if the dir $dirpath exists
     *
     * @api
     *
     * @param $dirpath string
     *
     * @return boolean Return true if the dir exists
     */
    public function dirExists($dirpath);

    /**
     * Removes the file or directory
     *
     * @api
     *
     * @param $path string
     *
     * @return boolean Return true if the file (or directory) has been deleted
     */
     public function remove($path);

    /**
     * Adds the $key to the authorized_keys of the user (create it if not exists)
     *
     * @api
     *
     * @param string $key Public key to add to the server
     *
     * @return boolean
     */
    public function addKey($key);

    /**
     * Removes the $key from the authorized_keys of the user
     *
     * @api
     *
     * @param string Public key to remove from the server
     *
     * @return boolean Return true if key successfully deleted or if authorized_keys doesn't exists
     */
    public function removeKey($key);

    /**
     * Creates $filepath or modify its modification time
     *
     * @api
     *
     * @param $filepath string    File path of the file to create or to update
     * @param $mtime    \DateTime Mtime you want to set on the file
     *
     * @return boolean
     */
    public function touch($filepath, \DateTime $mtime = null);

    /**
     * Creates $dirpath
     *
     * @api
     *
     * @param $dirpath string
     *
     * @return boolean
     */
    public function mkdir($dirpath);

    /**
     * Creates $filepath
     *
     * @api
     *
     * @param $filepath string File path of the file to create
     *
     * @return boolean
     */
    public function createFile($filepath);

    /**
     * Creates directory $dirpath
     *
     * @api
     *
     * @param $dirpath string Absolute path of the directory to create
     *
     * @return boolean
     */
    public function createDir($dirpath);

    /**
     * Change permissions on $path
     *
     * @api
     *
     * @param $path string Absolute path
     * @param $chmod integer Chmod octal integer
     * @param $recursive boolean Change permissions recursivly ?
     *
     * @return boolean
     */
    public function chmod($path, $chmod, $recursive = true);
    
    /**
     * Gets the user home
     *
     * @api
     *
     * @return string
     */
    public function getHome();

    /**
     * Determine whether the os is a 64 bit system
     *
     * @api
     *
     * @return boolean
     */
    public function is64BitSystem();

    /**
     * Determine whether the $program is installed
     *
     * @api
     *
     * @return boolean
     */
    public function isInstalled($program);

    /**
     * Determine whether java is installed
     *
     * @api
     *
     * @return boolean
     */
    public function isJavaInstalled();

    /**
     * Determine whether if the 32/64 bits compatability library is installed (ia32-libs)
     *
     * @api
     *
     * @return boolean
     */
    public function hasCompatLib();

    /**
     * Gets the screen $screenName content
     *
     * @api
     *
     * @param $screenName string The screen name
     *
     * @return string
     */
    public function getScreenContent($screenName);
    
    /**
     * Gets the server nb core
     * 
     * @return string
     */
    public function retrieveNbCore();

    /**
     * Resolve a given path (useful for ~/)
     *
     * @param $path string
     * @return string
     */
    public function resolvePath($path);
}
