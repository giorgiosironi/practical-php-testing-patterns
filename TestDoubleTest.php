<?php
class TestDoubleTest extends PHPUnit_Framework_TestCase
{
    public function testCalculatesAverageVisitorsNumber()
    {
        $source = new StubDataSource(array(40000, 50000, 100000, 20000, 40000));
        $statistics = new Statistics($source);
        $this->assertEquals(50000, $statistics->getAverage());
    }
}

/**
 * The System Under Test. It requires a DataSource collaborator to be used
 * in production, or to be tested.
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
 * Unfortunately, the real implementation of DataSource talks to an external
 * web service. Even if we reconfigure it, it will still involve using the 
 * network (no testing when offline, and slower testing when online with
 * respect to in-memory tests); it also probably gives different results 
 * depending on the current date, and we would have to update our assertions.
 */
class GoogleAnalyticsDataSource implements DataSource
{
    public function getSamples()
    {
        $result = curl("...");
        return $result;
    }
}

/**
 * An alternate implementation of DataSource, which is simply configured with 
 * predefined results. It returns them every time it is called, without
 * being affected by any global or external state.
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

