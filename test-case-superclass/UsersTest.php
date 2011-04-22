<?php
/**
 * The extracted Testcase Superclass: abstract, protected 
 * Test Utility Methods and good Api documentation to foster its usage.
 */
abstract class UsersTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return PDO  with schema created
     */
    protected function getDatabase()
    {
        $connection = new PDO("sqlite::memory:");
        // CREATE TABLE users...
        return $connection;
    }

    protected function createUser($details)
    {
        // INSERT INTO users...
    }
}
