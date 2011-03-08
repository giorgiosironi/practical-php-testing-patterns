<?php
class MockObjectTest extends PHPUnit_Framework_TestCase
{
    /**
     * In this test the UsersView Mock Object checks
     * that it is called the right number of times
     */
    public function testPrintsOnlyActiveUsers()
    {
        $users = array(
            new User('george', true),
            new User('john', false),
            new User('mark', false),
            new User('joan', true),
            new User('steve', false)
        );

        $view = $this->getMock('UsersView');
        $view->expects($this->exactly(2))
             ->method('add');

        $sut = new UsersController($users);
        $sut->renderOn($view);
    }

    public function testUsersSelectUsersWhoseNameStartsWithAGivenPrefix()
    {
        $users = array(
            new User('george', true),
            $john = new User('john', true),
            new User('mark', true),
            new User('steve', true)
        );

        $view = $this->getMock('UsersView');
        $view->expects($this->once())
             ->method('add')
             ->with($john);

        $sut = new UsersController($users, 'j');
        $sut->renderOn($view);
    }

}

/**
 * The interface the Mock Objects implement. The simpler this interface,
 * the cleaner your code.
 */
interface UsersView
{
    public function add(User $user);
}

/**
 * The System Under Test. It should render on a View, which is substituted by
 * a Test Double.
 */
class UsersController
{
    private $users;
    private $prefixFilter;

    public function __construct(array $users, $prefixFilter = '')
    {
        $this->users = $users;
        $this->prefixFilter = $prefixFilter;
    }

    public function renderOn(UsersView $view)
    {
        foreach ($this->users as $user)
        {
            if ($user->isActive() && $user->startsWith($this->prefixFilter)) {
                $view->add($user);
            }
        }
    }
}

/**
 * In these tests the instances of User will actually be Dummy, which
 * means just objects which are passed around without any method call 
 * is performed on them.
 * This implementation is thus really brief.
 */
class User
{
    private $name;
    private $active;

    public function __construct($name, $active)
    {
        $this->name = $name;
        $this->active = $active;
    }

    public function isActive()
    {
        return $this->active;
    }

    public function startsWith($prefix)
    {
        if ($prefix == '') {
            return true;
        }
        if (strstr($this->name, $prefix) == $this->name) {
            return true;
        }
        return false;
    }
}
