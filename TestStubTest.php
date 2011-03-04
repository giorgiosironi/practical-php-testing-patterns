<?php
class TestStubTest extends PHPUnit_Framework_TestCase
{
    /**
     * This is the same Test Stub of the previous article.
     * It is hand rolled, but not Hard-Coded as we can pass the data in the 
     * constructor.
     */
    public function testCalculatesAverageVisitorsNumber()
    {
        $source = new StubDataSource(array(40000, 50000, 100000, 20000, 40000));
        $statistics = new Statistics($source);
        $this->assertEquals(50000, $statistics->getAverage());
    }

    /**
     * You can easily generate Stub with PHPUnit.
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
     * Sometimes you do not have an interface or abstract class to Stub out, 
     * but only the real implementation. The best thing to do here would be to
     * extract an interface with only the method actually called by the SUT.
     * However maybe you have ten Singletons to remove before lunch, and you 
     * just want to cover this class as it is not your priority to refactor it.
     * So you can provide a Stub for the concrete class for the time being.
     * In this case, we use my MockBuilder to override the original constructor
     * of the collaborator and avoid passing in collaborators' collaborators
     * in a neverending chain.
     */
    public function testCalculatesAverageVisitorsNumberWithGeneratedStubForTheConcreteClass()
    {
        $source = $this->getMockBuilder('GoogleAnalyticsDataSource')
                       ->disableOriginalConstructor()
                       ->getMock();
        $source->expects($this->any())
               ->method('getSamples')
               ->will($this->returnValue((array(40000, 50000, 100000, 20000, 40000))));
        $statistics = new Statistics($source);
        $this->assertEquals(50000, $statistics->getAverage());
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
 * The real implementation, this time with an obnoxious constructor.
 */
class GoogleAnalyticsDataSource implements DataSource
{
    public function __construct(SplQueue $queue, SomeOtherObject $object)
    {
        // ...
    }

    public function getSamples()
    {
        $result = curl("...");
        return $result;
    }
}

/**
 * An hard-coded Test Stub which is simply configured with 
 * predefined results. Even if the real web service is down or your
 * network does not work, this Stub allows the unit test to be executed.
 * It's also much faster than a bunch of network calls: in a single test you
 * may not notice them, but with a test suite of hundreths of tests...
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

