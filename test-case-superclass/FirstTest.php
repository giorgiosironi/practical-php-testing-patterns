<?php
require_once 'SUT.php';

/**
 * Before the refactoring.
 */
class FirstTest extends PHPUnit_Framework_TestCase
{
    public function testUsersCanBeDeletedByTheirIds()
    {
        $connection = new PDO("sqlite::memory:");
        $sut = new SUT($connection);
        // CREATE TABLE users...
        // INSERT users...
        $sut->deleteUser(array(1, 2));
        $this->assertEquals(0, count($sut->getUsers()));
    }
}
