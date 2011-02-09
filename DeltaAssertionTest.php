<?php
class DeltaAssertionTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        // imagine that this fixture is a Shared one
        // we wouldn't know the particular state of the table
        $this->connection = new PDO('sqlite::memory:');
        $this->connection->exec('CREATE TABLE users (nickname VARCHAR(255) NOT NULL PRIMARY KEY, password VARCHAR(255))');
    }

    public function testAnUserIsAdded()
    {
        $userDao = new UserDao($this->connection);
        $previous = $userDao->countUsers();

        // if the nickname is not unique, however, the test would still fail
        // but we are covered against all other records inserted in the table
        $userDao->addUser('aUniqueNickname', 'password');

        $this->assertEquals($previous + 1, $userDao->countUsers());
    }
}

class UserDao
{
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function countUsers()
    {
        $stmt = $this->connection->query('SELECT COUNT(*) FROM users');
        $result = $stmt->fetchAll();
        return $result[0][0];
    }

    public function addUser($nickname, $password)
    {
        $stmt = $this->connection->prepare('INSERT INTO users (nickname, password) VALUES (:nickname, :password)');
        $stmt->bindValue('nickname', $nickname, PDO::PARAM_STR);
        $stmt->bindValue('password', $password, PDO::PARAM_STR);
        $stmt->execute();
    }
}
