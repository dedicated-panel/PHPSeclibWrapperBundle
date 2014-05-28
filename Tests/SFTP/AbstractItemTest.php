<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Tests\SFTP;

/**
 * @group sftp
 * @group sftp_path
 */
class AbstractItemTest extends \PHPUnit_Framework_TestCase
{
    public function mockServer()
    {
        $mock = $this->getMock('Dedipanel\PHPSeclibWrapperBundle\Server\ServerInterface');

        $mock
            ->expects($this->any())
            ->method('getServerIP')
            ->will($this->returnValue(HOSTNAME))
        ;

        $mock
            ->expects($this->any())
            ->method('getPort')
            ->will($this->returnValue(PORT))
        ;

        $mock
            ->expects($this->any())
            ->method('getUsername')
            ->will($this->returnValue(USERNAME))
        ;

        $mock
            ->expects($this->any())
            ->method('getPassword')
            ->will($this->returnValue(PASSWORD))
        ;

        $mock
            ->expects($this->any())
            ->method('getHome')
            ->will($this->returnValue(HOME))
        ;

        return $mock;
    }

    public function mockConnection()
    {
        $mock = $this->getMock('Dedipanel\PHPSeclibWrapperBundle\Connection\ConnectionInterface');

        $mock
            ->expects($this->any())
            ->method('getServer')
            ->will($this->returnValue($this->mockServer()))
        ;

        return $mock;
    }

    /**
     * @group sftp_item_path_validator
     */
    public function testValidateCorrectPath()
    {
        $path = '~/test';

        $mock = $this->getMockForAbstractClass(
            'Dedipanel\PHPSeclibWrapperBundle\SFTP\AbstractItem',
            array($this->mockConnection(), $path)
        );

        $this->assertTrue($mock->validatePath());
    }

    /**
     * @group sftp_item_path_validator
     */
    public function testValidateIncorrectPathWithBackdot()
    {
        $mock = $this->getMockForAbstractClass(
            'Dedipanel\PHPSeclibWrapperBundle\SFTP\AbstractItem',
            array(), '', false, true, true, array('getFullPath')
        );
        $mock
            ->expects($this->once())
            ->method('getFullPath')
            ->will($this->returnValue('/home/testing/../'))
        ;

        $this->assertFalse($mock->validatePath());
    }

    /**
     * @group sftp_item_path_validator
     */
    public function testValidateIncorrectPath()
    {
        $mock = $this->getMockForAbstractClass(
            'Dedipanel\PHPSeclibWrapperBundle\SFTP\AbstractItem',
            array(), '', false, true, true, array('getFullPath')
        );
        $mock
            ->expects($this->once())
            ->method('getFullPath')
            ->will($this->returnValue('/etc/passwd'))
        ;

        $this->assertFalse($mock->validatePath());
    }

    /**
     * @group sftp_item_constructor
     */
    public function testConstructorWithRootPath()
    {
        $path = '~/';

        $mock = $this->getMockForAbstractClass(
            'Dedipanel\PHPSeclibWrapperBundle\SFTP\AbstractItem',
            array($this->mockConnection(), $path)
        );

        $this->assertEquals('/home/' . USERNAME . '/', $mock->getFullPath());
    }

    /**
     * @group sftp_item_constructor
     */
    public function testConstructorWithFileAtRootPath()
    {
        $path = '~/.bashrc';

        $mock = $this->getMockForAbstractClass(
            'Dedipanel\PHPSeclibWrapperBundle\SFTP\AbstractItem',
            array($this->mockConnection(), $path)
        );

        $this->assertEquals('/home/' . USERNAME . '/.bashrc', $mock->getFullPath());
    }

    /**
     * @group sftp_item_constructor
     */
    public function testConstructorWithDirAtRootPath()
    {
        $path = '~/test/';

        $mock = $this->getMockForAbstractClass(
            'Dedipanel\PHPSeclibWrapperBundle\SFTP\AbstractItem',
            array($this->mockConnection(), $path)
        );

        $this->assertEquals('/home/' . USERNAME . '/test', $mock->getFullPath());
    }

    /**
     * @group sftp_item_constructor
     */
    public function testConstructorWithFileAtDir()
    {
        $path = '~/test/test.php';

        $mock = $this->getMockForAbstractClass(
            'Dedipanel\PHPSeclibWrapperBundle\SFTP\AbstractItem',
            array($this->mockConnection(), $path)
        );

        $this->assertEquals('/home/' . USERNAME . '/test/test.php', $mock->getFullPath());
    }

    /**
     * @group sftp_item_constructor
     * @expectedException Dedipanel\PHPSeclibWrapperBundle\SFTP\Exception\InvalidPathException
     */
    public function testConstructorWithBackdot()
    {
        $path = '~/..';

        $mock = $this->getMockForAbstractClass(
            'Dedipanel\PHPSeclibWrapperBundle\SFTP\AbstractItem',
            array($this->mockConnection(), $path)
        );

        $this->assertEquals('/home/' . USERNAME . '/', $mock->getFullPath());
    }
}
