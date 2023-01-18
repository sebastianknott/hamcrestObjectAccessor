<?php

declare(strict_types=1);

namespace SebastianKnott\HamcrestObjectAccessor;

use Hamcrest\Description;
use Hamcrest\Matcher;
use Hamcrest\TypeSafeDiagnosingMatcher;
use Hamcrest\TypeSafeMatcher;
use Hamcrest\Util;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * This Matcher tries to access a property of an object by name. It uses
 * Getters, public properties, hassers and issers.
 */
class HasProperty extends TypeSafeDiagnosingMatcher
{
    private PropertyAccessorInterface $accessor;

    /**
     * HasProperty constructor.
     *
     */
    public function __construct(private string $propertyName, private Matcher $propertyValueMatcher)
    {
        parent::__construct(TypeSafeMatcher::TYPE_OBJECT);

        $this->accessor = PropertyAccess::createPropertyAccessorBuilder()
            ->enableMagicCall()->getPropertyAccessor();
    }

    /**
     * Generates a description of the object. The description may be part
     * of a description of a larger object of which this is just a component,
     * so it should be worded appropriately.
     *
     *
     */
    public function describeTo(Description $description): void
    {
        $description->appendText(
            'an object with public property "' . $this->propertyName
            . '" with a value matching '
        );
        $this->propertyValueMatcher->describeTo($description);
    }

    /**
     * Subclasses should implement these. The item will already have been checked for
     * the specific type.
     *
     *
     */
    protected function matchesSafelyWithDiagnosticDescription(mixed $item, Description $mismatchDescription): ?bool
    {
        $propertyValue = null;
        try {
            $propertyValue = $this->accessor->getValue($item, $this->propertyName);
            $this->propertyValueMatcher->describeMismatch($propertyValue, $mismatchDescription);
        } catch (NoSuchPropertyException $exception) {
            $exceptionDescription = $exception->getMessage();
            $mismatchDescription->appendText(lcfirst(rtrim($exceptionDescription, '.')) . ' ');
        }

        return $this->propertyValueMatcher->matches($propertyValue);
    }

    /**
     * Static factory method.
     *
     *
     */
    public static function hasProperty(string $propertyName, mixed $propertyValueMatcher): HasProperty
    {
        return new self($propertyName, Util::wrapValueWithIsEqual($propertyValueMatcher));
    }
}
