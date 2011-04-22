<?php
require_once 'SUT.php';

/**
 * Before the refactoring, we have some database plumbing code 
 * duplicated between here and FirstTest.
 * Sometimes we can merge the Testcase Classes, but if they are on 
 * different SUTs it becomes difficult and it would not scale.
 */
class SecondTest extends PHPUnit_Framework_TestCase
{
    public function testUsersDetailsCanBeModified()
    {
        $connection = new PDO("sqlite::memory:");
        $sut = new SUT($connection);
        // CREATE TABLE users...
        // INSERT users...
        $sut->updatePassword(1, 'oldPassword', 'newPassword');
        $this->assertEquals('newPassword', $sut->getUser(1)->getPassword());
    }
}
