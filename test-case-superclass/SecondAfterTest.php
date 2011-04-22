<?php
require_once 'SUT.php';
require_once 'UsersTest.php';

/**
 * After the refactoring.
 */
class SecondAfterTest extends UsersTest
{
    public function testUsersDetailsCanBeModified()
    {
        $sut = new SUT($this->getDatabase());
        $this->createUser(array(/* ... */));
        $sut->updatePassword(1, 'oldPassword', 'newPassword');
        $this->assertEquals('newPassword', $sut->getUser(1)->getPassword());
    }
}
