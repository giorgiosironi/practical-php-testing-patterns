<?php

/**
 * The TestCase shows how first only a brittle test and a loose assertion
 * can be made over the result. With proper isolation, the assertion becomes 
 * an equality: at the same time TestableTimeBox isn't going to break client
 * code where UntestableTimeBox was used, because we only changed an internal
 * detail and not the public Api (like its constructor).
 */
class TestHookTest extends PHPUnit_Framework_TestCase
{
    public function testTheUntestableTimeBoxWithASmokeTest()
    {
        $box = new UntestableTimeBox();
        $this->assertRegexp('/<div(.*)<\/div>/', $box->__toString());
    }

    public function testTheTestableTimeBoxWithAUnitTest()
    {
        $box = new TestSubclassOfTestableTimeBox(0000001);
        $this->assertEquals('<div class="current_timestamp">1970-01-01</div>', $box->__toString());
    }

    public function testTheTestableTimeBoxWithAUnitTestAndAPartialGeneratedMock()
    {
        $box = $this->getMock('TestableTimeBox', array('currentTime'));
        $box->expects($this->any())
            ->method('currentTime')
            ->will($this->returnValue(0000001));
        $this->assertEquals('<div class="current_timestamp">1970-01-01</div>', $box->__toString());
    }
}

/**
 * The original SUT: there is no way to fully test it due to the global state
 * introduced by time().
 */
class UntestableTimeBox
{
    public function __toString()
    {
        return '<div class="current_timestamp">' . date('Y-m-d', time()) . '</div>';
    }
}

/**
 * The SUT with a really small refactoring, which does not break its Api.
 * Of course in reality it would have the same name of the original SUT...
 */
class TestableTimeBox
{
    public function __toString()
    {
        return '<div class="current_timestamp">' . date('Y-m-d', $this->currentTime()) . '</div>';
    }

    protected function currentTime()
    {
        return time();
    }
}

/**
 * A Test-Specific Subclass that overrides the Test Hook. This is *not* part 
 * of production code.
 * You add all these lines of code specific just in order to run a simple test:
 * the payback you get is the shorter test length. A next step could be to 
 * inject a simple collaborator wrapping time().
 */
class TestSubclassOfTestableTimeBox extends TestableTimeBox
{
    public function __construct($currentTime)
    {
        $this->currentTime = $currentTime;
    }

    protected function currentTime()
    {
        return $this->currentTime;
    }
}
