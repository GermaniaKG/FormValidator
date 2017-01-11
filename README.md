#Germania\FormValidator

**Callable for validating and filtering user inputs with convenient evaluation API.**


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


##Development and Testing

Develop using `develop` branch, using [Git Flow](https://github.com/nvie/gitflow).   

```bash
$ git clone git@github.com:GermaniaKG/FormValidator.git formvalidator
$ cd formvalidator
$ cp phpunit.xml.dist phpunit.xml
$ phpunit
```
