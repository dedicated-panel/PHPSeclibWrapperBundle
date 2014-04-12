<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Tests\Helper;

use Dedipanel\PHPSeclibWrapperBundle\Helper\KeyHelper;
use Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\KeyStoreInitializationException;

class KeyHelperTest extends \PHPUnit_Framework_TestCase
{
    public function mockManager()
    {
        return $this->getMock('Dedipanel\PHPSeclibWrapperBundle\Connection\ConnectionManagerInterface');
    }

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

    public function testConstructsWithCorrectStore()
    {
        $manager = $this->mockManager();
        $store   = $this->mockStore();
        $helper  = new KeyHelper($manager, $store);

        $this->assertInstanceOf('Dedipanel\PHPSeclibWrapperBundle\Helper\KeyHelper', $helper);
    }
    
    /**
     * @expectedException \Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\KeyStoreInitializationException
     */
    public function testConstructsWithIncorrectStore()
    {
        $manager = $this->mockManager();
        $store   = $this->mockStore(true);

        $helper  = new KeyHelper($manager, $store);
    }
}
