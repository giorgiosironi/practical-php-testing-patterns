<?php
/**
 * This is a kind of integration test: we mock out the database from every 
 * other test, but still we have to hit it in one place to ensure our Gateway
 * (in this case a class containing queries) work.
 */
class StoredProcedureTest extends PHPUnit_Framework_TestCase
{
    private $connection;
    private $repository;

    /**
     * I'm using my local instance of MySQL: the only requirement is the 
     * presence of a database named 'sandbox'. You may want to parametrize 
     * everything here from database name to user and password.
     */
    public function setUp()
    {
        $this->connection = new PDO("mysql:host=localhost;dbname=sandbox", 'root', '');
        $this->connection->exec("CREATE TABLE users (name VARCHAR(255) NOT NULL PRIMARY KEY, year YEAR)");
        $this->repository = new UserRepository($this->connection, 2011);
    }

    public function testAverageAgeIsCalculated()
    {
        $this->insertUser('Giorgio', 1942);
        $this->insertUser('Isaac', 1920);
        $this->assertEquals(80, $this->repository->getAverageAge());
    }

    private function insertUser($name, $year)
    {
        $stmt = $this->connection->prepare("INSERT INTO users (name, year) VALUES (:name, :year)");
        $stmt->bindValue('name', $name, PDO::PARAM_STR);
        $stmt->bindValue('year', $year, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function tearDown()
    {
        $this->connection->exec('DROP TABLE users');
    }
}

class UserRepository
{
    private $connection;
    private $currentYear;

    public function __construct(PDO $connection, $currentYear)
    {
        $this->connection = $connection;
        $this->currentYear = $currentYear;
    }

    /**
     * We suppose AVG() cannot be correctly implemented by Sqlite or
     * another surrogate database (substitute another vendor feature
     * for the same effect). 
     * We also suppose reconstituting millions of User objects to calculate
     * their average age isn't feasible: that's why we used SQL directly.
     */
    public function getAverageAge()
    {
        $stmt = $this->connection->prepare('SELECT AVG(:year - year) AS average_age FROM users');
        $stmt->bindValue('year', $this->currentYear, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['average_age'];
    }
}


