<?php
class TestSpecificSubclassTest extends PHPUnit_Framework_TestCase
{
    /**
     * MoneyFund is our SUT, and this is the test for its public Api.
     */
    public function testMoneyFundMainFeature()
    {
        $moneyFund = new MoneyFund(100000, 4);
        $moneyFund->afterYears(1);
        $this->assertEquals(104000, $moneyFund->getCapital());
    }

    /**
     * We can test internal parts of MoneyFund via a Test Specific Subclass.
     */
    public function testPrivateMethodOfMoneyFund()
    {
        $moneyFund = new TestSpecificMoneyFund(100000, 4);
        $this->assertEquals(4000, $moneyFund->calculateInterests());
    }

    /**
     * By the way, an alternate design consider the internal part another
     * object. Here the SUT is isolated from the new collaborator.
     */
    public function testCompositionMoneyFundMainFeature()
    {
        $interestCalculator = $this->getMock('InterestCalculator');
        $interestCalculator->expects($this->once())
                           ->method('calculateInterests')
                           ->with(100000)
                           ->will($this->returnValue(4000));
        $moneyFund = new CompositionMoneyFund(100000, $interestCalculator);
        $moneyFund->afterYears(1);
        $this->assertEquals(104000, $moneyFund->getCapital());
    }

    /**
     * As a result, we can test what was previously just internal functionality
     * without Test Specific Subclasses, but just with other public methods.
     */
    public function testPublicMethodOfInterestCalculator()
    {
        $calculator = new InterestCalculator(4);
        $this->assertEquals(4000, $calculator->calculateInterests(100000));
    }
}

/**
 * The original SUT.
 */
class MoneyFund
{
    public function __construct($capital, $interestRate)
    {
        $this->capital = $capital;
        $this->interestRate = $interestRate;
    }

    public function afterYears($years)
    {
        for ($i = 1; $i <= $years; $i++) {
            $this->capital += $this->calculateInterests();
        }
    }

    /**
     * Internal method we want to test.
     */
    protected function calculateInterests()
    {
        return round($this->capital * $this->interestRate / 100, 2);
    }

    public function getCapital()
    {
        return $this->capital;
    }
}

/**
 * Test Specific Subclass.
 */
class TestSpecificMoneyFund extends MoneyFund
{
    public function calculateInterests()
    {
        return parent::calculateInterests();
    }
}

/**
 * The refactored SUT.
 */
class CompositionMoneyFund
{
    public function __construct($capital, InterestCalculator $calculator)
    {
        $this->capital = $capital;
        $this->interestCalculator = $calculator;
    }

    public function afterYears($years)
    {
        for ($i = 1; $i <= $years; $i++) {
            $this->capital += $this->interestCalculator->calculateInterests($this->capital);
        }
    }

    public function getCapital()
    {
        return $this->capital;
    }
}

/**
 * The collaborator modelling the internal functionality.
 */
class InterestCalculator
{
    private $rate;

    public function __construct($rate = 0)
    {
        $this->rate = $rate;
    }

    public function calculateInterests($capital)
    {
        return round($this->rate / 100 * $capital, 2);
    }
}
