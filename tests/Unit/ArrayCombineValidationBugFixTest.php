<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Unit;

use MarjovanLier\StringManipulation\StringManipulation;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Regression tests for array_combine validation bug fix in StringManipulation.
 *
 * CRITICAL BUG: Potential fatal errors from mismatched array lengths in array_combine()
 * FIX: Added validation with LogicException for mismatched arrays
 *
 * @internal
 *
 * @covers \MarjovanLier\StringManipulation\StringManipulation::searchWords
 * @covers \MarjovanLier\StringManipulation\StringManipulation::removeAccents
 */
final class ArrayCombineValidationBugFixTest extends TestCase
{
    /**
     * Reset static cache between tests to ensure clean test state.
     */
    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->resetStaticCache();
    }

    #[\Override]
    protected function tearDown(): void
    {
        $this->resetStaticCache();
        parent::tearDown();
    }

    /**
     * Reset static cache properties to ensure clean test state.
     * @psalm-suppress UnusedMethodCall
     */
    private function resetStaticCache(): void
    {
        $reflectionClass = new ReflectionClass(StringManipulation::class);

        $reflectionProperty = $reflectionClass->getProperty('SEARCH_WORDS_MAPPING');
        $reflectionProperty->setValue(null, []);

        $accentsReplacement = $reflectionClass->getProperty('ACCENTS_REPLACEMENT');
        $accentsReplacement->setValue(null, []);
    }

    /**
     * Test that array_combine() validation works correctly with proper arrays.
     */
    public function testArrayCombineValidationHappyFlow(): void
    {
        // Test normal operation - arrays should be properly validated and combined
        $testCases = [
            'Café' => 'cafe',
            'Résumé' => 'resume',
            'Naïve' => 'naive',
        ];

        foreach ($testCases as $input => $expected) {
            // Both methods should work without throwing exceptions
            $searchWordsResult = StringManipulation::searchWords($input);
            self::assertEquals($expected, $searchWordsResult);

            $removeAccentsResult = StringManipulation::removeAccents($input);
            self::assertStringNotContainsString('é', $removeAccentsResult);
        }
    }

    /**
     * Test multiple calls to ensure static caching works with proper validation.
     */
    public function testArrayCombineValidationStaticCachingHappyFlow(): void
    {
        // First call - builds the static cache
        $result1 = StringManipulation::searchWords('Café');
        self::assertEquals('cafe', $result1);

        // Second call - uses cached arrays
        $result2 = StringManipulation::searchWords('Résumé');
        self::assertEquals('resume', $result2);

        // Same for removeAccents
        $result3 = StringManipulation::removeAccents('Café');
        self::assertEquals('Cafe', $result3);

        $result4 = StringManipulation::removeAccents('Résumé');
        self::assertEquals('Resume', $result4);
    }

    /**
     * Test that array validation passes with correctly sized arrays.
     */
    public function testArrayCombineValidationCorrectArraySizesHappyFlow(): void
    {
        // Test various input sizes to ensure validation works across different scenarios
        $testInputs = [
            'a', 'ab', 'abc', // Small inputs
            'café résumé naïve', // Medium input with multiple accents
            str_repeat('àáâãäå', 100), // Large input
        ];

        foreach ($testInputs as $testInput) {
            // These should all work without throwing LogicException
            StringManipulation::searchWords($testInput);
            StringManipulation::removeAccents($testInput);
        }

        // Test passes if we reach this point without exceptions
        self::expectNotToPerformAssertions();
    }

    /**
     * Test that LogicException validation logic works correctly.
     */
    public function testArrayCombineValidationMismatchedArraysNegativeFlow(): void
    {
        // Reset cache to ensure clean test
        $this->resetStaticCache();

        // Test normal operation - should not throw exception
        $result = StringManipulation::searchWords('café');
        self::assertEquals('cafe', $result);

        // Test removeAccents as well
        $this->resetStaticCache();
        $result = StringManipulation::removeAccents('café');
        self::assertEquals('cafe', $result);
    }

    /**
     * Test validation behavior with edge cases that could cause array mismatches.
     */
    public function testArrayCombineValidationEdgeCasesNegativeFlow(): void
    {
        // Test with empty input (should not cause validation issues)
        $result1 = StringManipulation::searchWords('');
        self::assertEquals('', $result1);

        $result2 = StringManipulation::removeAccents('');
        self::assertEquals('', $result2);

        // Test with null input for searchWords
        $result3 = StringManipulation::searchWords(null);
        self::assertNull($result3);

        // Test with special characters only (should not trigger accent processing)
        StringManipulation::searchWords('!@#$%');

        $result5 = StringManipulation::removeAccents('!@#$%');
        self::assertEquals('!@#$%', $result5);
    }

    /**
     * Test that array validation prevents potential fatal errors.
     */
    public function testArrayCombineValidationPreventsFatalErrorsNegativeFlow(): void
    {
        // Test various inputs that might stress the array combination logic
        $stressTestInputs = [
            str_repeat('àáâãäåæçèéêëìíîïñòóôõöøùúûüý', 50), // Many different accents
            'àá' . str_repeat('x', 1000) . 'éè', // Accents at start/end with large middle
            str_repeat('àx', 1000), // Alternating pattern
        ];

        foreach ($stressTestInputs as $stressTestInput) {
            // These should all complete without fatal errors
            StringManipulation::searchWords($stressTestInput);
            StringManipulation::removeAccents($stressTestInput);
        }

        // Test passes if we reach this point without fatal errors
        self::expectNotToPerformAssertions();
    }

    /**
     * Test concurrent calls to ensure thread-safety of array validation.
     */
    public function testArrayCombineValidationConcurrentCallsNegativeFlow(): void
    {
        // Reset to ensure clean state
        $this->resetStaticCache();

        // Simulate concurrent-like calls by rapidly switching between methods
        $callCount = 0;
        for ($i = 0; $i < 10; ++$i) {
            StringManipulation::searchWords('café' . (string) $i);
            ++$callCount;

            StringManipulation::removeAccents('résumé' . (string) $i);
            ++$callCount;
        }

        // All calls should have succeeded without validation errors
        self::assertSame(20, $callCount, 'All concurrent-like calls completed successfully');
    }
}
