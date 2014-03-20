<?php
namespace PhpWord\Tests;

use PhpOffice\PhpWord\Settings;

/**
 * @package                     PhpWord\Tests
 * @coversDefaultClass          PhpOffice\PhpWord\Settings
 * @runTestsInSeparateProcesses
 */
class SettingsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::setCompatibility
     * @covers ::getCompatibility
     */
    public function testGetSetCompatibility()
    {
        $this->assertTrue(Settings::getCompatibility());
        $this->assertTrue(Settings::setCompatibility(false));
        $this->assertFalse(Settings::getCompatibility());
        $this->assertFalse(Settings::setCompatibility('Non boolean'));
    }
}