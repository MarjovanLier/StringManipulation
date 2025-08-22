<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Benchmark;

require_once __DIR__ . '/../../vendor/autoload.php';

use MarjovanLier\StringManipulation\StringManipulation;

/**
 * Performance benchmark for nameFix().
 *
 * Run with: php tests/Benchmark/NameFixBenchmark.php
 *
 * @psalm-suppress UnusedVariable
 */
final class NameFixBenchmark
{
    private const int WARMUP = 100;

    private const int ITERATIONS = 30000;

    /**
     * Test names with various complexities requiring different transformations
     */
    private const array TEST_NAMES = [
        'mcdonald',
        'macarthur',
        'van der waals',
        "o'sullivan-smith",
        'de la tòrré',
        'VAN LIER-MCDONALD',
        'COMPLEX_NAME{with}@special:chars_AND_accents_like_café_Münchën-mac jones',
    ];

    public static function run(): void
    {
        echo "nameFix() Performance Benchmark\n";
        echo "===============================\n\n";

        foreach (self::TEST_NAMES as $i => $name) {
            self::benchmarkName($i + 1, $name);
        }

        self::printOptimizationNotes();
    }

    private static function benchmarkName(int $index, string $name): void
    {
        $length = strlen($name);
        echo sprintf("Test Name %d (Length: %d chars):\n", $index, $length);
        echo "Input:  '{$name}'\n";

        for ($i = 0; $i < self::WARMUP; ++$i) {
            StringManipulation::nameFix($name);
        }

        /** @psalm-suppress UnusedVariable */
        $result = '';
        $start = microtime(true);
        for ($i = 0; $i < self::ITERATIONS; ++$i) {
            $result = StringManipulation::nameFix($name);
        }

        $duration = microtime(true) - $start;

        $opsPerSecond = (float) self::ITERATIONS / $duration;
        $usPerOp = ($duration * 1_000_000.0) / (float) self::ITERATIONS;

        echo "Output: '{$result}'\n";
        echo 'Duration: ' . number_format($duration, 4) . " seconds\n";
        echo 'Operations/second: ' . number_format($opsPerSecond, 0) . "\n";
        echo 'Microseconds/operation: ' . number_format($usPerOp, 2) . "\n\n";
    }

    private static function printOptimizationNotes(): void
    {
        echo "Optimization Details:\n";
        echo "====================\n";
        echo "- Consolidated multiple regex operations into fewer passes\n";
        echo "- Reduced string traversals with combined prefix handling\n";
        echo "- Maintains exact same behavior as original\n";
    }
}

// Run benchmark if executed directly
if (PHP_SAPI === 'cli' && isset($_SERVER['SCRIPT_FILENAME'])) {
    /** @var string $scriptName */
    $scriptName = $_SERVER['SCRIPT_FILENAME'];
    if (basename(__FILE__) === basename($scriptName)) {
        NameFixBenchmark::run();
    }
}
