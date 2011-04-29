<?php
class DatabaseSandboxTest extends PHPUnit_Framework_TestCase
{
    private $db;

    public function setUp()
    {
        // in production the string is mysql:... or pgsql:...
        $this->db = new PDO('sqlite::memory:');
        // if you want portable CREATE statements, Doctrine 1 (deprecated) or 2
        $this->db->exec('CREATE TABLE users (id INT NOT NULL PRIMARY KEY, name VARCHAR(255))');
        $this->db->exec('INSERT INTO users (id, name) VALUES (1, "Giorgio")');
        // $this->db will be recreated for each test method: if you have many tables, this is SLOW
        // we'll see how to adjust the teardown so that we can reuse 
        // the same schema, containing hundreds of tables, between different tests
    }

    public function testMyDatabaseIsNewShinyAndPopulatedWithData()
    {
        $this->assertTrue($this->db instanceof PDO);
        $users = $this->db->query('SELECT * FROM users')->fetchAll();
        $this->assertEquals('Giorgio', $users[0]['name']);
    }
}
