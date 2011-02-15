<?php
class GarbageCollectedTeardownTest extends PHPUnit_Framework_TestCase
{
    private $deletedFixture;
    private $abandonedFixture;

    public function setUp()
    {
        $this->deletedFixture = new SomeFixture(1);
        $this->abandonedFixture = new SomeFixture(2);
    }

    public function testFirst()
    {
    }

    public function testSecond()
    {
    }

    public function tearDown()
    {
        // this fixture will be garbage-collected at the end of each test
        unset($this->deletedFixture);
        // since we do not touch $this->abandonedFixture, its collection
        // is not predictable. It can happen at any time after the tests execution.
    }
}

class SomeFixture
{
    private $number;
    public function __construct($number)
    {
        $this->number = $number;
    }

    public function __destruct()
    {
        echo "Cleaning up fixture $this->number.\n";
    }
}
