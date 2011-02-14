<?php
class UnfinishedTestAssertions extends PHPUnit_Framework_TestCase
{
    public function testThatFails()
    {
        $this->fail('Write this test, you should check that X does Y.');
    }

    public function testThatIsSkipped()
    {
        $this->markTestSkipped('To execute this test, you need an active Internet connection.');
    }

    public function testThatIsIncomplete()
    {
        $fixture = new ArrayObject();
        $fixture['key'] = 'value';

        $this->markTestIncomplete('This test needs to be finished: the act and assert parts are missing.');
    }

    public function testThatIsDeclaredIncompleteWithCustomUnifinishedTestAssertion()
    {
        $this->assertionWillBeNeeded();
    }

    private function assertionWillBeNeeded()
    {
        $this->markTestIncomplete('The test lacks an assert phase. Write it.');
    }
}
