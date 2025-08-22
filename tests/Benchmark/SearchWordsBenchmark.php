<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Benchmark;

require_once __DIR__ . '/../../vendor/autoload.php';

use MarjovanLier\StringManipulation\StringManipulation;

/**
 * Performance benchmark for searchWords().
 *
 * Run with: php tests/Benchmark/SearchWordsBenchmark.php
 *
 * @psalm-suppress UnusedVariable
 */
final class SearchWordsBenchmark
{
    private const int WARMUP = 100;

    private const int ITERATIONS = 20000;

    /**
     * Test strings with various complexities
     */
    private const array TEST_STRINGS = [
        'simple' => 'Simple Test String',
        'complex' => 'Complex_String{with}(special)@chars:and/accents_like_café_Münchën',
        'long' => '',
        'very_long' => '',
    ];

    public static function run(): void
    {
        $tests = self::buildTestStrings();

        echo "searchWords() Performance Benchmark\n";
        echo "==================================\n\n";

        foreach ($tests as $label => $testString) {
            self::benchmarkString($label, $testString);
        }

        self::printOptimizationNotes();
    }

    /**
     * @return array<string, string>
     */
    private static function buildTestStrings(): array
    {
        $long = str_repeat(
            'MacDonald_O\'Sullivan{@email.com}(phone:123)/accénts_ànd_spécial_çhàrs. ',
            10,
        );
        $veryLong = str_repeat(
            'Very_Long_Complex{String}@With(Many)Special/Characters\\And:Accents_Like_café_résumé_naïve. ',
            50,
        );

        $strings = self::TEST_STRINGS;
        $strings['long'] = $long;
        $strings['very_long'] = $veryLong;
        return $strings;
    }

    private static function benchmarkString(string $label, string $input): void
    {
        $length = strlen($input);
        echo sprintf("%s (%d chars):\n", ucwords(str_replace('_', ' ', $label)), $length);
        echo '  Sample: ' . substr($input, 0, 60) . "...\n";

        for ($i = 0; $i < self::WARMUP; ++$i) {
            StringManipulation::searchWords($input);
        }

        /** @psalm-suppress UnusedVariable */
        $result = '';
        $start = microtime(true);
        for ($i = 0; $i < self::ITERATIONS; ++$i) {
            $result = StringManipulation::searchWords($input) ?? '';
        }

        $duration = microtime(true) - $start;

        $opsPerSecond = (float) self::ITERATIONS / $duration;
        $usPerOp = ($duration * 1_000_000.0) / (float) self::ITERATIONS;

        echo '  Duration: ' . number_format($duration, 4) . " seconds\n";
        echo '  Operations/second: ' . number_format($opsPerSecond, 0) . "\n";
        echo '  Microseconds/operation: ' . number_format($usPerOp, 2) . "\n";
        echo "  Result: '" . substr($result, 0, 50) . "...'\n\n";
    }

    private static function printOptimizationNotes(): void
    {
        echo "Optimization Details:\n";
        echo "====================\n";
        echo "- Single-pass searchWords() using combined character mapping\n";
        echo "- Replaced multi-pass transformations with consolidated logic\n";
        echo "- Maintains exact same output as original implementation\n";
    }
}

// Run benchmark if executed directly
if (PHP_SAPI === 'cli' && isset($_SERVER['SCRIPT_FILENAME'])) {
    /** @var string $scriptName */
    $scriptName = $_SERVER['SCRIPT_FILENAME'];
    if (basename(__FILE__) === basename($scriptName)) {
        SearchWordsBenchmark::run();
    }
}
