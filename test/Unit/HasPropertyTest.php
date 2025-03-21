<?php

declare(strict_types=1);

namespace SebastianKnott\HamcrestObjectAccessor\Test\Unit;

use Hamcrest\Description;
use Hamcrest\Matcher;
use Hamcrest\Matchers;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use SebastianKnott\HamcrestObjectAccessor\HasProperty;
use SebastianKnott\HamcrestObjectAccessor\Test\Unit\Fixtures\HasPropertyFixture;
use stdClass;

#[CoversClass(HasProperty::class)]
class HasPropertyTest extends MockeryTestCase
{
    private string $propertyName;

    private Matcher&MockInterface $mockedMatcher;

    private HasProperty $subject;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->propertyName  = 'MyItem';
        $this->mockedMatcher = mock(Matcher::class);

        $this->subject = new HasProperty($this->propertyName, $this->mockedMatcher);
    }

    #[Test]
    public function hasProperty(): void
    {
        $subject = HasProperty::hasProperty(
            'propertyName',
            'propertyValue',
        );

        // @phpstan-ignore-next-line
        self::assertInstanceOf(HasProperty::class, $subject);
    }

    #[Test]
    public function describeTo(): void
    {
        $mockedDescription = mock(Description::class);
        $mockedDescription->shouldReceive('appendText')->once()
            ->with(
                'an object with public property "' . $this->propertyName
                . '" with a value matching ',
            );
        $this->mockedMatcher->shouldReceive('describeTo')->once()
            ->with($mockedDescription);

        $this->subject->describeTo($mockedDescription);
    }

    /** @return array<mixed> */
    public static function matchesReturnsExpectedResultDataProvider(): array
    {
        return [
            'object without property' => [new stdClass(), 'bla', 'blub', false],
            'object with property' => [
                new HasPropertyFixture(),
                'bla',
                'blub',
                true,
            ],
            'object with getter' => [
                new HasPropertyFixture(),
                'getable',
                'blub',
                true,
            ],
            'object with isser' => [
                new HasPropertyFixture(),
                'issable',
                true,
                true,
            ],
            'object with hasser' => [
                new HasPropertyFixture(),
                'hassable',
                true,
                true,
            ],
        ];
    }

    #[Test]
    #[DataProvider('matchesReturnsExpectedResultDataProvider')]
    public function matchesSafelyReturnsExpectedResult(
        HasPropertyFixture|stdClass $item,
        string $propertyName,
        mixed $propertyValue,
        bool $expectedResult,
    ): void {
        $subject = HasProperty::hasProperty($propertyName, $propertyValue);
        $result  = $subject->matchesSafely($item);
        self::assertSame($expectedResult, $result);
    }

    #[Test]
    public function describeMismatchSafelyReturnsExpectedResult(): void
    {
        $item          = new stdClass();
        $propertyName  = 'bla';
        $propertyValue = 'blub';

        /** @var MockInterface|Description $mockedDescription */
        $mockedDescription = mock(Description::class);
        $mockedDescription->shouldReceive('appendText')->once()
            ->with(
                Matchers::either(
                    is(
                        'neither the property "' . $propertyName . '" nor one of the methods '
                        . '"get' . ucfirst($propertyName) . '()", "' . $propertyName . '()", "is'
                        . ucfirst($propertyName)
                        . '()", "has' . ucfirst($propertyName) . '()", "__get()", '
                        . '"__call()" exist and have public access in class "stdClass" ',
                    ),
                )->orElse(
                    is('can\'t get a way to read the property "bla" in class "stdClass" '),
                ),
            )->andReturnSelf();

        $subject = HasProperty::hasProperty($propertyName, $propertyValue);
        $subject->describeMismatchSafely($item, $mockedDescription);
    }

    #[Test]
    public function matchesSafelyParameterNeedsToBeObject(): void
    {
        $result = $this->subject->matches('asd');
        self::assertFalse($result);
    }

    #[Test]
    public function diagnosticDescriptionIsForwarded(): void
    {
        $forgedObject      = new stdClass();
        $forgedObject->bla = 'wubwub';

        $mockedMatcher = mock(Matcher::class);
        $mockedMatcher->shouldReceive('describeMismatch')->once()
            ->with('wubwub', anInstanceOf(Description::class));
        $mockedMatcher->shouldReceive('matches')->once()
            ->with('wubwub')->andReturn(true);

        $subject = new HasProperty('bla', $mockedMatcher);
        $subject->matchesSafely($forgedObject);
    }
}
