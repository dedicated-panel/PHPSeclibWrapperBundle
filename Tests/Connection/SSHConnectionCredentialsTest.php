<?php

namespace DP\PHPSeclibWrapperBundle\Tests\Connection;

use DP\PHPSeclibWrapperBundle\Server\Server;
use DP\PHPSeclibWrapperBundle\Connection\Connection;

class SSHConnectionCredentialsTest extends \PHPUnit_Framework_TestCase
{
    public function getGoodServer()
    {
        $server = new Server;
        $server->setHostname(SERVER_HOST);
        $server->setPort(SERVER_PORT);
        $server->setUsername(SERVER_USER);
        $server->setPassword(SERVER_PASSWD);

        return $server;
    }

    public function testGoodCredentials()
    {
        $server = $this->getGoodServer();

        $connection = new Connection($server);
        $ssh = $connection->getSSH();

        $this->assertEquals('Net_SSH2', get_class($ssh));
    }

    public function testNoCredentials()
    {
        $this->setExpectedException('DP\PHPSeclibWrapperBundle\Connection\Exception\IncompleteLoginCredentialsException');

        $server = new Server;
        $server->setHostname(SERVER_HOST);
        $server->setPort(SERVER_PORT);

        $connection = new Connection($server);
        $ssh = $connection->getSSH();
    }

    public function testBadUsername()
    {
        $this->setExpectedException('DP\PHPSeclibWrapperBundle\Connection\Exception\ConnectionErrorException');

        $server = new Server;
        $server->setHostname(SERVER_HOST);
        $server->setPort(SERVER_PORT);
        $server->setUsername('bad-username');
        $server->setPassword('bad-password');

        $connection = new Connection($server);
        $ssh = $connection->getSSH();
    }

    public function testBadPassword()
    {
        $this->setExpectedException('DP\PHPSeclibWrapperBundle\Connection\Exception\ConnectionErrorException');

        $server = new Server;
        $server->setHostname(SERVER_HOST);
        $server->setPort(SERVER_PORT);
        $server->setUsername(SERVER_USER);
        $server->setPassword('bad-password');

        $connection = new Connection($server);
        $ssh = $connection->getSSH();
    }

    public function testGoodKey()
    {
        // @TODO: Implémenter la fonction de test
        /*$server = new Server;
        $server->setHostname(SERVER_HOST);
        $server->setPort(SERVER_PORT);
        $server->setUsername(SERVER_USER);
        $server->setPrivateKey(SERVER_KEY);

        $connection = new Connection($server);
        $ssh = $connection->getSSH();*/
    }

    public function testBadKey()
    {
        $this->setExpectedException('DP\PHPSeclibWrapperBundle\Connection\Exception\ConnectionErrorException');

        $server = new Server;
        $server->setHostname(SERVER_HOST);
        $server->setPort(SERVER_PORT);
        $server->setUsername(SERVER_USER);
        $server->setPrivateKey('aze');

        $connection = new Connection($server);
        $ssh = $connection->getSSH();
    }

    public function testExec()
    {
        $server = $this->getGoodServer();
        $connection = new Connection($server);

        $this->assertEquals('1 2 3 4 5', $connection->exec('echo 1 2 3 4 5'));
    }

    public function testConnectionTest()
    {
        $server = $this->getGoodServer();
        $connection = new Connection($server);

        $this->assertTrue($connection->connectionTest());
    }
}
