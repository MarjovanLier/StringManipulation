<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Benchmark;

require_once __DIR__ . '/../../vendor/autoload.php';

use MarjovanLier\StringManipulation\StringManipulation;

/**
 * Performance and correctness benchmark for removeAccents().
 *
 * Run with: php tests/Benchmark/RemoveAccentsBenchmark.php
 */
final class RemoveAccentsBenchmark
{
    private const int WARMUP = 100;

    private const int ITERATIONS = 10000;

    /**
     * Test strings of various lengths with accent characters
     */
    private const array TEST_STRINGS = [
        'short' => 'Short string with àccénts and spéciàl chàràcters',
        'medium' => '',
        'long' => '',
    ];

    /**
     * Sample inputs for quick correctness verification
     */
    private const array CORRECTNESS_SAMPLES = [
        'Café Münchën',
        'Naïve résumé',
        'Zürich Köln',
        'Ångström Ålesund',
    ];

    /**
     * Run all removeAccents benchmarks
     */
    public static function run(): void
    {
        // Initialize dynamic test strings that depend on repetition
        $testStrings = self::buildTestStrings();

        echo "removeAccents() Performance Benchmark\n";
        echo "=====================================\n\n";

        foreach ($testStrings as $label => $testString) {
            self::benchmarkString($label, $testString);
        }

        self::printCorrectness();
    }

    /**
     * Build dynamic medium/long strings to keep constants typed
     */
    /**
     * @return array<string, string>
     */
    private static function buildTestStrings(): array
    {
        $medium = str_repeat(
            'Thîs ís à lóngér strîng wîth múltîplé àccénts ànd spéciàl chàràcters like ñ, ç, ü, ä, ö. ',
            10,
        );
        $long = str_repeat(
            'Ëxtrémély lóng tést strîng wîth númérôús àccéntéd chàràctérs fôr pérfôrmàncé téstîng. ',
            100,
        );

        $strings = self::TEST_STRINGS;
        $strings['medium'] = $medium;
        $strings['long'] = $long;
        return $strings;
    }

    /**
     * Benchmark a single input string
     */
    private static function benchmarkString(string $label, string $input): void
    {
        $length = strlen($input);
        echo sprintf("%s string (Length: %d chars):\n", ucfirst($label), $length);

        for ($j = 0; $j < self::WARMUP; ++$j) {
            StringManipulation::removeAccents($input);
        }

        $start = microtime(true);
        for ($j = 0; $j < self::ITERATIONS; ++$j) {
            StringManipulation::removeAccents($input);
        }

        $duration = microtime(true) - $start;

        $opsPerSecond = (float) self::ITERATIONS / $duration;
        $usPerOp = ($duration * 1_000_000.0) / (float) self::ITERATIONS;

        echo '  Duration: ' . number_format($duration, 4) . " seconds\n";
        echo '  Operations/second: ' . number_format($opsPerSecond, 0) . "\n";
        echo '  Microseconds/operation: ' . number_format($usPerOp, 2) . "\n\n";
    }

    /**
     * Print correctness verification samples
     */
    private static function printCorrectness(): void
    {
        echo "Correctness Verification:\n";
        echo "========================\n";
        foreach (self::CORRECTNESS_SAMPLES as $sample) {
            $result = StringManipulation::removeAccents($sample);
            echo "'{$sample}' → '{$result}'\n";
        }
    }
}

// Run benchmarks if executed directly
if (PHP_SAPI === 'cli' && isset($_SERVER['SCRIPT_FILENAME'])) {
    /** @var string $scriptName */
    $scriptName = $_SERVER['SCRIPT_FILENAME'];
    if (basename(__FILE__) === basename($scriptName)) {
        RemoveAccentsBenchmark::run();
    }
}
