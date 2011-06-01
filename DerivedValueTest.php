<?php
class DerivedValueTest extends PHPUnit_Framework_TestCase
{
    /**
     * In this example, $center is computed to avoid a test bug because someone
     * updated $array and forgot the $center variable.
     * In short, to remove duplication.
     */
    public function testADerivedInputRemovesDuplicationAndImprovesClarity()
    {
        $array = array('a', 'b', 'center', 'd', 'e');
        $center = floor(count($array) / 2);
        array_flip($array);
        $this->assertEquals('center', $array[$center]);
    }

    /**
     * A data structure HTTP request is created and then invalidated.
     * Compare this with redefining the array every time and make sure
     * the other keys are still valid.
     * I use an array in place of an object for brevity in this explanation.
     */
    public function testOneBadAttributeValuesAreBuiltFromValidOnes()
    {
        $request = $this->createGetRequest(); // hides module and controller details
        $request['action'] = 'this-will-cause-a-404';
        $this->markTestIncomplete('When calling the SUT, the action should be judged as invalid.');
    }

    private function createGetRequest()
    {
        return array(
            'module' => 'default',
            'controller' => 'index',
            'action' => 'index'
        );
    }

    /**
     * If we have a "casting-out-nines" test to quickly check our results, 
     * at least as in a smoke test, we can use a derived expectation.
     * Since our test cases are usually many simplified scenarios where
     * to exercise the production code, deriving the right result shouldn't 
     * require as much code as in there. If you have to mirror the production 
     * code, stop: the test would become too tied with the implementation, to 
     * the point of reproducing its bugs.
     * These tests come handy when the amount of data is huge (e.g. multimedia 
     * files) and generating a sanity check is by far faster than hardcoding
     * everything by hand (the image contains a car at pixel (45; 100) ...).
     */
    public function testADerivedExpectationLetsYouAssertWithoutHardcoding()
    {
        $array = array();
        $elements = 4; // our unique parameter
        for ($i = 1; $i <= $elements; $i++) {
            $array[] = $i;
        }
        $expectedTotal = $elements * ($elements + 1) / 2; // Gauss formula
        $total = array_sum($array);
        $this->assertEquals($expectedTotal, $total);
    }
}
