<?php
class DependencyLookupTest extends PHPUnit_Framework_TestCase
{
    /**
     * We use the real Locator, since we can change its configuration.
     * It's not totally a unit test since it involves the Locator, but
     * it's often very simple code that would be perfectly replicated by the 
     * first Stub in the chain.
     */
    public function testViewProducesAParagraphAndAnUrl()
    {
        $viewHelperBroker = new ViewHelperBroker(array(
            'p' => 'NullHelper',
            'url' => 'NullHelper'
        ));
        $view = new ViewScript($viewHelperBroker);
        $expected = "Hello World!|"
                  . "\n"
                  . "user|1";
        $this->assertEquals($expected, $view->render());
    }
}

/**
 * A collaborator (a Stub one).
 */
class NullHelper
{
    public function __invoke($arg1 = null, $arg2 = null)
    {
        return $arg1 . '|' . $arg2;
    }
}

/**
 * The SUT.
 */
class ViewScript
{
    private $helperBroker;

    public function __construct(ViewHelperBroker $broker)
    {
        $this->helperBroker = $broker;
    }

    public function render()
    {
        return $this->helperBroker->p()->__invoke('Hello World!')
             . "\n"
             . $this->helperBroker->url()->__invoke('user', '1');
    }
}

/**
 * The Service Locator.
 */
class ViewHelperBroker
{
    private $configuration;

    public function __construct(array $helpersConfiguration)
    {
        $this->configuration = $helpersConfiguration; 
    }

    public function __call($helperName, $arguments)
    {
        $class = $this->configuration[$helperName];
        return new $class;
    }
}
