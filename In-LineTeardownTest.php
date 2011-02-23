<?php
/**
 * All the tests will be failing. This is to demonstrate the difficult part
 * of implementing In-Line Teardown: ensure it is executed while test are 
 * failing.
 */
class InLineTeardownTest extends PHPUnit_Framework_TestCase
{
    /**
     * I keep the object on a field so that if In-Line Teardown is not
     * executed, we'll see destruction messages at the end of the suite
     * instead of after each test.
     */
    private $fixtureToTeardown;

    public function testExpectMethodForInlineTeardown()
    {
        $this->fixtureToTeardown = new MyClassWithDestructor();
        // this doesn't *immediately* throw an exception
        $this->expectTrue(false, 'First test failure message.');
        unset($this->fixtureToTeardown);
    }

    private $expectationBooleans = array();
    private $expectationErrorMessages = array();

    private function expectTrue($boolean, $message = '')
    {
        $this->expectationBooleans[] = $boolean;
        $this->expectationErrorMessages[] = $message;
    }

    /**
     * A workaround to being able to support expect() methods
     */
    public function tearDown()
    {
        foreach ($this->expectationBooleans as $i => $boolean) {
            $this->assertTrue($boolean, $this->expectationErrorMessages[$i]);
        }
    }
    
    public function testMovementOfAssertionsAfterInLineTeardown()
    {
        $this->fixtureToTeardown = new MyClassWithDestructor();
        $result = 1 == 0; // or whatever computation over the results
                          // that ultimately produces a boolean
        unset($this->fixtureToTeardown);
        $this->assertTrue($result, 'Second test failure message.');
    }

    public function testFinallyLikeSolutions()
    {
        $this->fixtureToTeardown = new MyClassWithDestructor();
        try {
            $this->assertTrue(false, 'Third test failure message.');
        } catch (Exception $assertionException) {
            unset($this->fixtureToTeardown);
            throw $assertionException;
        }
    }
}

class MyClassWithDestructor
{
    public function __destruct()
    {
        echo "The instance of MyClassWithDestructor has been destroyed.\n";
    }
}
