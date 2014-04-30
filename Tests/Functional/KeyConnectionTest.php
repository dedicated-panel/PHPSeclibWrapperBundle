<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Tests\Functional;

use Dedipanel\PHPSeclibWrapperBundle\Connection\ConnectionManager;
use Dedipanel\PHPSeclibWrapperBundle\Helper\KeyHelper;
use Dedipanel\PHPSeclibWrapperBundle\Connection\Connection;
use Dedipanel\PHPSeclibWrapperBundle\Server\Server;

class KeyConnectionTest extends \PHPUnit_Framework_TestCase
{
    private $privateKey;
    private $publicKey;

    const HOSTNAME = '127.0.0.1';
    const PORT     = 22;
    const USERNAME = 'dedipanel';
    const PASSWORD = 'dedipanel';


    public function __construct()
    {
        $this->privateKey = file_get_contents(__DIR__ . '/../id_rsa');
        $this->publicKey = file_get_contents(__DIR__ . '/../id_rsa.pub');
    }

    public function mockServer($key = false)
    {
        $mock = $this->getMock('Dedipanel\PHPSeclibWrapperBundle\Server\ServerInterface');

        if (!$key) {
            $mock
                ->expects($this->any())
                ->method('setPrivateKeyName')
            ;
        }

        $mock
            ->expects($this->any())
            ->method('getServerIP')
            ->will($this->returnValue(self::HOSTNAME))
        ;

        $mock
            ->expects($this->any())
            ->method('getPort')
            ->will($this->returnValue(self::PORT))
        ;

        $mock
            ->expects($this->any())
            ->method('getUsername')
            ->will($this->returnValue(self::USERNAME))
        ;

        if (!$key) {
            $mock
                ->expects($this->any())
                ->method('getPassword')
                ->will($this->returnValue(self::PASSWORD))
            ;
        }

        $pkey = new \Crypt_RSA();
        $pkey->loadKey($this->privateKey);

        $mock
            ->expects($this->any())
            ->method('getPrivateKey')
            ->will($this->returnValue($pkey))
        ;

        return $mock;
    }

    public function mockStore()
    {
        $mock = $this->getMock('Dedipanel\PHPSeclibWrapperBundle\KeyStore\KeyStoreInterface');

        $mock
            ->expects($this->once())
            ->method('isInitialized')
            ->will($this->returnValue(true))
        ;

        $mock
            ->expects($this->once())
            ->method('store')
            ->will($this->returnValue(true))
        ;

        return $mock;
    }

    public function mockLogger()
    {
        return $this->getMock('Psr\Log\NullLogger');
    }

    public function testSamePublicKey()
    {
        $key = new \Crypt_RSA;
        $key->loadKey($this->privateKey);

        $this->assertEquals($this->privateKey, $key->getPrivateKey());
        $this->assertEquals($this->publicKey, $key->getPublicKey(CRYPT_RSA_PUBLIC_FORMAT_OPENSSH));
    }

    public function testCreateAndDeleteKeyPair()
    {
        $store  = $this->mockStore();
        $logger = $this->mockLogger();

        $server = new Server();
        $server->setIP(self::HOSTNAME);
        $server->setUsername(self::USERNAME);
        $server->setPassword(self::PASSWORD);

        $manager = new ConnectionManager($logger);
        $helper = new KeyHelper($manager, $store);

        $this->assertTrue($helper->createKeyPair($server));

        $server->setPassword(null);

        $this->assertTrue($helper->deleteKeyPair($server));
    }

    public function testConnectionWithKey()
    {
        $server = $this->mockServer(true);
        $logger = $this->mockLogger();

        $connection = new Connection($server, $logger);

        $this->assertTrue($connection->testSSHConnection());
    }
}
