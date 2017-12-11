# CHANGELOG

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org) and [Keep a CHANGELOG](http://keepachangelog.com).

## [2.0.0] - 2017-12-11

### Added

* Support for the `L` modifier in the day of month section
* Support for the `W` modifier in the day of month section
* Support for the `L` modifier in the day of week section
* Support for the `#[1-5]` modifier in the day of week section
* Support for the `?` instead of an `*` in day of month section
* Support for the `?` instead of an `*` in day of week section
* Support for whitespaces at the beginning and end of the expression
* Stricter step value checking for each section

### Changed

* Terminology from "crontab interval" to "crontab expression"
* Renamed exception class `InvalidCrontabInterval` to `InvalidExpressionException`
* Class `InvalidExpressionException` now extends `\InvalidArgumentException` instead of `\Exception`
* Fixed `InvalidExpressionException`'s message to match the documentation
* Renamed `CrontabValidator->isIntervalValid()` to `CrontabValidator->isExpressionValid()`
* Renamed `CrontabValidator->guardIntervalIsValid()` to `CrontabValidator->guardExpressionIsValid()`
* Renamed parameters and method names from "interval" to "expression"

### Removed

* Support for PHP 7.0 (Supported versions: PHP >= 7.1)
* Package base exception `CrontabValidatorException`

## [1.0.1] - 2017-12-05

### Fixed

* Unit test compatibility with PHPUnit 6+
* Usage of alias function `join`

## [1.0.0] - 2016-08-29

### Added

* Interval validation in boolean and/or guarding manner

[2.0.0]: https://github.com/hollodotme/crontab-validator/compare/v1.0.1...v2.0.0
[1.0.1]: https://github.com/hollodotme/crontab-validator/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/hollodotme/crontab-validator/tree/v1.0.0
