<?php
// in reality this would be done via autoloading and bootstrap file
require_once __DIR__ . '/../classes/MyClass.php';

class MyClassTest extends PHPUnit_Framework_TestCase
{
    public function testCanInstanceAnObjectOfTheClassUnderTest()
    {
        $object = new MyClass;
        $this->assertTrue($object instanceof MyClass);
    }
}
