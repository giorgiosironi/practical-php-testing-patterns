<?php
class ImplicitTeardownTest extends PHPUnit_Framework_TestCase
{
    /**
     * I keep the object on a field so that if In-Line Teardown is not
     * executed, we'll see destruction messages at the end of the suite
     * instead of after each test.
     */
    private $fixtureToTeardown;
    private static $classLevelFixtureToTeardown;

    public function testExpectMethodForInlineTeardown()
    {
        $this->fixtureToTeardown = new MyClassWithDestructor(1);
        self::$classLevelFixtureToTeardown = new MyClassWithDestructor(2);
        $this->assertTrue(false, 'First test failure message.');
    }

    public function testSomethingElseWhichCouldResultInAFatalError()
    {
        // suppose your SUT code returns this or a scalar for a
        // regression or bug
        $object = null;

        $this->assertInstanceOf('SplQueue', $object);

        $this->assertEquals('dummy', $object->dequeue());
    }

    /**
     * A workaround to being able to support expect() methods
     */
    public function tearDown()
    {
        unset($this->fixtureToTeardown);
    }
    
    public static function tearDownAfterClass()
    {
        // you can't unset() a static property. Don't ask me why
        self::$classLevelFixtureToTeardown = null;
    }
}

class MyClassWithDestructor
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function __destruct()
    {
        echo "The instance {$this->id} of MyClassWithDestructor has been destroyed.\n";
    }
}
