#Germania\FormValidator

**Callable for validating and filtering user inputs with convenient evaluation API.**

[![Build Status](https://travis-ci.org/GermaniaKG/FormValidator.svg?branch=master)](https://travis-ci.org/GermaniaKG/FormValidator)
[![Code Coverage](https://scrutinizer-ci.com/g/GermaniaKG/FormValidator/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/FormValidator/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/GermaniaKG/FormValidator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/FormValidator/?branch=master)


##Installation

```bash
$ composer require germania-kg/formvalidator
```


##Usage

```php
<?php
use Germania\FormValidator\FormValidator;

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

// Uses PHP's filter_var_array internally
$filtered_input = $formtest( $_POST );

// At least one required field valid
echo $formtest->isSubmitted();

// All required fields valid
echo $formtest->isValid();


```


##Issues on HHVM

- On Travis CI, the PhpUnit **FormValidatorTest** [fails on HHVM](https://travis-ci.org/GermaniaKG/FormValidator/jobs/190888985). Maybe this has something to do with [this HHVM filter_var issue](http://stackoverflow.com/questions/16756576/is-there-an-alternative-to-the-filter-var-function-in-php-when-using-hhvm) described on StackOverflow. For now, HHVM is disabled in `.travis.yml`. The FormValidator does meanwhile work on PHP 5.6+

##Development and Testing

Develop using `develop` branch, using [Git Flow](https://github.com/nvie/gitflow).   

```bash
$ git clone git@github.com:GermaniaKG/FormValidator.git formvalidator
$ cd formvalidator
$ cp phpunit.xml.dist phpunit.xml
$ phpunit
```
