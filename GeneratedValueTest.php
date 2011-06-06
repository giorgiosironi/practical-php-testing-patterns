<?php
class GeneratedValueTest extends PHPUnit_Framework_TestCase
{
    /**
     * Since these values are deterministic and sequential,
     * we need to keep track of the state of the current test.
     */
    private $nextCode = 1;

    public function testDistinctGeneratedValue()
    {
        $customerCode = $this->generateNewCustomerCode();
        $otherCustomerCode = $this->generateNewCustomerCode();
        $this->assertNotEquals($customerCode, $otherCustomerCode);
    }

    /**
     * Returns the current next value, then increments it.
     * @return int
     */
    private function generateNewCustomerCode()
    {
        return $this->nextCode++;
    }

    public function testRandomGeneratedValue()
    {
        $customerCode = $this->generateNewRandomCustomerCode();
        $otherCustomerCode = $this->generateNewRandomCustomerCode();
        $this->assertNotEquals($customerCode, $otherCustomerCode);
    }

    /**
     * Randomly generated values may be very large, so it's better to limit
     * the range.
     * @return int
     */
    private function generateNewRandomCustomerCode()
    {
        return rand(1, 10000);
    }

    public function testRelatedGeneratedValue()
    {
        $customerCode = $this->generateNewRelatedCustomerCode();
        $otherCustomerCode = $this->generateNewRelatedCustomerCode();
        $this->assertNotEquals($customerCode, $otherCustomerCode);
    }

    /**
     * @return string   something like customer_4de7541c5be0c
     * The second version (with rand()) will produce a wide variety of values,
     * while uniqid() results are guaranteed to be different but not by much
     * (2-3 characters).
     */
    private function generateNewRelatedCustomerCode()
    {
        return uniqid("customer_", true);
        // return "customer_" . rand(1, 10000);
    }
}
