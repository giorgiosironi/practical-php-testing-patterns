<?php
/**
 * The System Under Test should use this database-related class to move Posts around.
 * However we inject a FakePostDao in order to avoid using a real database in this test,
 * and at the same time better define the interface between the two.
 */
class FakeObjectTest extends PHPUnit_Framework_TestCase
{
    public function testMergesTwoThreads()
    {
        $dao = new FakePostDao(array(
            1 => array(
                new Post('Hello'),
                new Post('Hello!'),
                new Post('')
            ),
            2 => array(
                new Post('Hi'),
                new Post('Hi!')
            ),
            3 => array(
                new Post('Good morning.')
            )
        ));
        
        $forumManager = new ForumManager($dao);
        $forumManager->mergeThreadsByIds(1, 2);
        $thread = $dao->getThread(1);
        $this->assertEquals(5, count($thread));
    }
}

/**
 * The SUT.
 */
class ForumManager
{
    private $dao;

    public function __construct(PostsDao $dao)
    {
        $this->dao = $dao;
    }

    public function mergeThreadsByIds($originalId, $toBeMergedId)
    {
        $original = $this->dao->getThread($originalId);
        $toBeMerged = $this->dao->getThread($toBeMergedId);
        $newOne = array_merge($original, $toBeMerged);
        $this->dao->removeThread($originalId);
        $this->dao->removeThread($toBeMergedId);
        $this->dao->addThread($originalId, $newOne);
    }
}

/**
 * Interface for the collaborator to substitute with the Test Double.
 */
interface PostsDao
{
    public function getThread($id);
    public function removeThread($id);
    public function addThread($id, array $thread);
}

/**
 * Fake implementation.
 */
class FakePostDao implements PostsDao
{
    private $threads;

    public function __construct(array $initialState)
    {
        $this->threads = $initialState;
    }

    public function getThread($id)
    {
        return $this->threads[$id];
    }

    public function removeThread($id)
    {
        unset($this->threads[$id]);
    }

    /**
     * We model Thread as array of Posts for simplicity.
     */
    public function addThread($id, array $thread)
    {
        $this->threads[$id] = $thread;
    }
}

/**
 * Again a Dummy object: minimal implementation, to make this test pass.
 */
class Post
{
}
