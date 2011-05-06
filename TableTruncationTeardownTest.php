<?php
class TableTruncationTeardownTest extends PHPUnit_Framework_TestCase
{
    private static $sharedConnection;
    private $connection;

    public function setUp()
    {
        if (self::$sharedConnection === null) {
            self::$sharedConnection = new PDO('sqlite::memory:');
            self::$sharedConnection->exec('CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255)
            )');
        }
        $this->connection = self::$sharedConnection;
    }

    public function teardown()
    {
        $this->connection->exec('DELETE FROM users');
        $this->connection->exec('DELETE FROM sqlite_sequence');
    }

    public function testTableCanBePopulated()
    {
        $this->connection->exec('INSERT INTO users (name) VALUES ("Giorgio")');
        $this->assertEquals(1, $this->howManyUsers());
    }

    public function testTableRestartsFrom1()
    {
        $this->assertEquals(0, $this->howManyUsers());
        $this->connection->exec('INSERT INTO users (name) VALUES ("Isaac")');
        $stmt = $this->connection->query('SELECT name FROM users WHERE id=1');
        $result = $stmt->fetch();
        $this->assertEquals('Isaac', $result['name']);
    }

    public function testTableIsEmpty()
    {
        $this->assertEquals(0, $this->howManyUsers());
    }

    private function howManyUsers()
    {
        $stmt = $this->connection->query('SELECT COUNT(*) AS number FROM users');
        $result = $stmt->fetch();
        return $result['number'];
    }
}
