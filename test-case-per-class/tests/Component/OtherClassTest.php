<?php
// in reality this would be done via autoloading and bootstrap file
require_once __DIR__ . '/../../classes/Component/OtherClass.php';

class Component_OtherClassTest extends PHPUnit_Framework_TestCase
{
    public function testCanInstanceAnObjectOfTheClassUnderTest()
    {
        $object = new Component_OtherClass;
        $this->assertTrue($object instanceof Component_OtherClass);
    }
}
