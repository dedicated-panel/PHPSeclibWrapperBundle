<?php

namespace Dedipanel\PHPSeclibWrapperBundle\Tests\KeyStore;

use Dedipanel\PHPSeclibWrapperBundle\KeyStore\FileKeyStore;
use Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\KeyStoreInitializationException;
use Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\KeyStoreNotInitializedException;
use Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\KeyAlreadyExistsException;

/**
 * @author Albin Kerouanton
 * @license http://opensource.org/licenses/MIT
 * @version 1.0
 */
class FileKeyStoreTest extends \PHPUnit_Framework_TestCase
{
    private $store;
    
    public function setUp()
    {
        $this->path  = __DIR__ . '/../tmp';
        $this->store = new FileKeyStore($this->path);
    }
    
    public function tearDown()
    {
        if (file_exists($this->path)) {
            $iterator = new \DirectoryIterator($this->path);
            
            foreach ($iterator AS $item) {
                if ($item->isDot()) continue;
                
                unlink($item->getPathname());
            }
            
            rmdir($this->path);
        }
    }
    
    public function testConstructsWithCorrectPath()
    {
        $this->assertInstanceOf('Dedipanel\PHPSeclibWrapperBundle\KeyStore\KeyStoreInterface', $this->store);
        $this->assertEquals($this->path, $this->store->getStorePath());
        $this->assertTrue($this->store->isInitialized());
    }
    
    public function testStoreRetrieveAndRemoveWithCorrectStore()
    {
        $name    = 'test';
        $content = 'fake content';

        $this->assertTrue($this->store->isInitialized());
        
        $this->assertTrue($this->store->store($name, $content));
        $this->assertEquals($content, $this->store->retrieve($name));
        $this->assertTrue($this->store->remove($name));
    }
    
    /**
     * @expectedException \Dedipanel\PHPSeclibWrapperBundle\KeyStore\Exception\KeyAlreadyExistsException
     */
    public function testStoreTwoKeyWithSameName()
    {
        $this->store->initialize();
        $this->assertTrue($this->store->isInitialized());
        
        $this->store->store('test', '');
        $this->store->store('test', '');
    }
}
