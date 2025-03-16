<?php

declare(strict_types=1);

use SebastianKnott\HamcrestObjectAccessor\HasProperty;

if (!function_exists('hasProperty')) {
    /**
     * This Matcher tries to access a property of an object by name. It uses
     * Getters, public properties, hassers and issers.
     */
    function hasProperty(string $propertyName, mixed $propertyValueMatcher): HasProperty
    {
        return HasProperty::hasProperty($propertyName, $propertyValueMatcher);
    }
}
