<?php
require_once 'Facade.php';

class FacadeProductBoughtTest extends PHPUnit_Framework_TestCase
{
    private $transactionNumber;

    public function setUp()
    {
        $pdo = new PDO('sqlite::memory:');
        $this->facade = new Facade();
        // other setup code: CREATE TABLE statements and INSERT
        // also insert the transaction
        $this->transactionNumber = 42;
        $this->dummyCreditCard = '1234567812345678';
    }

    public function testUsersMayPayTheTransactionWithACreditCard()
    {
        $result = $this->facade->payFor($this->transactionNumber, $this->dummyCreditCard);
        $this->assertTrue($result);
    }

    public function testUsersCannotPayWithANotValidCreditCardNumber()
    {
        $result = $this->facade->payFor($this->transactionNumber, $tooLong = '12345678123456781234567812345678');
        $this->assertFalse($result);
    }
}

