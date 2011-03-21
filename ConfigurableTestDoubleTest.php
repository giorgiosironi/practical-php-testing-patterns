<?php
/**
 * We expand the example of the Test Stub article in order to discuss 
 * configuration more than stubbing.
 */
class ConfigurableTestDoubleTest extends PHPUnit_Framework_TestCase
{
    /**
     * This Test Double is hand rolled and configurable: the constructor
     * is the way chosen to perform configuration.
     */
    public function testCalculatesAverageVisitorsNumber()
    {
        $source = new StubDataSource(array(40000, 50000, 100000, 20000, 40000));
        $statistics = new Statistics($source);
        $this->assertEquals(50000, $statistics->getAverage());
    }

    /**
     * This Configurable Test Double use a separate interface (addSample())
     * for the sake of configuration. This interface is oriented to ease of 
     * testing more than to the design of the production code.
     */
    public function testCalculatesAverageVisitorsNumberFromAnotherTestDouble()
    {
        $source = new EasilyConfigurableStubDataSource;
        $source->addSample(40000)->addSample(50000)->addSample(60000);
        $statistics = new Statistics($source);
        $this->assertEquals(50000, $statistics->getAverage());
    }

    /**
     * You can easily generate Stub with PHPUnit. This time, the configuration
     * is done via the expects() method, which return a configuration object.
     */
    public function testCalculatesAverageVisitorsNumberWithGeneratedStub()
    {
        $source = $this->getMock('DataSource');
        $source->expects($this->any())
               ->method('getSamples')
               ->will($this->returnValue((array(40000, 50000, 100000, 20000, 40000))));
        $statistics = new Statistics($source);
        $this->assertEquals(50000, $statistics->getAverage());
    }

    /**
     * When you don't like PHPUnit's interface, or it is a too low level
     * of abstraction, you can always wrap it and build something more friendly.
     */
    public function testCalculatesAverageVisitorsNumberWithGeneratedStubAndAFriendlyInterface()
    {
        $source = $this->getMock('DataSource');
        $this->addSamples($source, array(40000, 50000, 100000, 20000, 40000));

        $statistics = new Statistics($source);
        $this->assertEquals(50000, $statistics->getAverage());
    }

    private function addSamples($mock, array $samples)
    {
        $mock->expects($this->any())
               ->method('getSamples')
               ->will($this->returnValue($samples));
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
        return array_sum($samples) / count($samples);
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

/**
 * An Configurable Test Double configured via constructor.
 */
class StubDataSource implements DataSource
{
    private $samples;

    public function __construct(array $samples)
    {
        $this->samples = $samples;
    }

    public function getSamples()
    {
        return $this->samples;
    }
}

/**
 * Another Configurable Test Double, with a separate Configuration Interface.
 */
class EasilyConfigurableStubDataSource implements DataSource
{
    private $samples = array();

    /**
     * @return EasilyConfigurableStubDataSource
     */
    public function addSample($sample)
    {
        $this->samples[] = $sample;
        return $this;
    }

    public function getSamples()
    {
        return $this->samples;
    }
}
