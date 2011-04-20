<?php
require_once 'Facade.php';

class FacadeMonolithicTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $pdo = new PDO('sqlite::memory:');
        $this->facade = new Facade($pdo);
        // other setup code: CREATE TABLE statements

        $this->transactionNumber = 42;
        $this->dummyCreditCard = '1234567812345678';
    }

    public function testGivenAnEmptyListOfProductsUsersAreDisplayedWithAnErrorMessage()
    {
        $result = $this->facade->search('product name');
        $this->assertContains('Sorry for the inconvenience.', $result);
    }

    /**
     * @expectedException ProductNotExistentException
     */
    public function testGivenAnEmptyListOfTransactionsUsersCannotPayForNotLoadedItems()
    {
        $result = $this->facade->payFor($userId = 1, $transactionId = null);
    }

    public function testGivenAListOfProductsUsersAreDisplayedWithTheListOfRelatedProducts()
    {
        // INSERT on products
        $result = $this->facade->search('product name');
        $this->assertTrue(count($result) > 10);
    }

    public function testUsersMayPayAnActiveTransactionWithACreditCard()
    {
        // INSERT on products and transactions
        $result = $this->facade->payFor($this->transactionNumber, $this->dummyCreditCard);
        $this->assertTrue($result);
    }

    public function testUsersCannotPayAnActiveTRansactionWithANotValidCreditCardNumber()
    {
        // INSERT on products and transactions
        $result = $this->facade->payFor($this->transactionNumber, $tooLong = '12345678123456781234567812345678');
        $this->assertFalse($result);
    }
}

