<?php

namespace DP\PHPSeclibWrapperBundle\Connection;

use DP\PHPSeclibWrapperBundle\Server\ServerInterface;
use DP\PHPSeclibWrapperBundle\Connection\Exception\IncompleteLoginCredentialsException;
use DP\PHPSeclibWrapperBundle\Connection\Exception\ConnectionErrorException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Connection
{
    protected $server;
    protected $connectionId;

    protected $debug;
    protected $logger;

    protected $ssh;
    protected $sftp;

    /**
     * @param ServerInterface   $server Server representation containing informations about it
     * @param boolean           $debug  Indicates whether connection need to be in debug mode
     *
     * @return Connection       Current instance, for method chaining
     */
    public function __construct(ServerInterface $server, $debug = false)
    {
        $this->server = $server;
        $this->debug = $debug;
        $this->logger = new NullLogger;

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
     * Gets the PHPSeclib SSH instance associated to this Connection instance.
     * If not already openned, we tried to connect with the server informations.
     *
     * @throws IncompleteLoginCredentialsException  If no private key and no password are defined
     * @throws ConnectionErrorException             If the connection can't be done (mostly due to bad credentials or timeout)
     *
     * @return \Net_SSH2        PHPSeclib SSH connection
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
            	$this->logger->notice(get_class($this) . '::getSSH - Trying to connect to ssh server ({username}@{host}:{port}, cid: {cid}) with private keyfile.', array(
                    'host' => $hostname,
                    'port' => $port,
                    'username' => $username,
                    'cid' => $this->getConnectionId(),
                ));

                $login = $ssh->login($username, $privateKey);
            }
            elseif (!empty($password)) {
            	$this->logger->notice(get_class($this) . '::getSSH - Trying to connect to ssh server ({username}@{host}:{port}, cid: {cid}) with password.', array(
                    'host' => $hostname,
                    'port' => $port,
                    'username' => $username,
                    'cid' => $this->getConnectionId(),
                ));

                $login = $ssh->login($username, $password);
            }
            else {
            	$this->logger->warning(get_class($this) . '::getSSH - Can\'t connect to ssh server ({username}@{host}:{port}, cid: {cid}) because no private key and no password are set.', array(
                    'host' => $hostname,
                    'port' => $port,
                    'username' => $username,
                    'cid' => $this->getConnectionId(),
                ));

                throw new IncompleteLoginCredentialsException($this->server);
            }

            if ($login === false) {
            	$this->logger->warning(get_class($this) . '::getSSH - Connection to ssh server ({username}@{host}:{port}, cid: {cid}) failed.', array(
                    'host' => $hostname,
                    'port' => $port,
                    'username' => $username,
                    'cid' => $this->getConnectionId(),
                ));

                throw new ConnectionErrorException($this->server);
            }

            $this->ssh = $ssh;
        }

        return $this->ssh;
    }

    /**
     * Gets the PHPSeclib SFTP instance associated to this Connection instance.
     * If not already openned, we tried to connect with the server informations.
     *
     * @throws IncompleteLoginCredentialsException  If no private key and no password are defined
     * @throws ConnectionErrorException             If the connection can't be done (mostly due to bad credentials or timeout)
     *
     * @return \Net_SFTP        PHPSeclib SFTP connection
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
            	$this->logger->notice(get_class($this) . '::getSFTP - Trying to connect to sftp server ({username}@{host}:{port}, cid: {cid}) with private keyfile.', array(
                    'host' => $hostname,
                    'port' => $port,
                    'username' => $username,
                    'cid' => $this->getConnectionId(),
                ));

                $login = $sftp->login($username, $privateKey);
            }
            elseif (!empty($password)) {
            	$this->logger->notice(get_class($this) . '::getSFTP - Trying to connect to sftp server ({username}@{host}:{port}, cid: {cid}) with password.', array(
                    'host' => $hostname,
                    'port' => $port,
                    'username' => $username,
                    'cid' => $this->getConnectionId(),
                ));

                $login = $sftp->login($username, $password);
            }
            else {
            	$this->logger->warning(get_class($this) . '::getSFTP - Can\'t connect to sftp server ({username}@{host}:{port}, cid: {cid}) because no private key and no password are set.', array(
                    'host' => $hostname,
                    'port' => $port,
                    'username' => $username,
                    'cid' => $this->getConnectionId(),
                ));

                throw new IncompleteLoginCredentialsException($this->server);
            }

            if ($login === false) {
                $this->logger->warning(get_class($this) . '::getSFTP - Connection to sftp server ({username}@{host}:{port}, cid: {cid}) failed.', array(
                    'host' => $hostname,
                    'port' => $port,
                    'username' => $username,
                    'cid' => $this->getConnectionId(),
                ));

                throw new ConnectionErrorException($this->server);
            }

            $this->sftp = $sftp;
        }

        return $this->sftp;
    }

    /**
     * Executes a shell command on the server
     *
     * @param string $cmd Command to execute
     *
     * @return string Return the shell command output
     */
    public function exec($cmd)
    {
    	$this->logger->notice(get_class($this) . '::exec - Execute {cmd} on ssh server ({username}@{host}:{port}, cid: {cid}).', array(
            'host' => $this->server->getHostname(),
            'port' => $this->server->getPort(),
            'username' => $this->server->getUsername(),
            'cid' => $this->getConnectionId(),
            'cmd' => $cmd,
        ));

        $ret = $this->getSSH()->exec($cmd);
        $ret = trim($ret);

        return $ret;
    }

    /**
     * Verifies that we can access the server with server credentials
     *
     * @return boolean Can we connect ?
     */
    public function connectionTest()
    {
    	$this->logger->notice(get_class($this) . '::connectionTest - Test connection to ssh server ({username}@{host}:{port}, cid: {cid}).', array(
            'host' => $this->server->getHostname(),
            'port' => $this->server->getPort(),
            'username' => $this->server->getUsername(),
            'cid' => $this->getConnectionId(),
        ));

        try {
            $echo = $this->exec('echo test');

            if (empty($echo) || $echo != 'test') {
                return false;
            }
        }
        catch (\Exception $e) {
            $this->logger->notice(get_class($this) . '::connectionTest - Connection test to ssh server ({username}@{host}:{port}, cid: {cid}) failed.', array(
                'host' => $this->server->getHostname(),
                'port' => $this->server->getPort(),
                'username' => $this->server->getUsername(),
                'cid' => $this->getConnectionId(),
            ));

            return false;
        }

    	$this->logger->notice(get_class($this) . '::connectionTest - Connection test to ssh server ({username}@{host}:{port}, cid: {cid}) succeeded.', array(
            'host' => $this->server->getHostname(),
            'port' => $this->server->getPort(),
            'username' => $this->server->getUsername(),
            'cid' => $this->getConnectionId(),
        ));

        return true;
    }
}
