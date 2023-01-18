<?php

declare(strict_types=1);

namespace SebastianKnott\HamcrestObjectAccessor\Test\Integration;

use Hamcrest\AssertionError;
use Hamcrest\MatcherAssert;
use SebastianKnott\DevUtils\Test\Infrastructure\DevToolsTestCase;
use SebastianKnott\HamcrestObjectAccessor\HasProperty;
use SebastianKnott\HamcrestObjectAccessor\Test\Unit\Fixtures\HasPropertyFixture;

class HasPropertyTest extends DevToolsTestCase
{
    /**
     * @test
     */
    public function hasProperty(): void
    {
        $object = new HasPropertyFixture();
        MatcherAssert::assertThat($object, hasProperty('bla', stringValue()));
    }

    /**
     * @test
     */
    public function hasPropertyByNamespace(): void
    {
        $object = new HasPropertyFixture();
        MatcherAssert::assertThat($object, HasProperty::hasProperty('bla', stringValue()));
    }

    /**
     * @test
     */
    public function hasPropertyThrowsExpectedException(): void
    {
        $this->expectException(AssertionError::class);
        $this->expectExceptionMessageMatches(
            '/(.*neither the property "blarg" nor one of the methods.*)'
            . '|(.*can\'t get a way to read the property "blarg" in class '
            . '"SebastianKnott\\\\HamcrestObjectAccessor\\\\Test\\\\Unit\\\\Fixtures\\\\HasPropertyFixture").*/'
        );
        $object = new HasPropertyFixture();
        MatcherAssert::assertThat($object, hasProperty('blarg', intValue()));
    }

    protected function setUp(): void
    {
        require_once __DIR__ . '/../../src/functions.php';
    }
}
