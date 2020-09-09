<?php

namespace SebastianKnott\HamcrestObjectAccessor\Test\Unit;

use SebastianKnott\HamcrestObjectAccessor\HasProperty;
use SebastianKnott\HamcrestObjectAccessor\Test\Unit\fixtures\HasPropertyFixture;
use Hamcrest\Description;
use Hamcrest\Matcher;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class HasPropertyTest extends TestCase
{
    /** @var string */
    private $propertyName;
    /** @var MockInterface|Matcher $mockedMatcher */
    private $mockedMatcher;
    /** @var HasProperty */
    private $subject;

    protected function setUp()
    {
        $this->propertyName  = 'MyItem';
        $this->mockedMatcher = Mockery::mock(Matcher::class);

        $this->subject = new HasProperty($this->propertyName, $this->mockedMatcher);
    }

    /**
     * @test
     */
    public function hasProperty()
    {
        $subject = HasProperty::hasProperty(
            'propertyName',
            'propertyValue'
        );

        self::assertInstanceOf(HasProperty::class, $subject);
    }

    /**
     * @test
     */
    public function describeTo()
    {
        /** @var MockInterface|Description $mockedDescription */
        $mockedDescription = Mockery::mock(Description::class);
        $mockedDescription->shouldReceive('appendText')->once()
            ->with(
                'an object with public property "' . $this->propertyName
                . '" with a value matching '
            );
        $this->mockedMatcher->shouldReceive('describeTo')->once()
            ->with($mockedDescription);

        $this->subject->describeTo($mockedDescription);
    }

    public function matchesReturnsExpectedResultDataProvider()
    {
        return array_merge(
            $this->matchesReturnsExpectedResultDataProviderSimple(),
            [
                'object without property' => [new stdClass(), 'bla', 'blub', false]
            ]
        );
    }

    public function matchesReturnsExpectedResultDataProviderSimple()
    {
        return [
            'object with property'    => [
                new HasPropertyFixture(),
                'bla',
                'blub',
                true
            ],
            'object with getter'      => [
                new HasPropertyFixture(),
                'getable',
                'blub',
                true
            ],
            'object with isser'       => [
                new HasPropertyFixture(),
                'issable',
                true,
                true
            ],
            'object with hasser'      => [
                new HasPropertyFixture(),
                'hassable',
                true,
                true
            ],
        ];
    }


    /**
     * @test
     * @dataProvider matchesReturnsExpectedResultDataProvider
     *
     * @param mixed $item
     * @param string $propertyName
     * @param string $propertyValue
     * @param bool $expectedResult
     */
    public function matchesSafelyReturnsExpectedResult(
        $item,
        $propertyName,
        $propertyValue,
        $expectedResult
    ) {
        $subject = HasProperty::hasProperty($propertyName, $propertyValue);
        $result  = $subject->matchesSafely($item);
        self::assertSame($expectedResult, $result);
    }

    /**
     * @test
     * @dataProvider matchesReturnsExpectedResultDataProviderSimple
     *
     * @param mixed $item
     * @param string $propertyName
     * @param string $propertyValue
     */
    public function describeMismatchSafelyReturnsExpectedResult(
        $item,
        $propertyName,
        $propertyValue
    ) {
        /** @var MockInterface|Description $mockedDescription */
        $mockedDescription = Mockery::mock(Description::class);
        $mockedDescription->shouldReceive('appendText')->once()
            ->with(
                'neither the property "' . $propertyName . '" nor one of the methods '
                . '"get' . ucfirst($propertyName) . '()", "' . $propertyName . '()", "is' . ucfirst($propertyName)
                . '()", "has' . ucfirst($propertyName) . '()", "__get()", '
                . '"__call()" exist and have public access in class "stdClass" '
            )->andReturnSelf();
        $mockedDescription->shouldReceive('appendText')->once()
            ->with('was ')->andReturnSelf();
        $mockedDescription->shouldReceive('appendValue')->once()
            ->with('blub')->andReturnSelf();

        $subject = HasProperty::hasProperty($propertyName, $propertyValue);
        $subject->describeMismatchSafely($item, $mockedDescription);
    }

    /**
     * @test
     *
     */
    public function describeMismatchSafelyReturnsExpectedResultForStdClass(
    ) {
        $item = new stdClass();
        $propertyName = 'bla';
        $propertyValue = 'blub';
        /** @var MockInterface|Description $mockedDescription */
        $mockedDescription = Mockery::mock(Description::class);
        $mockedDescription->shouldReceive('appendText')->once()
            ->with(
                'can\'t get a way to read the property "'. $propertyName .'" in class "stdClass" '
            )->andReturnSelf();
        $mockedDescription->shouldReceive('appendText')->once()
            ->with('was ')->andReturnSelf();
        $mockedDescription->shouldReceive('appendValue')->once()
            ->with('blub')->andReturnSelf();

        $subject = HasProperty::hasProperty($propertyName, $propertyValue);
        $subject->describeMismatchSafely($item, $mockedDescription);
    }

}
