<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Tests\Helper;

use Dedipanel\PHPSeclibWrapperBundle\Helper\KeyHelper;
use Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\KeyStoreInitializationException;

class KeyHelperTest extends \PHPUnit_Framework_TestCase
{
    private function mockStore($fake = false, $acting = false)
    {
        $mock = $this->getMock('Dedipanel\PHPSeclibWrapperBundle\KeyStore\KeyStoreInterface');
        
        $mock
            ->expects($this->once())
            ->method('isInitialized')
            ->will($this->returnValue(false))
        ;
        
        if ($fake) {
            $mock
                ->expects($this->once())
                ->method('initialize')
                ->will($this->throwException(new KeyStoreInitializationException()))
            ;
        }
        else {
            $mock
                ->expects($this->once())
                ->method('initialize')
            ;
            
            if ($acting) {
                $mock
                    ->expects($this->once())
                    ->method('store')
                    ->will($this->returnValue(true))
                ;
                
                $mock
                    ->expects($this->once())
                    ->method('remove')
                    ->will($this->returnValue(true))
                ;
            }
        }
        
        return $mock;
    }
    
    private function mockConnection()
    {
        $mock = $this->getMock('Dedipanel\PHPSeclibWrapperBundle\Connection\ConnectionInterface');
        
        $mock
            ->expects($this->once())
            ->method('addKey')
        ;
        
        $mock
            ->expects($this->once())
            ->method('removeKey')
        ;
        
        return $mock;
    }
    
    public function testConstructsWithCorrectStore()
    {
        $store  = $this->mockStore();
        $helper = new KeyHelper($store);
        
        $this->assertInstanceOf('Dedipanel\PHPSeclibWrapperBundle\Helper\KeyHelper', $helper);
    }
    
    /**
     * @expectedException \Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\KeyStoreInitializationException
     */
    public function testConstructsWithIncorrectStore()
    {
        $store  = $this->mockStore(true);
        $helper = new KeyHelper($store);
    }
    
    public function testCreateAndRemoveKeyPairWithCorrectStore()
    {
        $store  = $this->mockStore(false, true);
        $helper = new KeyHelper($store);
        
        $publicKey = $helper->createKeyPair('test');
        
        $this->assertNotEmpty($publicKey);
        $this->assertEquals('ssh-rsa', array_shift(explode(' ', $publicKey)));
        
        $this->assertTrue($helper->deleteKeyPair('test'));
    }
    
    public function testCreateAndRemoveKeyPairWithCorrectStoreAndCorrectConnection()
    {
        $store      = $this->mockStore(false, true);
        $connection = $this->mockConnection();
        $helper     = new KeyHelper($store);
        
        $publicKey = $helper->createKeyPair('test', $connection);
        
        $this->assertNotEmpty($publicKey);
        $this->assertEquals('ssh-rsa', array_shift(explode(' ', $publicKey)));
        
        $this->assertTrue($helper->deleteKeyPair('test', $connection));
    }
}
