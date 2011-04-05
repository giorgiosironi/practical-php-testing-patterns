<?php
class TestUtilityMethodBeforeTest extends PHPUnit_Framework_TestCase
{
    public function testIsCountable()
    {
        $array = new ArrayObject();
        $n = 6;
        for ($i = 0; $i < $n; $i++) {
            $array[] = 'dummy';
        }
        $this->assertEquals($n, count($array));
    }

    public function testAfterCreationContainsNoElements()
    {
        $array = new ArrayObject();
        $this->assertEquals(0, count($array));
    }

    public function testAfterAdditionContainsTheElement()
    {
        $array = new ArrayObject();
        $array[] = 'foo';
        $this->assertEquals(1, count($array));
        $index = array_search('foo', $array->getArrayCopy());
        $this->assertSame(0, $index);
    }

    public function testSortsItselfAccordingToValues()
    {
        $array = new ArrayObject(array('v' => 'value', 'f' => 'foo'));
        $array->natsort();
        $this->assertEquals('foo', $array->getIterator()->current());
        $index = array_search('foo', $array->getArrayCopy());
        $this->assertSame('f', $index);
        $index = array_search('value', $array->getArrayCopy());
        $this->assertSame('v', $index);
    }

    public function testAfterRemovalContainsNoElements()
    {
        $array = new ArrayObject(array('foo'));
        unset($array[0]);
        $this->assertEquals(0, count($array));
    }
}

class TestUtilityMethodAfterTest extends PHPUnit_Framework_TestCase
{
    public function testIsCountable()
    {
        $array = new ArrayObject();
        $this->fillWithValues($array, 6);
        $this->assertCountIs(6, $array);
    }

    public function testAfterCreationContainsNoElements()
    {
        $array = new ArrayObject();
        $this->assertIsEmpty($array);
    }

    public function testAfterAdditionContainsTheElement()
    {
        $array = new ArrayObject();
        $array[] = 'foo';
        $this->assertCountIs(1, $array);
        $this->assertKeyIs(0, 'foo', $array);
    }

    public function testSortsItselfAccordingToValues()
    {
        $array = new ArrayObject(array('v' => 'value', 'f' => 'foo'));
        $array->natsort();
        $this->assertEquals('foo', $array->getIterator()->current());
        $this->assertKeyIs('f', 'foo', $array);
        $this->assertKeyIs('v', 'value', $array);
    }

    public function testAfterRemovalContainsNoElements()
    {
        $array = new ArrayObject(array('foo'));
        unset($array[0]);
        $this->assertIsEmpty($array);
    }

    /**
     * Sort of Creation Method; although it does not instance the object,
     * it prepares it for the test.
     */
    private function fillWithValues(ArrayObject $array, $howMany)
    {
        for ($i = 0; $i < $howMany; $i++) {
            $array[] = 'dummy';
        }
    }

    /**
     * Three Assertion Methods.
     */
    private function assertIsEmpty(Countable $array)
    {
        $this->assertCountIs(0, $array);
    }

    private function assertCountIs($number, Countable $array)
    {
        $actual = count($array);
        $this->assertEquals($number, $actual, "The count is not $number but $actual.");
    }

    private function assertKeyIs($expectedKey, $value, ArrayObject $array)
    {
        $key = $this->keyOf($value, $array);
        $this->assertSame($expectedKey, $key);
    }

    /**
     * A simple Test Utility Method to give some meaningful name
     * to array_search()
     */
    private function keyOf($value, ArrayObject $array)
    {
        return array_search($value, $array->getArrayCopy());
    }
}
