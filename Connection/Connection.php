<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Connection;

use Dedipanel\PHPSeclibWrapperBundle\Connection\Exception\IncompleteLoginCredentialsException;
use Dedipanel\PHPSeclibWrapperBundle\Connection\Exception\ConnectionErrorException;
use Dedipanel\PHPSeclibWrapperBundle\Connection\ConnectionInterface;
use Psr\Log\LoggerInterface;

/**
 * @author Albin Kerouanton
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
class Connection implements ConnectionInterface
{
    /** @var ServerInterface **/
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
     * @param ServerInterface $server Server representation containing informations about it
     * @param LoggerInterface $logger The logger instance used for logging error and debug messages
     * @param boolean         $debug  Indicates whether connection need to be in debug mode
     *
     * @return Connection Current instance, for method chaining
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
            $hostname = $this->server->getServerIP();
            $port = $this->server->getPort();

            $ssh = new \Net_SSH2($hostname, $port);

            $username = $this->server->getUsername();
            $password = $this->server->getPassword();
            $privateKey = $this->server->getPrivateKey();

            if (!empty($password)) {
                $this->logger->info(get_class($this) . '::getSSH - Trying to connect to ssh server "{server}" (cid: {cid}) using password.', array(
                    'server' => strval($this->server),
                    'cid' => $this->getConnectionId(),
                ));

                $login = $ssh->login($username, $password);
            } elseif (!empty($privateKey)) {
                $this->logger->info(get_class($this) . '::getSSH - Trying to connect to ssh server "{server}" (cid: {cid}) using privatekey.', array(
                    'server' => strval($this->server),
                    'cid' => $this->getConnectionId(),
                ));

                $login = $ssh->login($username, $privateKey);
            } else {
                $this->logger->error(get_class($this) . '::getSSH - Can\'t connect to ssh server "{server}" (cid: {cid}) because no password and no private key are set.', array(
                    'server' => strval($this->server),
                    'cid' => $this->getConnectionId(),
                ));

                throw new IncompleteLoginCredentialsException($this->server);
            }

            if ($login === false) {
                $this->logger->error(get_class($this) . '::getSSH - Connection to ssh server "{server}" (cid: {cid}) failed.', array(
                    'server' => strval($this->server),
                    'cid' => $this->getConnectionId(),
                ));
                $this->logger->debug(get_class($this) . '::getSSH', array('phpseclib_logs' => $this->getSSH()->getLog()));

                throw new ConnectionErrorException($this->server);
            }

            $this->logger->info(get_class($this) . '::getSSH - Connection to ssh server "{server}" (cid: {cid}) succeeded.', array(
                'server' => strval($this->server),
                'cid' => $this->getConnectionId(),
            ));

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
            $hostname = $this->server->getServerIP();
            $port = $this->server->getPort();

            $sftp = new \Net_SFTP($hostname, $port);

            $username = $this->server->getUsername();
            $password = $this->server->getPassword();
            $privateKey = $this->server->getPrivateKey();

            if (!empty($password)) {
                $this->logger->info(get_class($this) . '::getSFTP - Trying to connect to sftp server "{server}" (cid: {cid}) with password.', array(
                    'server' => strval($this->server),
                    'cid' => $this->getConnectionId(),
                ));

                $login = $sftp->login($username, $password);
            } elseif (!empty($privateKey)) {
                $this->logger->info(get_class($this) . '::getSFTP - Trying to connect to sftp server "{server}" (cid: {cid}) with private keyfile.', array(
                    'server' => strval($this->server),
                    'cid' => $this->getConnectionId(),
                ));

                $login = $sftp->login($username, $privateKey);
            } else {
                $this->logger->error(get_class($this) . '::getSFTP - Can\'t connect to sftp server "{server}" (cid: {cid}) because no private key and no password are set.', array(
                    'server' => strval($this->server),
                    'cid' => $this->getConnectionId(),
                ));

                throw new IncompleteLoginCredentialsException($this->server);
            }

            if ($login === false) {
                $this->logger->error(get_class($this) . '::getSFTP - Connection to sftp server "{server}" (cid: {cid}) failed.', array(
                    'server' => strval($this->server),
                    'cid' => $this->getConnectionId(),
                ));
                $this->logger->debug(get_class($this) . '::getSFTP', array('phpseclib_logs' => $this->getSFTP()->getSFTPLog()));

                throw new ConnectionErrorException($this->server);
            }

            $this->logger->info(get_class($this) . '::getSFTP - Connection to sftp server "{server}" (cid: {cid}) succeeded.', array(
                'server' => strval($this->server),
                'cid' => $this->getConnectionId(),
            ));

            $this->sftp = $sftp;
        }

        return $this->sftp;
    }

    /**
     * @{inheritDoc}
     */
    public function exec($cmd)
    {
        $this->logger->info(get_class($this) . '::exec - Execute cmd on ssh server "{server}" (cid: {cid}).', array(
            'server' => strval($this->server),
            'cid' => $this->getConnectionId(),
            'cmd' => $cmd,
        ));

        $ret = $this->getSSH()->exec($cmd);
        $ret = trim($ret);

        $this->broadcastLog
        $this->logger->info(get_class($this) . '::exec - Return of the command executed on "{server}" (cid: {cid}) : "{ret}".', array(
            'cid' => $this->getConnectionId(),
            'cmd' => $cmd,
            'ret' => $ret,
        ));
        $this->logger->debug(get_class($this) . '::exec', array('phpseclib_logs' => $this->getSSH()->getLog()));

        return $ret;
    }

    /**
     * @{inheritdoc}
     */
    public function upload($filepath, $data, $chmod = 0750)
    {
        $filepath = $this->resolvePth($filepath);

        $this->logger->info(get_class($this) . '::upload - Upload {bytes} bytes to "{filepath}" on sftp server "{server}" (cid: {cid}).', array(
            'server' => strval($this->server),
            'cid' => $this->getConnectionId(),
            'bytes' => strlen($data),
            'filepath' => $filepath,
        ));

        $sftp = $this->getSFTP();
        $ret = $sftp->put($filepath, $data);

        $this->logger->info(get_class($this) . '::upload - Uploading to {filtepath} on sftp server "{server}" (cid: {cid}) {ret}.', array(
            'server' => strval($this->server),
            'cid' => $this->getConnectionId(),
            'filepath' => $filepath,
            'ret' => ($ret == true ? 'succeeded' : 'failed'),
        ));
        $this->logger->debug(get_class($this) . '::upload', array('phpseclib_logs' => $this->getSFTP()->getSFTPLog()));

        if ($chmod !== false) {
            $this->logger->info(get_class($this) . '::upload - Chmod "{filepath}" to {chmod} on sftp server "{server}" (cid: {cid}).', array(
                'server' => strval($this->server),
                'cid' => $this->getConnectionId(),
                'filepath' => $filepath,
                'chmod' => $chmod,
            ));

            $sftp->chmod($chmod, $filepath);

            $this->logger->info(get_class($this) . '::upload - Chmoding "{filepath}" to {chmod} on sftp server "{server}" (cid: {cid}) {ret}.', array(
                'server' => strval($this->server),
                'cid' => $this->getConnectionId(),
                'filepath' => $filepath,
                'chmod' => $chmod,
                'ret' => ($ret == true ? 'succeeded' : 'failed'),
            ));
            $this->logger->debug(get_class($this) . '::upload - Chmoding', array('phpseclib_logs' => $this->getSFTP()->getSFTPLog()));
        }

        return $ret;
    }

    /**
     * @{inheritdoc}
     */
    public function download($filepath)
    {
        $filepath = $this->resolvePath($filepath);

        $this->logger->info(get_class($this) . '::download - Download "{filepath}" on sftp server "{server}" (cid: {cid}).', array(
            'server' => strval($this->server),
            'cid' => $this->getConnectionId(),
            'filepath' => $filepath,
        ));

        $content = $this->getSFTP()->get($filepath);

        $this->logger->info(get_class($this) . '::download - Downloading {size} bytes from "{filepath}" on sftp server "{server}" (cid: {cid}).', array(
            'server' => strval($this->server),
            'cid' => $this->getConnectionId(),
            'size' => strlen($content),
            'filepath' => $filepath,
        ));
        $this->logger->debug(get_class($this) . '::download', array('phpseclib_logs' => $this->getSFTP()->getSFTPLog()));

        return $content;
    }

    /**
     * @{inheritdoc}
     */
    public function connectionTest()
    {
        $this->logger->info(get_class($this) . '::connectionTest - Test connection to ssh server "{server}" (cid: {cid}).', array(
            'server' => strval($this->server),
            'cid' => $this->getConnectionId(),
        ));

        try {
            $echo = $this->exec('echo test');

            if (empty($echo) || $echo != 'test') {
                $this->logger->error(get_class($this) . '::connectionTest - Connection test to ssh server "{server}" (cid: {cid}) failed.', array(
                    'server' => strval($this->server),
                    'cid' => $this->getConnectionId(),
                ));
                $this->logger->debug(get_class($this) . '::connectionTest', array('phpseclib_logs' => $this->getSSH()->getLog()));

                return false;
            }
        } catch (\Exception $e) {
            $this->logger->error(get_class($this) . '::connectionTest - Connection test to ssh server "{server}" (cid: {cid}) failed.', array(
                'server' => strval($this->server),
                'cid' => $this->getConnectionId(),
            ));
            $this->logger->debug(get_class($this) . '::connectionTest', array('phpseclib_logs' => $this->getSSH()->getLog()));

            return false;
        }

        $this->logger->info(get_class($this) . '::connectionTest - Connection test to ssh server "{server}" (cid: {cid}) succeeded.', array(
            'server' => strval($this->server),
            'cid' => $this->getConnectionId(),
        ));

        return true;
    }

    /**
     * @{inheritdoc}
     */
    public function fileExists($filepath)
    {
        $ret = $this->exec('if [ -f ' . $filepath . ' ]; then echo 1; else echo 0; fi');

        return intval($ret) == 1;
    }

    /**
     * @{inheritdoc}
     */
    public function dirExists($dirpath)
    {
        $ret = $this->exec('if [ -d ' . $dirpath . ' ]; then echo 1; else echo 0; fi');

        return intval($ret) == 1;
    }

    /**
     * @{inheritdoc}
     */
    public function remove($path)
    {
        $path = $this->resolvePath($path);

        $this->logger->info(get_class($this) . '::remove - Remove "{path}" from ssh server "{server}" (cid: {cid}).', array(
            'server' => strval($this->server),
            'cid'    => $this->getConnectionId(),
            'path'   => $path,
        ));

        $ret = $this->getSFTP()->delete($path, true);

        $this->logger->info(get_class($this) . '::remove - Removing "{path}" from ssh server "{server}" (cid: {cid}) {ret}.', array(
            'server' => strval($this->server),
            'cid'    => $this->getConnectionId(),
            'path'   => $path,
            'ret'    => ($ret == true ? 'successfull' : 'failed'),
        ));
        $this->logger->debug(get_class($this) . '::remove', array('phpseclib_logs' => $this->getSFTP()->getSFTPLog()));

        return $ret;
    }

    /**
     * @{inheritdoc}
     */
    public function addKey($key)
    {
        if (!$this->dirExists('~/.ssh')) {
            $this->createDir('~/.ssh');
        }

        if (!$this->fileExists('~/.ssh/authorized_keys')) {
            $this->touch('~/.ssh/authorized_keys');
        }

        $this->chmod('~/.ssh', 0700);

        $authorized = $this->download('~/.ssh/authorized_keys');
        $authorized .= $key . "\n";

        return $this->upload('~/.ssh/authorized_keys', $authorized);
    }

    /**
     * @{inheritdoc}
     */
    public function removeKey($key)
    {
        if (!$this->dirExists('~/.ssh') || !$this->fileExists('~/.ssh/authorized_keys')) {
            return true;
        }

        $authorized = $this->download('~/.ssh/authorized_keys');
        $authorized = str_replace("$key\n", '', $authorized);

        return $this->upload('~/.ssh/authorized_keys', $authorized);
    }

    /**
     * {@inheritdoc}
     */
    public function touch($filepath, \DateTime $mtime = null)
    {
        $filepath = $this->resolvePath($filepath);
        $timestamp = (is_null($mtime) ? null : $mtime->getTimestamp());
        $mtime = (is_null($mtime) ? null : $mtime->format('d/m/y H:i:s'));

        $this->logger->info(get_class($this) . '::touch - Touch file "{filepath}" on {mtime} on sftp server "{server}" (cid: {cid}).', array(
            'server' => strval($this->server),
            'cid' => $this->getConnectionId(),
            'filepath' => $filepath,
            'mtime' => $mtime,
        ));

        $ret = $this->getSFTP()->touch($filepath, $timestamp);

        $this->logger->info(get_class($this) . '::touch - Touching file "{filepath}" on sftp server "{server}" (cid: {cid}) {ret}.', array(
            'server' => strval($this->server),
            'cid' => $this->getConnectionId(),
            'filepath' => $filepath,
            'ret' => ($ret == true ? 'succeeded' : 'failed'),
        ));
        $this->logger->debug(get_class($this) . '::touch', array('phpseclib_logs' => $this->getSFTP()->getSFTPLog()));

        return $ret;
    }

    /**
     * @param string $dirpath
     * @return bool
     */
    public function mkdir($dirpath)
    {
        $dirpath = $this->resolvePath($dirpath);

        $this->logger->info(get_class($this) . '::mkdir - Create directory "{dirpath}" on sftp server "{server}" (cid: {cid}).', array(
            'server' => strval($this->server),
            'cid' => $this->getConnectionId(),
            'dirpath' => $dirpath,
        ));

        $ret = $this->getSFTP()->mkdir($dirpath);

        $this->logger->info(get_class($this) . '::mkdir - Creating directory "{dirpath}" on sftp server "{server}" (cid: {cid}) {ret}.', array(
            'server' => strval($this->server),
            'cid' => $this->getConnectionId(),
            'dirpath' => $dirpath,
            'ret' => ($ret == true ? 'succeeded' : 'failed'),
        ));
        $this->logger->debug(get_class($this) . '::mkdir', array('phpseclib_logs' => $this->getSFTP()->getSFTPLog()));

        return $ret;
    }

    /**
     * {@inheritdoc}
     */
    public function createFile($filepath)
    {
        return $this->touch($filepath);
    }

    /**
     * {@inheritdoc}
     */
    public function createDir($dirpath)
    {
        return $this->mkdir($dirpath);
    }

    /**
     * {@inheritdoc}
     */
    public function chmod($path, $chmod, $recursive = true)
    {
        $path = $this->resolvePath($path);

        $this->logger->info(get_class($this) . '::chmod - Chmod {chmod} on "{path}" on sftp server "{server}" (cid: {cid}).', array(
            'server' => strval($this->server),
            'cid' => $this->getConnectionId(),
            'path' => $path,
            'chmod' => $chmod,
        ));

        $ret = $this->getSFTP()->chmod($path, $chmod);

        $this->logger->info(get_class($this) . '::chmod - Chmoding {chmod} on "{path}" on sftp server "{server}" (cid: {cid}) {ret}.', array(
            'server' => strval($this->server),
            'cid' => $this->getConnectionId(),
            'path' => $path,
            'chmod' => $chmod,
            'ret' => ($ret == true ? 'succeeded' : 'failed'),
        ));
        $this->logger->debug(get_class($this) . '::chmod', array('phpseclib_logs' => $this->getSFTP()->getSFTPLog()));

        return $ret;
    }

    /**
     * {@inheritdoc}
     */
    public function getHome()
    {
        return $this->exec('cd ~ && pwd');
    }

    /**
     * {@inheritdoc}
     */
    public function is64BitSystem()
    {
        return strlen($this->exec('uname -r | grep "\-64"')) > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function isInstalled($packet)
    {
        $ret = trim($this->exec('dpkg-query -W --showformat=\'${Status}\n\' ' . $packet . ' | grep \'install ok installed\''));

        return $ret == 'install ok installed';
    }

    /**
     * {@inheritdoc}
     */
    public function isJavaInstalled()
    {
        return strlen($this->exec('type java 2>/dev/null')) > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function hasCompatLib()
    {
        // On récupère la version du système debian utilisé
        // puisque le paquet à vérifier diffère avec la debian wheezy
        $os_version = floatval(trim($this->exec('cat /etc/debian_version')));

        if ($os_version >= 7) {
            return $this->isInstalled('libc6:i386');
        } else {
            return $this->isInstalled('ia32-libs');
        }
    }

    /**
     * @{inheritdoc}
     */
    public function getScreenContent($screenName)
    {
        $tmpFile = '/tmp/' . uniqid();
        $cmd = 'screen -S "' . $screenName . '" -X hardcopy ' . $tmpFile . '; sleep 1s;';
        $cmd .= 'if [ -e ' . $tmpFile . ' ]; then cat ' . $tmpFile . '; rm -f ' . $tmpFile . '; fi';

        return $this->exec($cmd);
    }

    /**
     * @{inheritdoc}
     */
    public function retrieveNbCore()
    {
        return $this->exec('nproc');
    }

    /**
     * @{inheritdoc}
     */
    public function resolvePath($path)
    {
        return str_replace('~/', $this->getHome(), $path);
    }
}
