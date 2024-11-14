<?php

use PHPUnit\Framework\TestCase;
use WiseTest\Cnp;

class CnpTest extends TestCase
{
    public function testCnp(): void
    {
        // Letters
        $this->assertFalse(Cnp::isValid('testtesttest1'));
        // Missing character
        $this->assertFalse(Cnp::isValid('185081041356'));
        // Wrong character
        $this->assertFalse(Cnp::isValid('1850810413561'));
        // Correct
        $this->assertTrue(Cnp::isValid('1850810413560'));
        // Leap year wrong date
        $this->assertFalse(Cnp::isValid('5000230061412'));
        // Leap year
        $this->assertTrue(Cnp::isValid('5000229061411'));
        // Wrong date
        $this->assertFalse(Cnp::isValid('1970431095361'));
        // Correct
        $this->assertTrue(Cnp::isValid('1970430095360'));
    }
}