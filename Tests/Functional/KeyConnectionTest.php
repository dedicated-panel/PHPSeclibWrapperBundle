<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Tests\Functional;

use Dedipanel\PHPSeclibWrapperBundle\Connection\ConnectionManager;
use Dedipanel\PHPSeclibWrapperBundle\Helper\KeyHelper;
use Dedipanel\PHPSeclibWrapperBundle\Connection\Connection;

class KeyConnectionTest extends \PHPUnit_Framework_TestCase
{
    const HOSTNAME = '127.0.0.1';
    const PORT     = 22;
    const USERNAME = 'dedipanel';
    const PASSWORD = 'dedipanel';
    const PRIVATE_KEY = <<<EOF
-----BEGIN RSA PRIVATE KEY-----
MIICXgIBAAKBgQDEnkHO7NyxQ/JQRCS4fn7fs7j9KOeL8fRaEgXT51Vi41kW5MaC
S2ouVPqrdEHr1/nDw+zxn+6LACnfG/zJ5WveKIbzsI1VhAAnBDXblE5B189yYIj7
JtSTRORbQM4pYEwWQCJAXvTCoY+swfStVgT4HoZX9psbCFflxnQoVOyKSwIDAQAB
AoGBAI1qikironw5M7K5oHO2P8jkOjyTzB6i0y5pYhmsfISYor5No92ZInDanETv
ZG6eM72zUNvlPSxq3LLlLWeFhJPMebNWmO91w/9+W5dE9MSa6xv2kG6N6lmesP2Z
3snNsm+KO6y2bzQdO0mLReAwqqnJXawyF/uQ7/0eUUIBByFhAkEA8okOyM0KPmJ2
CKVNXTHJoFn5SwL2D4Um1EYwP/LGvAdjEoTKcpW0LLhdweuvfxkR6TQ84USgrEcd
KIQYEj7AcQJBAM+Inl8d+8fEUgq0rdIStmctT+4N8LkWt0HAKKyZQm7vUtstVqEi
7VjITdRVz2sDoVJUnKKaHJ2vX5MiLGQxVHsCQQCFenCsikus8btMHs1pENGKcnoE
kmihOgKYqNg9GXvOV7JqqrJQRZuXURAofXFkXYPB+IHY6FAVAD5H4grtX6PBAkBc
0w2LG70fF/deJHxOpuIA+ipzHrcaAkgLV6iZsp8dQVw8/mVuA1JH0KfHHm58vg3s
5j19GRNNkEBebf2O/uV7AkEAnbUaqF867JXJjvGud3hkt5yUzFVtdAzVHm3dmVLQ
w8YkEl5DZFlSnSw/tRiC1X28Qr941JjKO+/tPGPZgc6aOw==
-----END RSA PRIVATE KEY-----
EOF;


    public function mockServer($key = false)
    {
        $mock = $this->getMock('Dedipanel\PHPSeclibWrapperBundle\Server\ServerInterface');

        if (!$key) {
            $mock
                ->expects($this->once())
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

        if ($key) {
            $pkey = new \Crypt_RSA();
            $pkey->loadKey(self::PRIVATE_KEY);

            $mock
                ->expects($this->once())
                ->method('getPrivateKey')
                ->will($this->returnValue($pkey))
            ;
        }
        else {
            $mock
                ->expects($this->any())
                ->method('getPassword')
                ->will($this->returnValue(self::PASSWORD))
            ;
        }

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

    public function testCreateKeyPair()
    {
        $server = $this->mockServer();
        $store  = $this->mockStore();
        $logger = $this->mockLogger();

        $manager = new ConnectionManager($logger);
        $helper = new KeyHelper($manager, $store);

        $this->assertTrue($helper->createKeyPair($server));
    }

    public function testConnectionWithKey()
    {
        $server = $this->mockServer(true);
        $logger = $this->mockLogger();

        $connection = new Connection($server, $logger);

        $this->assertTrue($connection->testSSHConnection());
    }
}
