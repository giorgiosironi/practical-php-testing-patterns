<?php
class TestHelperAfterTest extends PHPUnit_Framework_TestCase
{
    /**
     * We create the Test Helper in the setUp() in case other Test Methods
     * that need it are present.
     */
    public function setUp()
    {
        $this->standardsHelper = new StandardsHelper($this);
    }

    public function testArrayRespectsStandardStructure()
    {
        $array = array(
            'code' => 200,
            'content' => '...'
        );
        $this->standardsHelper->assertArrayRespectsStandardStructure($array);
    }
}

class StandardsHelper
{
    /**
     * Composing the Testcase Object is useful because of the access to assert*() functions,
     * but also to getMock(), any() and many other utility methods. They could be reimplemented,
     * but compositions is really much less work.
     * Incidentally, this also demonstrates why it's correct to keep methods private 
     * on the Testcase class: they are preserved from usage "da parte di" Test Helpers.
     */
    public function __construct(PHPUnit_Framework_TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    /**
     * The Custom Assertion becomes public. Other methods which are only called inside
     * this class can remain private.
     */
    public function assertArrayRespectsStandardStructure(array $array)
    {
        $this->testCase->assertTrue(isset($array['code']), 'Missing "code" key.');
        $this->testCase->assertTrue(is_numeric($array['code']), '"code" key invalid.');
        $this->testCase->assertTrue(isset($array['content']), 'Missing "content" key.');
    }
}
