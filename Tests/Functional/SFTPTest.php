<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Tests\Functional;

use Dedipanel\PHPSeclibWrapperBundle\Connection\Connection;
use Dedipanel\PHPSeclibWrapperBundle\SFTP\Directory;
use Dedipanel\PHPSeclibWrapperBundle\SFTP\File;

/**
 * @group sftp
 */
class SFTPTest extends \PHPUnit_Framework_TestCase
{
    const HOSTNAME = HOSTNAME;
    const PORT     = PORT;
    const USERNAME = USERNAME;
    const PASSWORD = PASSWORD;
    const HOME     = HOME;
    const FILE_CONTENT = "test\n\naze\t\naze";

    public function mockServer()
    {
        $mock = $this->getMock('Dedipanel\PHPSeclibWrapperBundle\Server\ServerInterface');

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

        $mock
            ->expects($this->any())
            ->method('getPassword')
            ->will($this->returnValue(self::PASSWORD))
        ;

        $mock
            ->expects($this->any())
            ->method('getHome')
            ->will($this->returnValue(self::HOME))
        ;

        return $mock;
    }

    public function mockLogger()
    {
        return $this->getMock('Psr\Log\NullLogger');
    }

    public function getConnection()
    {
        $server = $this->mockServer();
        $logger = $this->mockLogger();

        return new Connection($server, $logger);
    }

    public function testRetrieveFile()
    {
        $conn = $this->getConnection();
        $file = new File($conn, '~/.bashrc');

        $this->assertNotEmpty($file->retrieve());
        $this->assertNotEquals(0, $file->getSize());
    }

    public function testRetrieveDirectory()
    {
        $conn = $this->getConnection();
        $dir  = new Directory($conn, '~/');

        $this->assertNotEmpty($dir->retrieve());
    }

    public function testCreateDirectory()
    {
        $conn = $this->getConnection();
        $dir  = new Directory($conn, '~/test');

        $this->assertTrue($dir->create());
        $this->assertEquals(2, count($dir->retrieve()));
    }

    public function testCreateFile()
    {
        $content = self::FILE_CONTENT;

        $conn = $this->getConnection();
        $dir  = new Directory($conn, '~/test');
        $file = new File($conn, '~/test/test.txt', self::HOME, true, $content);

        $this->assertEquals(2, count($dir->retrieve()));
        $this->assertTrue($file->create());
        $this->assertEquals(3, count($dir->retrieve()));
        $this->assertEquals($content, $file->retrieve());
    }

    public function testRenameFile()
    {
        $conn = $this->getConnection();
        $file = new File($conn, '~/test/test.txt');
        $file->setName('test.aze');

        $this->assertTrue($file->rename());
    }

    public function testRenameDirectory()
    {
        $conn = $this->getConnection();
        $file = new Directory($conn, '~/test/');
        $file->setName('test2');

        $this->assertTrue($file->rename());
    }

    public function testMoveFile()
    {
        $conn = $this->getConnection();
        $dir  = new Directory($conn, '~/test2');
        $file = new File($conn, '~/test2/test.aze');
        $file->setPath('~/');

        $this->assertEquals(3, count($dir->retrieve()));
        $this->assertTrue($file->rename());
        $this->assertEquals(2, count($dir->retrieve()));
    }

    public function testMoveSubdirectory()
    {
        $conn = $this->getConnection();
        $dir  = new Directory($conn, '~/test2');
        $subdir = new Directory($conn, '~/test2/test');

        $this->assertEquals(2, count($dir->retrieve()));
        $this->assertTrue($subdir->create());
        $this->assertEquals(3, count($dir->retrieve()));
        $this->assertEquals(2, count($subdir->retrieve()));

        $subdir->setPath('~/');
        $this->assertTrue($subdir->rename());
        $this->assertEquals(2, count($dir->retrieve()));
    }

    public function testDeleteFile()
    {
        $conn = $this->getConnection();
        $file = new File($conn, '~/test.aze');

        $this->assertTrue($file->delete());
    }

    public function testDeleteDirectory()
    {
        $conn = $this->getConnection();
        $dir  = new Directory($conn, '~/test2');
        $movedDir = new Directory($conn, '~/test');

        $this->assertTrue($movedDir->delete());
        $this->assertTrue($dir->delete());
    }
}
