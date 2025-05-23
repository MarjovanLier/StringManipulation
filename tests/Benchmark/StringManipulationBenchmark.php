<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Benchmark;

require_once __DIR__ . '/../../vendor/autoload.php';

use MarjovanLier\StringManipulation\StringManipulation;

/**
 * Performance benchmark tests for StringManipulation methods.
 *
 * Run with: php tests/Benchmark/StringManipulationBenchmark.php
 */
final class StringManipulationBenchmark
{
    private const ITERATIONS = 10000;

    /**
     * Test data for benchmarks
     */
    private const TEST_STRINGS = [
        'simple' => 'Hello World',
        'accented' => 'Héllö Wörld with àccénts',
        'long' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        'special' => 'Test@String_With{Special}Characters(2023)',
        'unicode' => 'Test with \u00c0\u00c1\u00c2 unicode escapes',
    ];

    /**
     * Run all benchmarks
     */
    public static function run(): void
    {
        echo "StringManipulation Performance Benchmarks\n";
        echo "=========================================\n";
        echo "Iterations per test: " . self::ITERATIONS . "\n\n";

        self::benchmarkStrReplace();
        self::benchmarkRemoveAccents();
        self::benchmarkNameFix();
        self::benchmarkSearchWords();
        self::benchmarkUtf8Ansi();
        self::benchmarkIsValidDate();
    }

    /**
     * Benchmark strReplace method
     */
    private static function benchmarkStrReplace(): void
    {
        echo "strReplace Benchmark:\n";

        // Single character replacement
        $start = microtime(true);
        for ($i = 0; $i < self::ITERATIONS; $i++) {
            StringManipulation::strReplace('o', 'a', self::TEST_STRINGS['simple']);
        }
        $singleCharTime = microtime(true) - $start;
        echo "  Single character replacement: " . number_format($singleCharTime * 1000, 3) . " ms\n";

        // Array replacement
        $start = microtime(true);
        for ($i = 0; $i < self::ITERATIONS; $i++) {
            StringManipulation::strReplace(['e', 'o'], ['a', 'i'], self::TEST_STRINGS['simple']);
        }
        $arrayTime = microtime(true) - $start;
        echo "  Array replacement: " . number_format($arrayTime * 1000, 3) . " ms\n";

        // Long string replacement
        $start = microtime(true);
        for ($i = 0; $i < self::ITERATIONS; $i++) {
            StringManipulation::strReplace('dolor', 'happiness', self::TEST_STRINGS['long']);
        }
        $longStringTime = microtime(true) - $start;
        echo "  Long string replacement: " . number_format($longStringTime * 1000, 3) . " ms\n\n";
    }

    /**
     * Benchmark removeAccents method
     */
    private static function benchmarkRemoveAccents(): void
    {
        echo "removeAccents Benchmark:\n";

        // First call (populates cache)
        $start = microtime(true);
        StringManipulation::removeAccents(self::TEST_STRINGS['accented']);
        $firstCallTime = microtime(true) - $start;
        echo "  First call (cache population): " . number_format($firstCallTime * 1000, 3) . " ms\n";

        // Subsequent calls (using cache)
        $start = microtime(true);
        for ($i = 0; $i < self::ITERATIONS; $i++) {
            StringManipulation::removeAccents(self::TEST_STRINGS['accented']);
        }
        $cachedTime = microtime(true) - $start;
        echo "  Cached calls: " . number_format($cachedTime * 1000, 3) . " ms\n";

        // Long string with accents
        $longAccented = str_repeat(self::TEST_STRINGS['accented'], 10);
        $start = microtime(true);
        for ($i = 0; $i < self::ITERATIONS / 10; $i++) {
            StringManipulation::removeAccents($longAccented);
        }
        $longTime = microtime(true) - $start;
        echo "  Long string: " . number_format($longTime * 1000, 3) . " ms\n\n";
    }

    /**
     * Benchmark nameFix method
     */
    private static function benchmarkNameFix(): void
    {
        echo "nameFix Benchmark:\n";

        $testNames = [
            'mcdonald' => 'mcdonald',
            'van der waals' => 'van der waals',
            'O\'Brien' => 'O\'Brien',
            'MacDonald' => 'MacDonald',
            'de la Cruz' => 'de la Cruz',
        ];

        foreach ($testNames as $name => $input) {
            $start = microtime(true);
            for ($i = 0; $i < self::ITERATIONS; $i++) {
                StringManipulation::nameFix($input);
            }
            $time = microtime(true) - $start;
            echo "  $name: " . number_format($time * 1000, 3) . " ms\n";
        }
        echo "\n";
    }

    /**
     * Benchmark searchWords method
     */
    private static function benchmarkSearchWords(): void
    {
        echo "searchWords Benchmark:\n";

        foreach (self::TEST_STRINGS as $type => $string) {
            $start = microtime(true);
            for ($i = 0; $i < self::ITERATIONS; $i++) {
                StringManipulation::searchWords($string);
            }
            $time = microtime(true) - $start;
            echo "  $type string: " . number_format($time * 1000, 3) . " ms\n";
        }
        echo "\n";
    }

    /**
     * Benchmark utf8Ansi method
     */
    private static function benchmarkUtf8Ansi(): void
    {
        echo "utf8Ansi Benchmark:\n";

        $start = microtime(true);
        for ($i = 0; $i < self::ITERATIONS; $i++) {
            StringManipulation::utf8Ansi(self::TEST_STRINGS['unicode']);
        }
        $time = microtime(true) - $start;
        echo "  Unicode escape decoding: " . number_format($time * 1000, 3) . " ms\n\n";
    }

    /**
     * Benchmark isValidDate method
     */
    private static function benchmarkIsValidDate(): void
    {
        echo "isValidDate Benchmark:\n";

        $testDates = [
            'Valid datetime' => ['2023-12-25 14:30:00', 'Y-m-d H:i:s'],
            'Valid date only' => ['2023-12-25', 'Y-m-d'],
            'Invalid date' => ['2023-02-30 12:00:00', 'Y-m-d H:i:s'],
            'Invalid format' => ['25/12/2023', 'Y-m-d'],
        ];

        foreach ($testDates as $type => $data) {
            [$date, $format] = $data;
            $start = microtime(true);
            for ($i = 0; $i < self::ITERATIONS; $i++) {
                StringManipulation::isValidDate($date, $format);
            }
            $time = microtime(true) - $start;
            echo "  $type: " . number_format($time * 1000, 3) . " ms\n";
        }
        echo "\n";
    }
}

// Run benchmarks if executed directly
if (php_sapi_name() === 'cli' && isset($_SERVER['SCRIPT_FILENAME'])) {
    $scriptName = $_SERVER['SCRIPT_FILENAME'];
    if (is_string($scriptName) && basename(__FILE__) === basename($scriptName)) {
        StringManipulationBenchmark::run();
    }
}
