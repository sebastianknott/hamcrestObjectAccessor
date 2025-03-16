<?php

declare(strict_types=1);

namespace SebastianKnott\HamcrestObjectAccessor\Test\Integration;

use Hamcrest\AssertionError;
use Hamcrest\MatcherAssert;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\RunClassInSeparateProcess;
use PHPUnit\Framework\Attributes\Test;
use SebastianKnott\HamcrestObjectAccessor\HasProperty;
use SebastianKnott\HamcrestObjectAccessor\Test\Unit\Fixtures\HasPropertyFixture;

require_once __DIR__ . '/../../src/functions.php';

#[CoversClass(HasProperty::class)]
#[CoversFunction('hasProperty')]
#[RunClassInSeparateProcess]
class HasPropertyTest extends MockeryTestCase
{
    #[Test]
    public function hasProperty(): void
    {
        $object = new HasPropertyFixture();
        MatcherAssert::assertThat($object, hasProperty('bla', stringValue()));
    }

    #[Test]
    public function hasPropertyByNamespace(): void
    {
        $object = new HasPropertyFixture();
        MatcherAssert::assertThat($object, HasProperty::hasProperty('bla', stringValue()));
    }

    #[Test]
    public function hasPropertyThrowsExpectedException(): void
    {
        $this->expectException(AssertionError::class);
        $this->expectExceptionMessageMatches(
            '/(.*neither the property "blarg" nor one of the methods.*)'
            . '|(.*can\'t get a way to read the property "blarg" in class '
            . '"SebastianKnott\\\\HamcrestObjectAccessor\\\\Test\\\\Unit\\\\Fixtures\\\\HasPropertyFixture").*/',
        );
        $object = new HasPropertyFixture();
        MatcherAssert::assertThat($object, hasProperty('blarg', intValue()));
    }
}
