<?php
require_once 'Facade.php';

class FacadeProductListEmptyTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $pdo = new PDO('sqlite::memory:');
        $this->facade = new Facade();
        // other setup code: CREATE TABLE statements
    }

    public function testUsersAreDisplayedWithAnErrorMessage()
    {
        $result = $this->facade->search('product name');
        $this->assertContains('Sorry for the inconvenience.', $result);
    }

    /**
     * @expectedException ProductNotExistentException
     */
    public function testUsersCannotPayForNotLoadedItems()
    {
        $result = $this->facade->payFor($userId = 1, $transactionId = null);
    }
}
