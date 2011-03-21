<?php
/**
 * We expand the example of the Test Stub article in order to discuss 
 * Hard-Coded Test Doubles.
 */
class HardCodedTestDoubleTest extends PHPUnit_Framework_TestCase
{
    /**
     * This Test Double is hard-coded: since we have to define a class
     * for it externally to this Testcase Class, we lose a bit of readability.
     */
    public function testCalculatesAverageVisitorsNumber()
    {
        $source = new FixedDataSource();
        $statistics = new Statistics($source);
        $this->assertEquals(50000, $statistics->getAverage());
    }

    /**
     * Sometimes a feel of what the Test Double does can be presented
     * by choosing a meaningful name.
     */
    public function testWhenThereAreNoSamplesRemainsAtZeroVisits()
    {
        $source = new EmptyDataSource();
        $statistics = new Statistics($source);
        $this->assertEquals(0, $statistics->getAverage());
    }

    /**
     * And hard-coding can take place also in the name of the class, at a very 
     * low level of abstraction.
     */
    public function testCalculatesAverageVisitorsNumberUsingATestDoubleWithMeaningfulName()
    {
        $source = new DataSource40000And50000And60000();
        $statistics = new Statistics($source);
        $this->assertEquals(50000, $statistics->getAverage());
    }


}

/**
 * This is the contract of the source, the collaborator for the SUT.
 * It's not mandatory to have an explicit interface, particularly in PHP,
 * but it helps.
 */
interface DataSource
{
    /**
     * @return array    numerical values of visitors to this website
     */
    public function getSamples();
}

class FixedDataSource implements DataSource
{
    public function getSamples()
    {
        return array(40000, 50000, 100000, 20000, 40000);
    }
}

class EmptyDataSource implements DataSource
{
    public function getSamples()
    {
        return array();
    }
}

class DataSource40000And50000And60000 implements DataSource
{
    public function getSamples()
    {
        return array(40000, 50000, 100000, 20000, 40000);
    }
}

/**
 * The System Under Test (same as previous article).
 * It requires a DataSource collaborator to be used in production,
 * or to be tested.
 */
class Statistics
{
    private $source;

    public function __construct(DataSource $source)
    {
        $this->source = $source;
    }

    public function getAverage()
    {
        $samples = $this->source->getSamples();
        if (!$samples) {
            return 0;
        } 
        return array_sum($samples) / count($samples);
    }
}
