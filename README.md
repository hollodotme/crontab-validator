[![Build Status](https://travis-ci.org/hollodotme/crontab-validator.svg?branch=master)](https://travis-ci.org/hollodotme/crontab-validator)
[![Coverage Status](https://coveralls.io/repos/github/hollodotme/crontab-validator/badge.svg?branch=master)](https://coveralls.io/github/hollodotme/crontab-validator?branch=master)
[![Latest Stable Version](https://poser.pugx.org/hollodotme/crontab-validator/v/stable)](https://packagist.org/packages/hollodotme/crontab-validator) 
[![Total Downloads](https://poser.pugx.org/hollodotme/crontab-validator/downloads)](https://packagist.org/packages/hollodotme/crontab-validator) 
[![Latest Unstable Version](https://poser.pugx.org/hollodotme/crontab-validator/v/unstable)](https://packagist.org/packages/hollodotme/crontab-validator) 
[![License](https://poser.pugx.org/hollodotme/crontab-validator/license)](https://packagist.org/packages/hollodotme/crontab-validator)

# hollodotme\CrontabValidator

A validator for crontab values

## Features

* Validation of crontab interval strings like <kbd>6,21,36,51 7-23/1 * FEB-NOV/2 *</kbd>.

## Requirements

* PHP >= 7.0.0

## Usage

### Boolean validation

```php
<?php declare(strict_types=1);

namespace MyVendor\MyProject;

use hollodotme\CrontabValidator\CrontabValidator;

$validator = new CrontabValidator();
if ( $validator->isIntervalValid('6,21,36,51 7-23/1 * FEB-NOV/2 *') )
{
	echo "Interval is valid.";
}
else
{
	echo "Interval is invalid.";	
}
```

### Guarding

```php
<?php declare(strict_types=1);

namespace MyVendor\MyProject;

use hollodotme\CrontabValidator\CrontabValidator;
use hollodotme\CrontabValidator\Exceptions\InvalidCrontabInterval;

$validator = new CrontabValidator();

try 
{
	# => All fine, execution continues
	$validator->guardIntervalIsValid('6,21,36,51 7-23/1 * FEB-NOV/2 *');
	
	# => This will raise an InvalidCrontabInterval exception
	$validator->guardIntervalIsValid('this is not a valid interval');
}
catch (InvalidCrontabInterval $e)
{
	echo $e->getMessage() . ': "' . $e->getCrontabInterval() . '"';
}
```

**Prints:**

	Invalid crontab interval: "this is not a valid interval"
	

---

Feedback and contributions welcome!
