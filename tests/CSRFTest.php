<?php

namespace rockunit;


use rock\csrf\CSRF;

class CSRFTest extends \PHPUnit_Framework_TestCase
{
    /** @var  CSRF */
    protected $csrf;

    protected function setUp()
    {
        parent::setUp();

        if (!isset($this->csrf)) {
            $this->csrf = new CSRF();
        }
        $this->csrf->enableCsrfValidation = true;
    }

    public function testGenerate()
    {
        $this->assertNotEmpty($this->csrf->get());

        $this->csrf->enableCsrfValidation = false;
        $this->assertNull($this->csrf->get());
    }

    /**
     * @depends testGenerate
     */
    public function testValid()
    {
        $this->assertTrue($this->csrf->valid($this->csrf->get()));
        $this->assertFalse($this->csrf->valid());

        $this->csrf->enableCsrfValidation = false;
        $this->assertTrue($this->csrf->valid());
    }

    /**
     * @depends testGenerate
     */
    public function testValidAsHeader()
    {
        $this->assertFalse($this->csrf->valid());
        $key = 'HTTP_' . str_replace('-', '_', strtoupper(CSRF::CSRF_HEADER));;
        $_SERVER[$key] = $this->csrf->get();
        $this->assertTrue($this->csrf->valid());
    }

    /**
     * @depends  testValid
     */
    public function testRemove()
    {
        $this->assertTrue($this->csrf->exists());
        $this->csrf->remove();
        $this->assertFalse($this->csrf->exists());
    }
}
