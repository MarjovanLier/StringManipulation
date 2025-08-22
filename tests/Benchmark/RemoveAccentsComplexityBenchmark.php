<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Benchmark;

require_once __DIR__ . '/../../vendor/autoload.php';

use MarjovanLier\StringManipulation\StringManipulation;

/**
 * O(n) complexity verification for removeAccents().
 *
 * Run with: php tests/Benchmark/RemoveAccentsComplexityBenchmark.php
 */
final class RemoveAccentsComplexityBenchmark
{
    private const int ITERATIONS = 10000;

    private const int WARMUP = 100;

    /**
     * Input sizes to test
     */
    private const array LENGTHS = [100, 500, 1000, 2000, 5000];

    /**
     * Base string with accented characters
     */
    private const string BASE = 'Àáâãäåæçèéêëìíîïð';

    public static function run(): void
    {
        echo "O(n) Complexity Verification for removeAccents()\n";
        echo "================================================\n\n";

        echo 'Testing with ' . (string) self::ITERATIONS . " iterations each:\n\n";
        echo "Length\t\tTime (ms)\tOps/sec\t\tµs/op\t\tComplexity Ratio\n";
        echo "------\t\t---------\t-------\t\t-----\t\t----------------\n";

        $previousTime = null;
        $previousLength = null;

        foreach (self::LENGTHS as $length) {
            $testString = self::makeString($length);

            // Warmup
            for ($i = 0; $i < self::WARMUP; ++$i) {
                StringManipulation::removeAccents($testString);
            }

            $start = microtime(true);
            for ($i = 0; $i < self::ITERATIONS; ++$i) {
                StringManipulation::removeAccents($testString);
            }

            $duration = microtime(true) - $start;

            $durationMs = $duration * 1000.0;
            $opsPerSec = (float) self::ITERATIONS / $duration;
            $usPerOp = ($duration * 1_000_000.0) / (float) self::ITERATIONS;

            $complexityRatio = 'baseline';
            if ($previousTime !== null && $previousLength !== null) {
                $expected = $length / $previousLength;
                $actual = $durationMs / $previousTime;
                $complexityRatio = sprintf('%.2fx (expected: %.2fx)', $actual, $expected);
            }

            echo sprintf(
                "%d\t\t%.2f\t\t%s\t%.2f\t\t%s\n",
                $length,
                $durationMs,
                number_format($opsPerSec, 0),
                $usPerOp,
                $complexityRatio,
            );

            $previousTime = $durationMs;
            $previousLength = $length;
        }

        echo "\nInterpretation:\n";
        echo "- If complexity is O(n), time should scale linearly with input size\n";
        echo "- Actual ratio should be close to expected ratio\n";
        echo "- Significant deviation indicates non-linear complexity\n";
        echo "\nOptimization: strtr() provides O(1) character lookup vs O(k) linear search\n";
    }

    /**
     * Build a test string of a given length by repeating the base sequence
     */
    private static function makeString(int $length): string
    {
        $string = str_repeat(self::BASE, (int) ceil($length / strlen(self::BASE)));
        return substr($string, 0, $length);
    }
}

// Run benchmark if executed directly
if (PHP_SAPI === 'cli' && isset($_SERVER['SCRIPT_FILENAME'])) {
    /** @var string $scriptName */
    $scriptName = $_SERVER['SCRIPT_FILENAME'];
    if (basename(__FILE__) === basename($scriptName)) {
        RemoveAccentsComplexityBenchmark::run();
    }
}
