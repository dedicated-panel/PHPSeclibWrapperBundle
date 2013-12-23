<?php

namespace DP\PHPSeclibWrapperBundle\Connection;

use DP\PHPSeclibWrapperBundle\Server\ServerInterface;
use DP\PHPSeclibWrapperBundle\Connection\Exception\IncompleteLoginCredentialsException;
use DP\PHPSeclibWrapperBundle\Connection\Exception\ConnectionErrorException;
use DP\PHPSeclibWrapperBundle\Connection\ConnectionInterface;
use Psr\Log\LoggerInterface;

/**
 * @author Albin Kerouanton
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
class Connection implements ConnectionInterface
{
    /** @var Server\ServerInterface **/
    protected $server;
    /** @var integer Connection id used by the connection manager **/
    protected $connectionId;
    
    /** @var boolean **/
    protected $debug;
    /** @var LoggerInterface **/
    protected $logger;
    
    /** @var \Net_SSH2 PHPSeclib ssh2 instance **/
    protected $ssh;
    /** @var \Net_SFTP PHPSeclib sftp instance **/
    protected $sftp;
    
    /**
     * @param ServerInterface   $server Server representation containing informations about it
     * @param LoggerInterface   $logger The logger instance used for logging error and debug messages
     * @param boolean           $debug  Indicates whether connection need to be in debug mode
     *
     * @return Connection       Current instance, for method chaining
     */
    public function __construct(ServerInterface $server, LoggerInterface $logger, $debug = false)
    {
        $this->server = $server;
        $this->logger = $logger;
        $this->debug = $debug;
        
        return $this;
    }
    
    /**
     * Interdit le clonage de l'objet
     */
    private function __clone() {}
    
    /**
     * Sets the connection id
     *
     * @param integer $connectionId Connection Id
     *
     * @return Connection Current instance, for method chaining
     */
    public function setConnectionId($connectionId)
    {
        $this->connectionId = $connectionId;
        
        return $this;
    }
    
    /**
     * Gets the connection id assigned by the manager
     *
     * @return integer Connection id
     */
    public function getConnectionId()
    {
        return $this->connectionId;
    }
    
    /**
     * Sets the debug mode
     *
     * @param boolean $debug Indicates whether connection need to be in debug mode
     *
     * @return Connection Current instance, for method chaining
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
        
        return $this;
    }
    
    /**
     * Gets the debug mode
     *
     * @return boolean Current debug mode for ssh/sftp connections
     */
    public function getDebug()
    {
        return $this->debug;
    }
    
    /**
     * Sets the logger
     *
     * @param LoggerInterface $logger
     *
     * @return Connection
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        
        return $this;
    }
    
    /**
     * Gets the logger
     *
     * @return LogerInterface Logger instance
     */
    public function getLogger()
    {
        return $this->logger;
    }
    
    /**
     * Gets the server instance
     * 
     * @return ServerInterface
     */
    public function getServer()
    {
        return $this->server;
    }
    
    /**
     * @{inheritDoc}
     */
    public function getSSH()
    {
        if (!isset($this->ssh)) {
            $hostname = $this->server->getHostname();
            $port = $this->server->getPort();
            
            $ssh = new \Net_SSH2($hostname, $port);
            
            $username = $this->server->getUsername();
            $password = $this->server->getPassword();
            $privateKey = $this->server->getPrivateKey();
            
            if (!empty($privateKey)) {
                $key = new \Crypt_RSA();
                $key->loadKey($privateKey);
                
                $this->logger->notice(get_class($this) . '::getSSH - Trying to connect to ssh server ({server}, cid: {cid}) using private keyfile.', array(
                    'server' => strval($this->server),
                    'cid' => $this->getConnectionId(),
                ));
                
                $login = $ssh->login($username, $key);
            }
            elseif (!empty($password)) {
                $this->logger->notice(get_class($this) . '::getSSH - Trying to connect to ssh server ({server}, cid: {cid}) using password.', array(
                    'server' => strval($this->server),
                    'cid' => $this->getConnectionId(),
                ));
                
                $login = $ssh->login($username, $password);
            }
            else {
                $this->logger->warning(get_class($this) . '::getSSH - Can\'t connect to ssh server ({server}, cid: {cid}) because no private key and no password are set.', array(
                    'server' => strval($this->server),
                    'cid' => $this->getConnectionId(),
                ));
                
                throw new IncompleteLoginCredentialsException($this->server);
            }
            
            if ($login === false) {
                $this->logger->warning(get_class($this) . '::getSSH - Connection to ssh server ({server}, cid: {cid}) failed.', array(
                    'server' => strval($this->server),
                    'cid' => $this->getConnectionId(),
                ));
                
                throw new ConnectionErrorException($this->server);
            }
            
            $this->ssh = $ssh;
        }
        
        return $this->ssh;
    }
    
    /**
     * @{inheritDoc}
     */
    public function getSFTP()
    {
        if (!isset($this->sftp)) {
            $hostname = $this->server->getHostname();
            $port = $this->server->getPort();
            
            $sftp = new \Net_SFTP($hostname, $port);
            
            $username = $this->server->getUsername();
            $password = $this->server->getPassword();
            $privateKey = $this->server->getPrivateKey();
            
            if (!empty($privateKey)) {
                $this->logger->notice(get_class($this) . '::getSFTP - Trying to connect to sftp server ({server}, cid: {cid}) with private keyfile.', array(
                    'server' => strval($this->server),
                    'cid' => $this->getConnectionId(),
                ));
                
                $login = $sftp->login($username, $privateKey);
            }
            elseif (!empty($password)) {
                $this->logger->notice(get_class($this) . '::getSFTP - Trying to connect to sftp server ({server}, cid: {cid}) with password.', array(
                    'server' => strval($this->server),
                    'cid' => $this->getConnectionId(),
                ));
                
                $login = $sftp->login($username, $password);
            }
            else {
                $this->logger->warning(get_class($this) . '::getSFTP - Can\'t connect to sftp server ({server}, cid: {cid}) because no private key and no password are set.', array(
                    'server' => strval($this->server),
                    'cid' => $this->getConnectionId(),
                ));
                
                throw new IncompleteLoginCredentialsException($this->server);
            }
            
            if ($login === false) {
                $this->logger->warning(get_class($this) . '::getSFTP - Connection to sftp server ({server}:{port}, cid: {cid}) failed.', array(
                    'server' => strval($this->server),
                    'cid' => $this->getConnectionId(),
                ));
                
                throw new ConnectionErrorException($this->server);
            }
            
            $this->sftp = $sftp;
        }
        
        return $this->sftp;
    }
    
    /**
     * @{inheritDoc}
     */
    public function exec($cmd)
    {
        $this->logger->notice(get_class($this) . '::exec - Execute {cmd} on ssh server ({server}, cid: {cid}).', array(
            'server' => strval($this->server),
            'cid' => $this->getConnectionId(),
            'cmd' => $cmd,
        ));
        
        $ret = $this->getSSH()->exec($cmd);
        $ret = trim($ret);
        
        $this->logger->debug(get_class($this) . '::exec - Return of the command {cmd} executed on cid {cid} : {ret}.', array(
            'cid' => $this->getConnectionId(),
            'cmd' => $cmd,
            'ret' => $ret, 
        ));
        
        return $ret;
    }
    
    /**
     * @{inheritDoc}
     */
    public function upload($filepath, $data, $chmod = 0750)
    {
        $this->logger->notice(get_class($this) . '::upload - Upload {bytes} bytes to {filepath} on sftp server ({server}, cid: {cid}).', array(
            'server' => strval($this->server),
            'cid' => $this->getConnectionId(),
            'bytes' => strlen($data), 
            'filepath' => $filepath, 
        ));
        
        $sftp = $this->getSFTP();
        $ret = $sftp->put($filepath, $data);
        
        $this->logger->debug(get_class($this) . '::upload - Uploading to {filtepath} on sftp server {server} (cid: {cid}) : {ret}.', array(
            'cid' => $this->getConnectionId(),
            'cmd' => $cmd,
            'ret' => ($ret == true ? 'successful' : 'failed'), 
        ));
        
        if ($chmod !== false) {
            $this->logger->notice(get_class($this) . '::upload - Chmod {filepath} to {chmod} on sftp server {server} (cid: {cid}).', array(
                'server' => strval($this->server),
                'cid' => $this->getConnectionId(), 
                'filepath' => $filepath, 
            ));
        
            $sftp->chmod($chmod, $filepath);
            
            $this->logger->debug(get_class($this) . '::upload - Chmoding {filepath} to {chmod} on sftp server {server} (cid: {cid}) : {ret}.', array(
                'server' => strval($this->server),
                'cid' => $this->getConnectionId(), 
                'filepath' => $filepath,
                'ret' => ($ret == true ? 'successful' : 'failed'),
            ));
        }
        
        return $ret;
    }
    
    /**
     * @{inheritDoc}
     */
    public function download($filepath)
    {
        $this->logger->notice(get_class($this) . '::download - Download {filepath} on sftp server {server} (cid: {cid}).', array(
            'server' => strval($this->server),
            'cid' => $this->getConnectionId(),
            'filepath' => $filepath, 
        ));
        
        $content = $this->getSFTP()->get($filepath);
        
        $this->logger->debug(get_class($this) . '::download - Downloading {size} bytes from {filepath} on sftp server {server} (cid: {cid}).', array(
            'server' => strval($this->server),
            'cid' => $this->getConnectionId(),
            'size' => strlen($content),
            'filepath' => $filepath, 
        ));
        
        return $content;
    }
    
    /**
     * @{inheritDoc}
     */
    public function connectionTest()
    {
        $this->logger->notice(get_class($this) . '::connectionTest - Test connection to ssh server ({server}, cid: {cid}).', array(
            'server' => strval($this->server),
            'cid' => $this->getConnectionId(),
        ));
        
        try {
            $echo = $this->exec('echo test');
            
            if (empty($echo) || $echo != 'test') {
                $this->logger->notice(get_class($this) . '::connectionTest - Connection test to ssh server ({username}@{host}:{port}, cid: {cid}) failed.', array(
                    'host' => $this->server->getHostname(),
                    'port' => $this->server->getPort(),
                    'username' => $this->server->getUsername(),
                    'cid' => $this->getConnectionId(),
                ));
                
                return false;
            }
        }
        catch (\Exception $e) {
            $this->logger->notice(get_class($this) . '::connectionTest - Connection test to ssh server ({server}, cid: {cid}) failed.', array(
                'server' => strval($this->server),
                'cid' => $this->getConnectionId(),
            ));
            
            return false;
        }
        
        $this->logger->notice(get_class($this) . '::connectionTest - Connection test to ssh server ({server}, cid: {cid}) succeeded.', array(
            'server' => strval($this->server),
            'cid' => $this->getConnectionId(),
        ));
        
        return true;
    }
    
    /**
     * @{inheritDoc}
     */
    public function fileExists($filepath)
    {
        $ret = $this->exec('if [ -f ' . $filepath . ' ]; then echo 1; else echo 0; fi');
        
        return intval($ret) == 1;
    }
    
    /**
     * @{inheritDoc}
     */
    public function dirExists($dirpath)
    {
        $ret = $this->exec('if [ -d ' . $dirpath . ' ]; then echo 1; else echo 0; fi');
        
        return intval($ret) == 1;
    }
    
    /**
     * @{inheritDoc}
     */
    public function remove($path)
    {
        $this->logger->notice(get_class($this) . '::remove - Remove {path} from ssh server {server} (cid: {cid}).', array(
            'server' => strval($this->server),
            'cid'    => $this->getConnectionId(),
            'path'   => $path, 
        ));
        
        $ret = $this->getSFTP()->delete($path, true);
        
        $this->logger->notice(get_class($this) . '::remove - Removing {path} from ssh server {server} (cid: {cid}) : {ret}.', array(
            'server' => strval($this->server),
            'cid'    => $this->getConnectionId(),
            'path'   => $path, 
            'ret'    => ($ret == true ? 'successfull' : 'failed'), 
        ));
        
        return $ret;
    }
}
