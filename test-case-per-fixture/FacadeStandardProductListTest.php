<?php
require_once 'Facade.php';

class FacadeStandardProductListTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $pdo = new PDO('sqlite::memory:');
        $this->facade = new Facade();
        // other setup code: CREATE TABLE statements and INSERT
    }

    public function testUsersAreDisplayedWithTheListOfRelatedProducts()
    {
        $result = $this->facade->search('product name');
        $this->assertTrue(count($result) > 10);
    }
}

