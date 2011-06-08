<?php
class DummyObjectTest extends PHPUnit_Framework_TestCase
{
    /**
     * The Test Method names describe the different ways to produce
     * a Dummy Object (or just a Dummy value).
     */
    public function testNullOrStringsCanBeUsedAsADummyIfThereAreNoTypeHints()
    {
        $sut = new MostDynamicSut(null);
        $sut = new MostDynamicSut('this should not be called');
    }

    public function testNullCanBeUsedAsADummyIfItsTheDefaultForTypeHint()
    {
        // I prefer an explicit parameter; also there may be other parameters after that
        $sut = new TypeHintedWithDefaultSut(null);
    }

    public function testAnInlineClassCanBeUsedToInstantiateADummyObject()
    {
        $sut = new FullyDefensiveSut(new DummyIterator);
    }

    public function testADynamicallyGeneratedDummyObjectCanBeBuiltByPHPUnit()
    {
        $dummy = $this->getMock('Iterator');
        $sut = new FullyDefensiveSut($dummy);
    }

    public function testPHPUnitCanOverrideTheConstructorOfADummy()
    {
        $dummy = $this->getMockBuilder('ArrayIterator')
                      ->disableOriginalConstructor()
                      ->getMock();
        $sut = new FullyDefensiveSut($dummy);
    }

    public function testPHPUnitCanCheckThatTheDummyMethodsAreNeverCalled()
    {
     // often if someone calls it, lack of return value (null) causes explosion anyway.but if it's a void...
        $dummy = $this->getMock('Iterator');
        $dummy->expects($this->never())
              ->method('current');
        $sut = new FullyDefensiveSut($dummy);
    }
}


class MostDynamicSut
{
    public function __construct($iterator) { }
}

class TypeHintedWithDefaultSut
{
    public function __construct(Iterator $iterator = null) { }
}

class FullyDefensiveSut
{
    public function __construct(Iterator $iterator) { }
}

class DummyIterator extends ArrayIterator
{
    public function __construct() {}
}
