<?php
require_once 'Facade.php';

class FacadeBuyingTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        /**
         * For simplicity I instance the object with new, but in reality
         * it would be an object graph created by a Factory or a DI
         * container.
         */
        $this->facade = new Facade();
        // other setup code: database of the users for example
    }

    public function testUserCanBuyAnItem()
    {
        $userId = 42;
        $productId = 1000000;
        $this->facade->buy($userId, $productId);
    }

    public function testUserCanPayAnItem()
    {
        $userId = 42;
        $transactionId = 200000000;
        $this->facade->payFor($userId, $transactionId);
    }
}
