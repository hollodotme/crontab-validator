[![Build Status](https://travis-ci.org/hollodotme/crontab-validator.svg?branch=master)](https://travis-ci.org/hollodotme/crontab-validator)
[![Coverage Status](https://coveralls.io/repos/github/hollodotme/crontab-validator/badge.svg?branch=master)](https://coveralls.io/github/hollodotme/crontab-validator?branch=master)
[![Latest Stable Version](https://poser.pugx.org/hollodotme/crontab-validator/v/stable)](https://packagist.org/packages/hollodotme/crontab-validator) 
[![Total Downloads](https://poser.pugx.org/hollodotme/crontab-validator/downloads)](https://packagist.org/packages/hollodotme/crontab-validator) 
[![License](https://poser.pugx.org/hollodotme/crontab-validator/license)](https://packagist.org/packages/hollodotme/crontab-validator)

# CrontabValidator

A validator for crontab expressions.

Sources used to determine the allowed expressions:

* https://crontab.guru/
* https://en.wikipedia.org/wiki/Cron

## Features

* Validation of crontab expressions like <kbd>6,21,36,51 7-23/1 * FEB-NOV/2 *</kbd>.

## Requirements

* PHP >= 7.1

## Installation

```
composer require "hollodotme/crontab-validator"
```

## Usage

### Boolean validation

```php
<?php declare(strict_types=1);

namespace MyVendor\MyProject;

use hollodotme\CrontabValidator\CrontabValidator;

$validator = new CrontabValidator();

if ( $validator->isExpressionValid( '6,21,36,51 7-23/1 * FEB-NOV/2 *' ) )
{
	echo 'Expression is valid.';
}
else
{
	echo 'Expression is invalid.';	
}
```

### Guarding

```php
<?php declare(strict_types=1);

namespace MyVendor\MyProject;

use hollodotme\CrontabValidator\CrontabValidator;
use hollodotme\CrontabValidator\Exceptions\InvalidExpressionException;

$validator = new CrontabValidator();

try 
{
	# => All fine, execution continues
	$validator->guardExpressionIsValid( '6,21,36,51 7-23/1 * FEB-NOV/2 *' );
	
	# => This will raise an InvalidExpressionException
	$validator->guardExpressionIsValid( 'this is not a valid interval' );
}
catch ( InvalidExpressionException $e )
{
	echo $e->getMessage();
}
```

**Prints:**

	Invalid crontab expression: "this is not a valid interval"
	

---

Feedback and contributions welcome!
