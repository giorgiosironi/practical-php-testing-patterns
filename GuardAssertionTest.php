<?php

class GuardAssertionTest extends PHPUnit_Framework_TestCase
{
    public function testTheObjectWhichWasnt()
    {
        $object = null;

        // from PHPUnit 3.5 you can also use $this->assertInstanceOf()
        $this->assertTrue($object instanceof MyClass, 'The object is not an instance of MyClass.');
        
        $this->assertEquals(42, $object->getSomeField());
    }

    public function testTheArrayWhichIsEmptyAndThenFull()
    {
        $arrayObject = $this->getACollection();

        $this->assertEquals(0, count($arrayObject), 'The collection is not empty.');
        $arrayObject[] = 42;
        $this->assertEquals(1, count($arrayObject), 'The collection does not accept new elements.');
    }

    public function testTheDriverWhichIsNotAvailable()
    {
        $this->assertMysqlDatabaseIsAvailable();
        $connection = new PDO('mysql:...');
    }

    private function getACollection()
    {
        return new ArrayObject();
    }

    private function assertMysqlDatabaseIsAvailable()
    {
        // for simplicity, let's say some configuration is missing 
        // and we check it here
        if (true) {
            $this->markTestSkipped('The MySQL database for testing is not available.');
        }
    }
}

