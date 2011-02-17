<?php
class AutomatedTeardownTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var array   Paths to all files created in the tests.
     */
    private $files;

    public function testTheSutSumsANumberToTheNumbersInTheFile()
    {
        $file = $this->createTextFile("1\n2\n3\n");
        $sut = new Raiser(10);
        $sut->raise($file);

        // make your assertions...
    }

    /**
     * Wrapped creation of resources, in this case files
     */
    private function createTextFile($content)
    {
        $file = uniqid('temp') . '.txt';
        file_put_contents($file, $content);
        $this->files[] = $file;
        return $file;
    }

    /**
     * Hook for executing the teardown at the end of each test.
     * You can also execute it manually: the automation resides in not having
     * to specify where are the resources to clean up.
     */
    public function teardown()
    {
        $this->cleanUpAllFiles();
    }

    /**
     * Automated Teardown implementation
     */
    private function cleanUpAllFiles()
    {
        foreach ($this->files as $filePath) {
            unlink($filePath);
        }
    }
}

class Raiser
{
    private $delta;

    public function __construct($delta)
    {
        $this->delta = $delta;
    }

    public function raise($file)
    {
        $content = file_get_contents($file);
        $lines = explode("\n", $content);
        foreach ($lines as $line) {
            if (is_numeric($line)) {
                $line += $this->delta;
            }
        }
        $content = implode("\n", $lines);
        file_put_contents($file, $content);
    }
}
