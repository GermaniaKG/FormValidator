# Germania KG · FormValidator

**Callable for validating and filtering user inputs with convenient evaluation API.**


[![Packagist](https://img.shields.io/packagist/v/germania-kg/formvalidator.svg?style=flat)](https://packagist.org/packages/germania-kg/formvalidator)
[![PHP version](https://img.shields.io/packagist/php-v/germania-kg/formvalidator.svg)](https://packagist.org/packages/germania-kg/formvalidator)
[![Build Status](https://img.shields.io/travis/GermaniaKG/FormValidator.svg?label=Travis%20CI)](https://travis-ci.org/GermaniaKG/FormValidator)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/GermaniaKG/FormValidator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/FormValidator/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/GermaniaKG/FormValidator/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/FormValidator/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/GermaniaKG/FormValidator/badges/build.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/FormValidator/build-status/master)


## Installation with Composer

```bash
$ composer require germania-kg/formvalidator
```


## Form validation

```php
<?php
use Germania\FormValidator\FormValidator;
use Germania\FormValidator\InputContainer;

// Setup
$required = [
	"send_button"     =>   FILTER_VALIDATE_BOOLEAN,
	"user_email"      =>   FILTER_VALIDATE_EMAIL,
	"user_login_name" =>   FILTER_SANITIZE_STRING
];

$optional = [
	"family_name"     =>   FILTER_SANITIZE_STRING,
	"first_name"      =>   FILTER_SANITIZE_STRING
];

$formtest = new FormValidator( $required, $optional );

// Invoking uses PHP's filter_var_array internally.
// Return value is InputContainer instance:
$filtered_input = $formtest( $_POST );

// At least one required field valid?
echo $formtest->isSubmitted();

// All required fields valid?
echo $formtest->isValid();


```

## Adding fields

After instantiation, you can add required or optional fields. An existing *optional* field is no longer optional if added using `addRequired`, the same goes with *required* fields, if added using `addOptional `.

```php
$formtest = new FormValidator( $required, $optional );

$formtest->addRequired('additional_info', FILTER_SANITIZE_STRING);
$formtest->addOptional('additional_info', FILTER_SANITIZE_STRING);
```


## Removing fields

After instantiation, you can remove required or optional fields.

```php
$formtest = new FormValidator( $required, $optional );

$formtest->removeRequired('user_email');
$formtest->removeOptional('family_name');
```



## Filtered Result: InputContainer

The *InputContainer* is a [PSR-11 Container](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-11-container.md) and also implements [ArrayAccess.](http://php.net/manual/de/class.arrayaccess.php)


### ArrayAccess

```php
<?php
// Invocation returns InputContainer instance
$filtered_input = $formtest( $_POST );

// ArrayAccess: 
// If field not set, return values are null.
echo $filtered_input['foo'];
echo $filtered_input->offsetGet('foo');
```

### ContainerInterface

```php
<?php
use Germania\FormValidator\NotFoundException;
use Psr\Container\NotFoundExceptionInterface;

// Invocation returns InputContainer instance
$filtered_input = $formtest( $_POST );

try {
	echo $filtered_input->has('foo');
	echo $filtered_input->get('foo');
}
catch (NotFoundException $e) {
	// not found
}
catch (NotFoundExceptionInterface $e) {
	// not found
}
```



## Filtered Result: Custom InputContainer

The *FormValidator* class optionally accepts a Callable that takes the filtered input. It should return something useful (such as the default *InputContainer*).

**Variant A:** Using the constructor

```php
// Setup the factory
$factory = function( $filtered_input ) {
    return new \ArrayObject( $filtered_input );
};

// Pass to the ctor
$formtest = new FormValidator( $required, $optional, $factory );

// Returns an ArrayObject
$filtered_input = $formtest( $_POST );
```

**Variant B:** Use per call

```php
// Setup as usual:
$formtest = new FormValidator( $required, $optional );
$filtered_input = $formtest( $_POST );

// While the above returns the usual InputContainer,
// this will return an ArrayObject:
$filtered_input = $formtest( $_POST, function( $filtered_input ) {
    return new \ArrayObject( $filtered_input );
});
```

## 

## Issues

See [issues list.][i0]

[i0]: https://github.com/GermaniaKG/FormValidator/issues

## Development

```bash
$ git clone https://github.com/GermaniaKG/FormValidator.git
$ cd FormValidator
$ composer install
```

## Unit tests

Either copy `phpunit.xml.dist` to `phpunit.xml` and adapt to your needs, or leave as is. Run [PhpUnit](https://phpunit.de/) test or composer scripts like this:

```bash
$ composer test
# or
$ vendor/bin/phpunit
```


