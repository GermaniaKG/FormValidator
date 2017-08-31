#Germania KG · FormValidator

**Callable for validating and filtering user inputs with convenient evaluation API.**

[![Build Status](https://travis-ci.org/GermaniaKG/FormValidator.svg?branch=master)](https://travis-ci.org/GermaniaKG/FormValidator)
[![Code Coverage](https://scrutinizer-ci.com/g/GermaniaKG/FormValidator/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/FormValidator/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/GermaniaKG/FormValidator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/FormValidator/?branch=master)


## Installation

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

## Filtered Result: InputContainer

The *InputContainer* is a 
[PSR-11 Container](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-11-container.md) and
also implements [ArrayAccess.](http://php.net/manual/de/class.arrayaccess.php)


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




## Issues

On Travis CI, the PhpUnit **FormValidatorTest** [fails on HHVM](https://travis-ci.org/GermaniaKG/FormValidator/jobs/190888985). Maybe this has something to do with [this HHVM filter_var issue](http://stackoverflow.com/questions/16756576/is-there-an-alternative-to-the-filter-var-function-in-php-when-using-hhvm) described on StackOverflow. Discuss at [issue #1][i1].

Also see [full issues list.][i0]

[i0]: https://github.com/GermaniaKG/FormValidator/issues 
[i1]: https://github.com/GermaniaKG/FormValidator/issues/1

## Development and Testing

Develop using `develop` branch, using [Git Flow](https://github.com/nvie/gitflow).   

```bash
$ git clone git@github.com:GermaniaKG/FormValidator.git formvalidator
$ cd formvalidator
$ cp phpunit.xml.dist phpunit.xml
$ phpunit
```
