<?php

namespace SebastianKnott\HamcrestObjectAccessor\Test\Integration;

use SebastianKnott\HamcrestObjectAccessor\HasProperty;
use SebastianKnott\HamcrestObjectAccessor\Test\Unit\fixtures\HasPropertyFixture;
use Hamcrest\MatcherAssert;
use PHPUnit\Framework\TestCase;

class HasPropertyTest extends TestCase
{
    protected function setUp()
    {
        require_once __DIR__ . '/../../src/functions.php';
    }

    /**
     * @test
     */
    public function hasProperty()
    {
        $object = new HasPropertyFixture();
        MatcherAssert::assertThat($object, hasProperty('bla', stringValue()));
    }

    /**
     * @test
     */
    public function hasPropertyByNamespace()
    {
        $object = new HasPropertyFixture();
        MatcherAssert::assertThat($object, HasProperty::hasProperty('bla', stringValue()));
    }

    /**
     * @test
     * @expectedException        \Hamcrest\AssertionError
     * @expectedExceptionMessage an object with public property
     */
    public function hasPropertyThrowsExpectedException()
    {
        $object = new HasPropertyFixture();
        MatcherAssert::assertThat($object, hasProperty('bla', intValue()));
    }
}
