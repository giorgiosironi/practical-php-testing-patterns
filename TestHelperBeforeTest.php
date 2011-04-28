<?php
class TestHelperBeforeTest extends PHPUnit_Framework_TestCase
{
    /**
     * Our only Test Method, but there could be others.
     */
    public function testArrayRespectsStandardStructure()
    {
        $array = array(
            'code' => 200,
            'content' => '...'
        );
        $this->assertArrayRespectsStandardStructure($array);
    }

    /**
     * This is the method we want to extract: a Custom Assertion.
     * We suppose this code is duplicated in other classes, which are won't 
     * included here for brevity.
     */
    private function assertArrayRespectsStandardStructure(array $array)
    {
        $this->assertTrue(isset($array['code']), 'Missing "code" key.');
        $this->assertTrue(is_numeric($array['code']), '"code" key invalid.');
        $this->assertTrue(isset($array['content']), 'Missing "content" key.');
    }
}
