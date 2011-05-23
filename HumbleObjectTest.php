<?php
/**
 * How do we test this controller? We have to instantiate it, using a
 * constructor designed for the framework, and inject a lot of things like a
 * bootstrap object, which is a bit complex to build. Or we can mock it,
 * but it has lots of methods.
 * In fact, we can only do so with other goodies from the
 * framework, in our case Zend_Test. But the test will be slow since it will
 * be and end-to-end one.
 */
class PostController // extends Zend_Controller_Action
{
    public function indexAction()
    {
        $connection = $this->bootstrap->getResource('connection');
        $stmt = $connection->query('SELECT * FROM posts')->execute();
        $posts = array();
        foreach ($stmt->fetchAll() as $row) {
            $posts[] = $row;
        }
        // pass $posts to the view...
    }
}

/**
 * We separate the logic in an Humble Object (the controller) and the real
 * object which performs the work.
 * We can now test PostsDao in isolation, while the Humble Object short code
 * will be tested by very few end-to-end tests.
 * I don't show PostsDao implementation here for brevity reasons and because
 * it's really simple to grasp what goes inside: the PDO usage.
 */
class PostController // extends Zend_Controller_Action
{
    public function indexAction()
    {
        $connection = $this->bootstrap->getResource('connection');
        $postsDao = new PostsDao($connection);
        $posts = $postsDao->findAll();
        // pass $posts to the view...
    }
}
