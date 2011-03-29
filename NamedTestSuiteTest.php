<?php
abstract class FakeTest extends PHPUnit_Framework_TestCase
{
    public function testNothing() {}
}

/**
 * @group unit
 * @group domain
 */
class ATest extends FakeTest
{
}

/**
 * @group unit
 * @group application
 */
class BTest extends FakeTest
{
}

/**
 * @group functional
 * @group domain
 */
class CTest extends FakeTest
{
}

/**
 * @group endtoend
 * @group application
 */
class DTest extends FakeTest
{
}
