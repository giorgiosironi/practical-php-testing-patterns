<?php
class DependencyInjectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * The System Under Test is exercised in isolation via a .
     */
    public function testInjectsATestDoubleViaConstructor()
    {
        $collaboratorStub = $this->getMock('Collaborator');
        $sut = new GoodSut($collaboratorStub);
        $collaboratorStub->expects($this->any())->method('baseValue')->will($this->returnValue(5));
        $this->assertEquals(50, $sut->calculateValue());
    }

    /**
     * The System Under Test cannot be exercised in isolation, and since
     * the other class is not yet finished we're blocked. We cannot even
     * substitute Collaborator just because of speed if it's a really heavy
     * and complex to set up implementation using a database or the filesystem.
     */
    public function testCannotSubstituteTheCollaborator()
    {
        $sut = new BadSut();
        // now what?
        $this->markTestIncomplete('Kernel panic!');
    }
}

class GoodSut
{
    private $collaborator;

    public function __construct(Collaborator $collaborator)
    {
        $this->collaborator = $collaborator;
    }

    public function calculateValue()
    {
        return $this->collaborator->baseValue() * 10;
    }
}

class BadSut
{
    private $collaborator;

    public function __construct()
    {
        $this->collaborator = new Collaborator();
    }

    public function calculateValue()
    {
        return $this->collaborator->baseValue() * 10;
    }
}

/**
 * The Collaborator class is not even finished yet.
 */
class Collaborator
{
    public function baseValue() {}
}
