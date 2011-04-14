<?php
require_once 'Facade.php';

class FacadeSearchTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->facade = new Facade();
        // other setup code: product database for example
    }

    public function testUserCanSearchForProducts()
    {
        $products = $this->facade->search('hard disks');
        $this->assertTrue(count($products) > 0);
        // ... more assertions
    }

    public function testUserCanSearchForProductsAndExcludeUnwantedOnes()
    {
        $products = $this->facade->search('hard disks -ssd');
        $this->assertTrue(count($products) == 1);
        // ... more assertions
    }
}
