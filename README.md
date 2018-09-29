[![Build Status](https://www.travis-ci.org/doomhammerchen/hamcrestObjectAccessor.svg?branch=master)](https://www.travis-ci.org/doomhammerchen/hamcrestObjectAccessor)
# Hamcrest Object Accessor

This package extends the collection of matchers in 
[Hamcrest](https://github.com/hamcrest/hamcrest-php). For general 
informationan about [Hamcrest](https://github.com/hamcrest/hamcrest-php)
please visit their website. It's worth it ^^.

## hasProperty

This Matcher tries to access a property of an object by name.
It uses Getters, public properties, hassers and issers.

### Example Class
```php
class HasPropertyFixture
{
    public $bla = 'blub';
    private $getable = 'blub';
    private $issable = true;
    private $hassable = true;
    private $notGettable = 'nope';

    public function getGetable()
    {
        return $this->getable;
    }

    public function isIssable()
    {
        return $this->issable;
    }

    public function hasHassable()
    {
        return $this->hassable;
    }
}
```

### Matcher 

```php
$object = new hasPropertyFixture();
MatcherAssert::assertThat(
    $object, 
    hasProperty(
        'bla', // property name
        stringValue() // matcher the property value has to match
    )
);

MatcherAssert::assertThat(
    $object, 
    hasProperty(
        'Getable', 
        stringValue()
    )
);

MatcherAssert::assertThat(
    $object, 
    hasProperty(
        'isIssable', 
        boolValue()
    )
);

```

## Installation

Once it's released on Packagist you may include this package
by composer

`composer require --dev sebastianknott/hamcrest-object-accessor`

I recommend to use this matcher in dev environments only!

### Setup

Once the Package is installed you can call the matcher staticly ...

`HasProperty::hasProperty('bla', stringValue()));`

... or by requiring `src/functions.php` provided by this package.
