<?php

declare(strict_types=1);

use MarjovanLier\StringManipulation\Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Configuration
|--------------------------------------------------------------------------
|
| Pest configuration for StringManipulation library tests.
|
*/

pest()->extend(TestCase::class)->in('Unit');

/*
|--------------------------------------------------------------------------
| Global Functions
|--------------------------------------------------------------------------
|
| Import PHPUnit assertion functions for use in functional tests.
|
*/

uses()->beforeEach(function (): void {
    // This ensures the test case is properly set up
})->in('Unit');
