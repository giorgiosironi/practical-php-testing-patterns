<?php
require_once 'SUT.php';
require_once 'UsersTest.php';

/**
 * After the refactoring.
 */
class FirstAfterTest extends UsersTest
{
    public function testUsersCanBeDeletedByTheirIds()
    {
        $sut = new SUT($this->getDatabase());
        $this->createUser(array(/* ... */));
        $this->createUser(array(/* ... */));

        $sut->deleteUser(array(1, 2));
        $this->assertEquals(0, count($sut->getUsers()));
    }
}
