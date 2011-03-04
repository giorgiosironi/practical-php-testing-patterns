<?php
class TestSpyTest extends PHPUnit_Framework_TestCase implements Mailer, Db
{
    /**
     * Other expectations would be simple to check with generated Mocks, 
     * but order of calls on different object is not in PHPUnit.
     * The same Self-Shunting setup can be used in other tests too.
     */
    public function testSendsAMailAfterUserCreationViaSelfShunting()
    {
        // remember, Db and Mailer would be two different objects in production
        $sut = new UserDao($this, $this);
        $sut->createUser(array('mail' => 'someone@example.com', 'nickname' => 'johndoe'));
        $this->assertEquals(array('executeQuery', 'mail'), $this->order);
    }

    private $order = array();

    private $queries = array();

    public function executeQuery($query, array $params)
    {
        $this->order[] = 'executeQuery';
        $this->queries[] = $query;
    }

    private $mails = array();

    public function mail($to, $subject, $object)
    {
        $this->order[] = 'mail';
        $this->mails[] = array('to' => $to,
                               'subject' => $subject,
                               'object' => $object);
    }

    public function testInnerTestDoubleArrayObject()
    {
        $parts = new ArrayObject();
        $receiver = $this->getMock('Receiver');
        $receiver->expects($this->any())
                 ->method('definePart')
                 ->will($this->returnCallback(function($amount) use ($parts) {
                     $parts[] = $amount;
                 }));
        $sut = new RandomDivider($receiver);
        $sut->divide(10);
        $this->assertEquals(10, array_sum($parts->getArrayCopy()));
    }

    public function testInnerTestDoubleArrayPassedByReference()
    {
        $parts = array(); // an array would not be passed by handler by default
        $receiver = $this->getMock('Receiver');
        $receiver->expects($this->any())
                 ->method('definePart')
                 ->will($this->returnCallback(function($amount) use (&$parts) { // but we can pass it by reference
                     $parts[] = $amount;
                 }));
        $sut = new RandomDivider($receiver);
        $sut->divide(10);
        $this->assertEquals(10, array_sum($parts));
    }
}

interface Mailer
{
    public function mail($to, $subject, $object);
}

interface Db
{
    public function executeQuery($query, array $params);
}

class UserDao
{
    private $db;
    private $mailer;

    public function __construct(DB $db, Mailer $mailer)
    {
        $this->db = $db;
        $this->mailer = $mailer;
    }

    public function createUser(array $userDetails)
    {
        // internally it would use PDO
        $this->db->executeQuery("INSERT INTO users ...", $userDetails);
        $this->mailer->mail($userDetails['mail'], 'You have been registered on example.com', '...');
    }
}

interface Receiver
{
    public function definePart($amount);
}

class RandomDivider
{
    private $receiver;

    public function __construct(Receiver $receiver)
    {
        $this->receiver = $receiver;
    }

    public function divide($total)
    {
        $part = ceil(rand() * $total);
        $this->receiver->definePart($part);
        $this->receiver->definePart($total - $part);
    }
}
