<?php
class LiteralValueTest extends PHPUnit_Framework_TestCase
{
    const POSITIVE = 1;
    const NEGATIVE = -1;

    public function setUp()
    {
        $this->sut = new SUT();
    }

    /**
     * The most basic of the tests.
     */
    public function testALiteralValueIsUsedForExpectationAndInput()
    {
        $this->assertEquals(5, $this->sut->sum(2, 3));
    }

    /**
     * If we change the result from 1 and -1 to "+1" or to an object,
     * we will have a way to quickly change this test. It's normal refactoring.
     */
    public function testASymbolicConstantIsExtractedToAvoidDuplicatingAValue()
    {
        $this->assertEquals(self::POSITIVE, $this->sut->sign(0));
        $this->assertEquals(self::POSITIVE, $this->sut->sign(1));
        $this->assertEquals(self::POSITIVE, $this->sut->sign(2));
        $this->assertEquals(self::POSITIVE, $this->sut->sign(10));
        $this->assertEquals(self::NEGATIVE, $this->sut->sign(-1));
        $this->assertEquals(self::NEGATIVE, $this->sut->sign(-2));
        $this->assertEquals(self::NEGATIVE, $this->sut->sign(-10));
    }
    
    /**
     * Describing cars that have to be deleted as old and rusty is simpler
     * for the mind RAM of the reader that using A, B and C. Choosing 
     * Self-Describing Values is an art that I have only started exploring.
     */
    public function testASelfDescribingValueIsUsedToMakeTheTestMoreReadable()
    {
        $this->sut->addVehicle('Old rusty car', 1980);
        $this->sut->addVehicle('Bus', 2000);
        $this->sut->addVehicle('Ferrari', 2010);
        $this->sut->deleteVehiclesUpTo(1990);
        // another Literal Value
        $this->assertEquals(2, $this->sut->getVehiclesCount());
    }
}

/**
 * Since we only want to describe the test code in this sample,
 * we use this catch-all SUT which accepts any call.
 */
class SUT
{
    public function __call($method, $arguments) {}
}
