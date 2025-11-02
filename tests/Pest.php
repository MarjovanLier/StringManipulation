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
