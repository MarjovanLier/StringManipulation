<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Unit;

use MarjovanLier\StringManipulation\StringManipulation;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Regression tests for uppercase accent mapping bug fix in StringManipulation.
 *
 * CRITICAL BUG: Previously searchWords('À') returned 'A' instead of 'a'
 * FIX: Apply strtolower() to REMOVE_ACCENTS_TO values
 *
 * @internal
 */
final class UppercaseAccentMappingBugFixTest extends TestCase
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

        $reflectionProperty = $reflectionClass->getProperty('searchWordsMapping');
        $reflectionProperty->setValue(null, []);

        $accentsReplacement = $reflectionClass->getProperty('accentsReplacement');
        $accentsReplacement->setValue(null, []);
    }

    /**
     * Test that uppercase accented characters in searchWords() properly convert to lowercase.
     */
    public function testSearchWordsUppercaseAccentMappingHappyFlow(): void
    {
        // Test individual uppercase accented characters
        $uppercaseAccentTests = [
            'À' => 'a',     // A with grave accent
            'Á' => 'a',     // A with acute accent
            'Â' => 'a',     // A with circumflex
            'Ã' => 'a',     // A with tilde
            'Ä' => 'a',     // A with diaeresis
            'Å' => 'a',     // A with ring above
            'Æ' => 'ae',    // AE ligature
            'Ç' => 'c',     // C with cedilla
            'È' => 'e',     // E with grave accent
            'É' => 'e',     // E with acute accent
            'Ê' => 'e',     // E with circumflex
            'Ë' => 'e',     // E with diaeresis
            'Ì' => 'i',     // I with grave accent
            'Í' => 'i',     // I with acute accent
            'Î' => 'i',     // I with circumflex
            'Ï' => 'i',     // I with diaeresis
            'Ñ' => 'n',     // N with tilde
            'Ò' => 'o',     // O with grave accent
            'Ó' => 'o',     // O with acute accent
            'Ô' => 'o',     // O with circumflex
            'Õ' => 'o',     // O with tilde
            'Ö' => 'o',     // O with diaeresis
            'Ø' => 'o',     // O with stroke
            'Ù' => 'u',     // U with grave accent
            'Ú' => 'u',     // U with acute accent
            'Û' => 'u',     // U with circumflex
            'Ü' => 'u',     // U with diaeresis
            'Ý' => 'y',     // Y with acute accent
        ];

        foreach ($uppercaseAccentTests as $input => $expected) {
            $result = StringManipulation::searchWords($input);
            self::assertEquals(
                $expected,
                $result,
                sprintf("Failed: searchWords('%s') should return '%s' but got '%s'", $input, $expected, $result ?? 'null'),
            );
        }
    }

    /**
     * Test mixed case words with uppercase accented characters in searchWords().
     */
    public function testSearchWordsMixedCaseAccentedWordsHappyFlow(): void
    {
        $mixedCaseTests = [
            'Café' => 'cafe',
            'CAFÉ' => 'cafe',
            'CaFÉ' => 'cafe',
            'Résumé' => 'resume',
            'RÉSUMÉ' => 'resume',
            'Naïve' => 'naive',
            'NAÏVE' => 'naive',
            'Zürich' => 'zurich',
            'ZÜRICH' => 'zurich',
            'München' => 'munchen',
            'MÜNCHEN' => 'munchen',
            'Ålesund' => 'alesund',
            'ÅLESUND' => 'alesund',
            'Øresund' => 'oresund',
            'ØRESUND' => 'oresund',
        ];

        foreach ($mixedCaseTests as $input => $expected) {
            $result = StringManipulation::searchWords($input);
            self::assertEquals(
                $expected,
                $result,
                sprintf("Failed: searchWords('%s') should return '%s' but got '%s'", $input, $expected, $result ?? 'null'),
            );
        }
    }

    /**
     * Test sentences with uppercase accented characters in searchWords().
     */
    public function testSearchWordsSentencesWithUppercaseAccentsHappyFlow(): void
    {
        $sentenceTests = [
            'À la carte' => 'a la carte',
            'Café-Restaurant' => 'cafe-restaurant',
            'ÉDITION SPÉCIALE' => 'edition speciale',
            'Hôtel de luxe' => 'hotel de luxe',
            'UNIVERSITÉ DE PARIS' => 'universite de paris',
            'Crème brûlée' => 'creme brulee',
            'CHÂTEAU D\'YQUEM' => "chateau d'yquem",
        ];

        foreach ($sentenceTests as $input => $expected) {
            $result = StringManipulation::searchWords($input);
            self::assertEquals(
                $expected,
                $result,
                sprintf("Failed: searchWords('%s') should return '%s' but got '%s'", $input, $expected, $result ?? 'null'),
            );
        }
    }

    /**
     * Test that removeAccents() properly handles uppercase accented characters.
     */
    public function testRemoveAccentsUppercaseAccentMappingHappyFlow(): void
    {
        // Test individual uppercase accented characters - should remain uppercase
        $uppercaseAccentTests = [
            'À' => 'A',     // A with grave accent
            'Á' => 'A',     // A with acute accent
            'Â' => 'A',     // A with circumflex
            'Ã' => 'A',     // A with tilde
            'Ä' => 'A',     // A with diaeresis
            'Å' => 'A',     // A with ring above
            'Æ' => 'AE',    // AE ligature
            'Ç' => 'C',     // C with cedilla
            'È' => 'E',     // E with grave accent
            'É' => 'E',     // E with acute accent
            'Ê' => 'E',     // E with circumflex
            'Ë' => 'E',     // E with diaeresis
            'Ì' => 'I',     // I with grave accent
            'Í' => 'I',     // I with acute accent
            'Î' => 'I',     // I with circumflex
            'Ï' => 'I',     // I with diaeresis
            'Ñ' => 'N',     // N with tilde
            'Ò' => 'O',     // O with grave accent
            'Ó' => 'O',     // O with acute accent
            'Ô' => 'O',     // O with circumflex
            'Õ' => 'O',     // O with tilde
            'Ö' => 'O',     // O with diaeresis
            'Ø' => 'O',     // O with stroke
            'Ù' => 'U',     // U with grave accent
            'Ú' => 'U',     // U with acute accent
            'Û' => 'U',     // U with circumflex
            'Ü' => 'U',     // U with diaeresis
            'Ý' => 'Y',     // Y with acute accent
        ];

        foreach ($uppercaseAccentTests as $input => $expected) {
            $result = StringManipulation::removeAccents($input);
            self::assertEquals(
                $expected,
                $result,
                sprintf("Failed: removeAccents('%s') should return '%s' but got '%s'", $input, $expected, $result),
            );
        }
    }

    /**
     * Test mixed case preservation in removeAccents().
     */
    public function testRemoveAccentsMixedCasePreservationHappyFlow(): void
    {
        $mixedCaseTests = [
            'Café' => 'Cafe',
            'CaFÉ' => 'CaFE',
            'Résumé' => 'Resume',
            'RéSuMé' => 'ReSuMe',
            'Naïve' => 'Naive',
            'NaÏvE' => 'NaIvE',
            'Zürich' => 'Zurich',
            'ZüRiCh' => 'ZuRiCh',
            'München' => 'Munchen',
            'MüNcHeN' => 'MuNcHeN',
        ];

        foreach ($mixedCaseTests as $input => $expected) {
            $result = StringManipulation::removeAccents($input);
            self::assertEquals(
                $expected,
                $result,
                sprintf("Failed: removeAccents('%s') should return '%s' but got '%s'", $input, $expected, $result),
            );
        }
    }

    /**
     * Test edge cases with unusual Unicode sequences for searchWords().
     */
    public function testSearchWordsUppercaseAccentMappingNegativeFlow(): void
    {
        // Test with empty and null inputs
        self::assertNull(StringManipulation::searchWords(null));
        self::assertEquals('', StringManipulation::searchWords(''));

        // Test with non-accented uppercase characters (should work as before)
        $nonAccentedTests = [
            'HELLO' => 'hello',
            'WORLD' => 'world',
            'ABC123' => 'abc123',
            'TEST!' => 'test!',
        ];

        foreach ($nonAccentedTests as $input => $expected) {
            $result = StringManipulation::searchWords($input);
            self::assertEquals($expected, $result);
        }

        // Test with mixed accented and non-accented uppercase
        $mixedTests = [
            'HELLO CAFÉ' => 'hello cafe',
            'ÀBCD EFGH' => 'abcd efgh',
            'TEST123 RÉSUMÉ' => 'test123 resume',
        ];

        foreach ($mixedTests as $input => $expected) {
            $result = StringManipulation::searchWords($input);
            self::assertEquals($expected, $result);
        }

        // Test with malformed or unusual Unicode
        $malformedTests = [
            "\xFF\xFEÀ" => "\xFF\xFEa", // Malformed with accented char
            "À\x00\x01" => "a\x00\x01", // Accented with control chars
        ];

        foreach ($malformedTests as $input => $expected) {
            $result = StringManipulation::searchWords($input);
            self::assertEquals($expected, $result);
        }
    }

    /**
     * Test edge cases with unusual Unicode sequences for removeAccents().
     */
    public function testRemoveAccentsUppercaseAccentMappingNegativeFlow(): void
    {
        // Test with empty input
        self::assertEquals('', StringManipulation::removeAccents(''));

        // Test with non-accented uppercase characters (should remain unchanged)
        $nonAccentedTests = [
            'HELLO' => 'HELLO',
            'WORLD' => 'WORLD',
            'ABC123' => 'ABC123',
            'TEST!' => 'TEST!',
        ];

        foreach ($nonAccentedTests as $input => $expected) {
            $result = StringManipulation::removeAccents($input);
            self::assertEquals($expected, $result);
        }

        // Test with malformed Unicode sequences
        $malformedTests = [
            "\xFF\xFE" => "\xFF\xFE",
            "\x80\x81\x82" => "\x80\x81\x82",
            "À\xFF\xFE" => "A\xFF\xFE",
        ];

        foreach ($malformedTests as $input => $expected) {
            $result = StringManipulation::removeAccents($input);
            self::assertEquals($expected, $result);
        }

        // Test with very long strings containing uppercase accents
        $longAccentString = str_repeat('ÀÆ', 1000); // À->A, Æ->AE (net +1 char per 2)
        $result = StringManipulation::removeAccents($longAccentString);
        self::assertStringNotContainsString('À', $result);
        self::assertStringContainsString('A', $result);
        // Length should be longer due to 'AE' replacement for 'Æ' (2000 chars -> 3000 chars)
        self::assertEquals(3000, strlen($result));
    }
}
