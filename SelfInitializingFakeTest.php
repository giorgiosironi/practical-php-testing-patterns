<?php
class SelfInitializingFakeTest extends PHPUnit_Framework_TestCase
{
    public function testReturnsAlwaysTheSameResultForEachQuery()
    {
        $fake = new GoogleMapsDirectionsSelfInitializingFake();
        $httpStart = $this->currentTime();
        $fake->getDirections('Milan', 'Rome');
        $httpEnd = $this->currentTime();
        $fake->getDirections('Milan', 'Rome');
        $cachedEnd = $this->currentTime();
        $httpTime = $httpEnd - $httpStart;
        $cachedTime = $cachedEnd - $httpEnd;
        $this->assertGreaterThan(10, $httpTime / $cachedTime);
    }

    private function currentTime()
    {
        return microtime(true);
    }
}

class GoogleMapsDirectionsSelfInitializingFake
{
    public function getDirections($from, $to)
    {
        $url = "http://maps.googleapis.com/maps/api/directions/xml?origin={$from}&destination={$to}&sensor=false";
        $response = file_get_contents($url);
        return new SimpleXMLElement($response);
    }
}
