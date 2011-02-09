<?php
class CustomAssertionsTest extends PHPUnit_Framework_TestCase
{
    public function testCustomEqualityAssertion()
    {
        $this->assertPhoneNumbersAreEqual('5551234', '555 12 33');
    }

    public function assertPhoneNumbersAreEqual($expected, $actual)
    {
        $expected = $this->normalizePhoneNumber($expected);
        $actual = $this->normalizePhoneNumber($actual);
        $this->assertEquals($expected, $actual, "The $actual phone number is not equal to the expected $expected.");
    }

    private function normalizePhoneNumber($number)
    {
        return str_replace(' ', '', $number);
    }

    public function testObjectAttributeEqualityAssertion()
    {
        $fordCar = new Car(10);
        $peugeoutCar = new Car(5);

        $this->assertCarsHaveTheSameSpeed($peugeoutCar, $fordCar);
    }

    public function assertCarsHaveTheSameSpeed(Car $firstCar, Car $secondCar)
    {
        $firstSpeed = $firstCar->getSpeed();
        $secondSpeed = $secondCar->getSpeed();
        $this->assertEquals($firstSpeed, $secondSpeed, "The speeds of the two cars differ: they are $firstSpeed and $secondSpeed.");
    }

    public function testDomainAssertion()
    {
        $car = new Car(60);

        $this->assertCarIsBelowTheUrbanSpeedLimit($car);
    }

    public function assertCarIsBelowTheUrbanSpeedLimit(Car $car)
    {
        $this->assertTrue($car->getSpeed() <= 50, "Car's speed is too high: ". $car->getSpeed() . ".");
    }

    public function testDiagnosticAssertion()
    {
        $expectedJson = '{"engine" : "good", "hood" : "awful", "tires" : "normal"}';
        $actualJson = '{"engine" : "good", "hood" : "good", "tires" : "normal"}';
        $this->assertJsonEquals($expectedJson, $actualJson);
    }

    private function assertJsonEquals($expectedJson, $actualJson)
    {
        $expectedJson = json_decode($expectedJson);
        $actualJson = json_decode($actualJson);
        var_dump($expectedJson, $actualJson);
        // taking advantage of assertEquals(object, object)
        $this->assertEquals($expectedJson, $actualJson);
    }

    public function testVerificationMethod()
    {
        $car = new Car(10);
        $this->assertNewSpeedCreatesANewValueObject($car);
    }

    private function assertNewSpeedCreatesANewValueObject(Car $car)
    {
        $oldSpeed = $car->getSpeed();
        $newSpeed = $oldSpeed + 10;
        $newCar = $car->setSpeed($newSpeed);
        $this->assertEquals($oldSpeed, $car->getSpeed());
        $this->assertTrue($newCar instanceof Car, 'Car::setSpeed() does not return a new Car object.');
        $this->assertEquals($newSpeed, $newCar->getSpeed());
    }
}

/**
 * A simple Value Object that our tests use.
 */
class Car
{
    private $speed;

    public function __construct($speed)
    {
        $this->speed = $speed;
    }

    public function setSpeed($speed)
    {
        // commented to make the test fail and show the assertion message
        //return new Car($speed);
    }

    public function getSpeed()
    {
        return $this->speed;
    }
}
