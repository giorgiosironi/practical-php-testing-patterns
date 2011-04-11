<?php
/**
 * This class contains many duplicated tests.
 * Yet if we kept them in a single testSquareRootIsCalculated() method,
 * the first failure would prevent the other from running.
 */
class NotParameterizedTest extends PHPUnit_Framework_TestCase
{
    public function testSquareRootIsCalculatedCorrectlyByPHPFor0()
    {
        $this->assertEquals(0, sqrt(0));
    }

    public function testSquareRootIsCalculatedCorrectlyByPHPFor1()
    {
        $this->assertEquals(1, sqrt(1));
    }

    public function testSquareRootIsCalculatedCorrectlyByPHPFor4()
    {
        $this->assertEquals(2, sqrt(4));
    }

    public function testSquareRootIsCalculatedCorrectlyByPHPFor9()
    {
        $this->assertEquals(3, sqrt(9));
    }

    public function testSquareRootIsCalculatedCorrectlyByPHPFor16()
    {
        $this->assertEquals(4, sqrt(16));
    }

    public function testSquareRootIsCalculatedCorrectlyByPHPForMinusOne()
    {
        $this->assertEquals('i', sqrt(-1));
    }
}

class ParameterizedTest extends PHPUnit_Framework_TestCase
{
    /**
     * This should be a static method, returning an array of arrays.
     * Each row of this table-like array is a set of arguments
     * for the Test Method.
     */
    public static function squaresAndRoots()
    {
        return array(
            array(0, 0),
            array(1, 1),
            array(4, 2),
            array(9, 3),
            array(16, 4),
            array(-1, 'i')
        );
    }

    /**
     * The annotation requires as unique argument the name of a public method
     * in this Testcase Class.
     * @dataProvider squaresAndRoots
     */
    public function testSquareRootIsCalculatedCorrectlyByPHP($square, $root)
    {
        $this->assertEquals($root, sqrt($square));
    }
}

class ProgrammaticDataProviderTest extends PHPUnit_Framework_TestCase
{
    private static $width = 5;
    private static $height = 10;

    /**
     * I don't know why these methods are required to be static. It makes
     * no difference however as we are never going to call it directly.
     */
    public static function everyCoupleOfCoordinates()
    {
        $testParameters = array();
        for ($x = 1; $x <= self::$width; $x++) {
            for ($y = 1; $y <= self::$height; $y++) {
                $testParameters[] = array($x, $y);
            }
        }
        return $testParameters;
    }

    /**
     * I don't advise you to test an image at every pixel, but in some cases
     * you may need an exhaustive search. The programmatic generation of data
     * keeps the test code really short, but don't forget that the generation
     * logic may hide bugs if it becomes too complex.
     * @dataProvider everyCoupleOfCoordinates
     */
    public function testImageHasCorrectTransparencyValue($x, $y)
    {
        $this->fail("This test will be executed in isolation with the x=$x and y=$y values.");
    }
}
