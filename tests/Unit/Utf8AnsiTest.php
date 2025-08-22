<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Unit;

use MarjovanLier\StringManipulation\StringManipulation;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \MarjovanLier\StringManipulation\StringManipulation::utf8Ansi
 */
final class Utf8AnsiTest extends TestCase
{
    /**
     * @var array<string, string>
     */
    private const array UTF8_TO_ANSI_MAP = [
        '\u00c0' => 'À',
        '\u00c1' => 'Á',
        '\u00c2' => 'Â',
        '\u00c3' => 'Ã',
        '\u00c4' => 'Ä',
        '\u00c5' => 'Å',
        '\u00c6' => 'Æ',
        '\u00c7' => 'Ç',
        '\u00c8' => 'È',
        '\u00c9' => 'É',
        '\u00ca' => 'Ê',
        '\u00cb' => 'Ë',
        '\u00cc' => 'Ì',
        '\u00cd' => 'Í',
        '\u00ce' => 'Î',
        '\u00cf' => 'Ï',
        '\u00d1' => 'Ñ',
        '\u00d2' => 'Ò',
        '\u00d3' => 'Ó',
        '\u00d4' => 'Ô',
        '\u00d5' => 'Õ',
        '\u00d6' => 'Ö',
        '\u00d8' => 'Ø',
        '\u00d9' => 'Ù',
        '\u00da' => 'Ú',
        '\u00db' => 'Û',
        '\u00dc' => 'Ü',
        '\u00dd' => 'Ý',
        '\u00df' => 'ß',
        '\u00e0' => 'à',
        '\u00e1' => 'á',
        '\u00e2' => 'â',
        '\u00e3' => 'ã',
        '\u00e4' => 'ä',
        '\u00e5' => 'å',
        '\u00e6' => 'æ',
        '\u00e7' => 'ç',
        '\u00e8' => 'è',
        '\u00e9' => 'é',
        '\u00ea' => 'ê',
        '\u00eb' => 'ë',
        '\u00ec' => 'ì',
        '\u00ed' => 'í',
        '\u00ee' => 'î',
        '\u00ef' => 'ï',
        '\u00f0' => 'ð',
        '\u00f1' => 'ñ',
        '\u00f2' => 'ò',
        '\u00f3' => 'ó',
        '\u00f4' => 'ô',
        '\u00f5' => 'õ',
        '\u00f6' => 'ö',
        '\u00f8' => 'ø',
        '\u00f9' => 'ù',
        '\u00fa' => 'ú',
        '\u00fb' => 'û',
        '\u00fc' => 'ü',
        '\u00fd' => 'ý',
        '\u00ff' => 'ÿ',
    ];


    public function testUtf8Ansi(): void
    {
        // This represents the UTF-8 encoded character 'À'
        $string = '\u00c0';
        $result = StringManipulation::utf8Ansi($string);
        self::assertEquals('À', $result);
    }


    /**
     * Test the utf8Ansi function.
     */
    public function testUtf8AnsiFunction(): void
    {
        foreach (self::UTF8_TO_ANSI_MAP as $utf8 => $ansi) {
            self::assertEquals($ansi, StringManipulation::utf8Ansi($utf8));
        }

        // Test an empty string
        self::assertEquals('', StringManipulation::utf8Ansi(''));

        // Test null input
        self::assertEquals('', StringManipulation::utf8Ansi(null));
    }


    public function testUtf8AnsiWithInvalidCharacter(): void
    {
        // Invalid UTF-8 encoded character
        $string = '\uZZZZ';
        $result = StringManipulation::utf8Ansi($string);
        self::assertEquals($string, $result);
    }


    /**
     * Test UTF-8 to ANSI conversion with multiple characters.
     */
    public function testUtf8AnsiMultipleCharacters(): void
    {
        // Test string with multiple UTF-8 characters
        $utf8String = '\u00c0\u00e9\u00ef\u00f1\u00fc';
        $expectedAnsi = 'Àéïñü';
        self::assertEquals($expectedAnsi, StringManipulation::utf8Ansi($utf8String));

        // Test mixed content with normal ASCII and UTF-8
        $mixedString = 'Hello \u00c0\u00e9\u00ef\u00f1\u00fc World';
        $expectedMixed = 'Hello Àéïñü World';
        self::assertEquals($expectedMixed, StringManipulation::utf8Ansi($mixedString));

        // Test uppercase UTF-8 characters
        $uppercaseString = '\u00c0\u00c1\u00c2\u00c3\u00c4\u00c5';
        $expectedUppercase = 'ÀÁÂÃÄÅ';
        self::assertEquals($expectedUppercase, StringManipulation::utf8Ansi($uppercaseString));

        // Test lowercase UTF-8 characters
        $lowercaseString = '\u00e0\u00e1\u00e2\u00e3\u00e4\u00e5';
        $expectedLowercase = 'àáâãäå';
        self::assertEquals($expectedLowercase, StringManipulation::utf8Ansi($lowercaseString));
    }


    /**
     * Test UTF-8 to ANSI conversion with real-world scenarios.
     */
    public function testUtf8AnsiRealWorldScenarios(): void
    {
        // French text
        $frenchText = 'Caf\u00e9 r\u00e9staurant \u00e0 Paris';
        $expectedFrench = 'Café réstaurant à Paris';
        self::assertEquals($expectedFrench, StringManipulation::utf8Ansi($frenchText));

        // German text
        $germanText = 'M\u00fcnchen ist sch\u00f6n';
        $expectedGerman = 'München ist schön';
        self::assertEquals($expectedGerman, StringManipulation::utf8Ansi($germanText));

        // Spanish text
        $spanishText = 'Ma\u00f1ana ser\u00e1 otro d\u00eda';
        $expectedSpanish = 'Mañana será otro día';
        self::assertEquals($expectedSpanish, StringManipulation::utf8Ansi($spanishText));

        // Portuguese text
        $portugueseText = 'N\u00e3o h\u00e1 solu\u00e7\u00e3o';
        $expectedPortuguese = 'Não há solução';
        self::assertEquals($expectedPortuguese, StringManipulation::utf8Ansi($portugueseText));

        // Nordic text
        $nordicText = '\u00c5\u00e6\u00f8 \u00c6\u00d8\u00c5';
        $expectedNordic = 'Åæø ÆØÅ';
        self::assertEquals($expectedNordic, StringManipulation::utf8Ansi($nordicText));
    }


    /**
     * Test UTF-8 conversion with numbers and symbols.
     */
    public function testUtf8AnsiWithNumbersAndSymbols(): void
    {
        // Text with numbers
        $numberText = 'Address: 123 Rue de la Paix, 75001 Paris, France';
        self::assertEquals($numberText, StringManipulation::utf8Ansi($numberText));

        // Text with symbols
        $symbolText = 'Price: $29.99 (15% off)';
        self::assertEquals($symbolText, StringManipulation::utf8Ansi($symbolText));

        // Mixed UTF-8 with numbers and symbols
        $mixedText = 'Conna\u00eetre: \u20ac19.99 (r\u00e9duction 15%)';
        $expectedMixed = 'Connaître: \u20ac19.99 (réduction 15%)';
        self::assertEquals($expectedMixed, StringManipulation::utf8Ansi($mixedText));

        // Email with UTF-8
        $emailText = 'Contact: jos\u00e9@caf\u00e9.example.com';
        $expectedEmail = 'Contact: josé@café.example.com';
        self::assertEquals($expectedEmail, StringManipulation::utf8Ansi($emailText));
    }


    /**
     * Test UTF-8 conversion performance and edge cases.
     */
    public function testUtf8AnsiPerformanceEdgeCases(): void
    {
        // Long string with many UTF-8 characters
        $longString = str_repeat('\u00e9\u00e0\u00e7', 100);
        $expectedLong = str_repeat('éàç', 100);
        self::assertEquals($expectedLong, StringManipulation::utf8Ansi($longString));

        // String with only ASCII characters
        $asciiString = 'The quick brown fox jumps over the lazy dog.';
        self::assertEquals($asciiString, StringManipulation::utf8Ansi($asciiString));

        // Single UTF-8 character
        self::assertEquals('é', StringManipulation::utf8Ansi('\u00e9'));
        self::assertEquals('Ñ', StringManipulation::utf8Ansi('\u00d1'));

        // UTF-8 characters mixed with spaces
        $spacedString = '\u00e9 \u00e0 \u00e7';
        $expectedSpaced = 'é à ç';
        self::assertEquals($expectedSpaced, StringManipulation::utf8Ansi($spacedString));
    }


    /**
     * Test UTF-8 conversion with special cases.
     */
    public function testUtf8AnsiSpecialCases(): void
    {
        // String with line breaks
        $multilineString = 'Line 1\u00e9\nLine 2\u00e0\rLine 3\u00e7';
        $expectedMultiline = 'Line 1é\nLine 2à\rLine 3ç';
        self::assertEquals($expectedMultiline, StringManipulation::utf8Ansi($multilineString));

        // String with tabs
        $tabbedString = 'Column1\u00e9\tColumn2\u00e0\tColumn3\u00e7';
        $expectedTabbed = 'Column1é\tColumn2à\tColumn3ç';
        self::assertEquals($expectedTabbed, StringManipulation::utf8Ansi($tabbedString));

        // String with quotes
        $quotedString = '"Caf\u00e9" said the visitor';
        $expectedQuoted = '"Café" said the visitor';
        self::assertEquals($expectedQuoted, StringManipulation::utf8Ansi($quotedString));

        // String with parentheses and brackets
        $bracketsString = 'M\u00fcnchen (Germany) [Baviera]';
        $expectedBrackets = 'München (Germany) [Baviera]';
        self::assertEquals($expectedBrackets, StringManipulation::utf8Ansi($bracketsString));
    }


    /**
     * Test negative flow scenarios for utf8Ansi function.
     */
    public function testUtf8AnsiNegativeFlow(): void
    {
        // Malformed UTF-8 escape sequences
        $malformedSequences = [
            '\uGGGG',  // Invalid hex
            '\u12',    // Too short
            '\u123G',  // Mixed valid/invalid hex
            '\uZZZZ',  // All invalid hex
            '\u',      // Incomplete sequence
        ];

        foreach ($malformedSequences as $malformedSequence) {
            $result = StringManipulation::utf8Ansi($malformedSequence);
            self::assertEquals($malformedSequence, $result, 'Malformed sequence should be returned unchanged: ' . $malformedSequence);
        }

        // Mixed valid and invalid sequences
        $mixedString = 'Valid: \u00e9 Invalid: \uGGGG More valid: \u00e0';
        $expectedMixed = 'Valid: é Invalid: \uGGGG More valid: à';
        self::assertEquals($expectedMixed, StringManipulation::utf8Ansi($mixedString));

        // Binary data mixed with UTF-8 sequences
        $binaryMixed = "\x00\x01\u00e9\x02\x03";
        $expectedBinary = "\x00\x01é\x02\x03";
        self::assertEquals($expectedBinary, StringManipulation::utf8Ansi($binaryMixed));

        // Very long string with many sequences
        $longString = str_repeat('\u00e9\u00e0\u00e7', 10000);
        $startTime = microtime(true);
        $result = StringManipulation::utf8Ansi($longString);
        $duration = microtime(true) - $startTime;
        self::assertLessThan(1.0, $duration, 'Large string conversion should be efficient');
        self::assertStringContainsString('é', $result);

        // Incomplete sequences at string boundaries
        $incompleteStart = '\u00';
        self::assertEquals($incompleteStart, StringManipulation::utf8Ansi($incompleteStart));

        $incompleteEnd = 'text\u00';
        self::assertEquals($incompleteEnd, StringManipulation::utf8Ansi($incompleteEnd));

        // Case sensitivity in hex digits - uppercase not supported
        $upperCaseHex = '\u00C9';  // Not in mapping
        $mixedCaseHex = '\u00c9';  // In mapping
        self::assertEquals('\u00C9', StringManipulation::utf8Ansi($upperCaseHex));
        self::assertEquals('É', StringManipulation::utf8Ansi($mixedCaseHex));

        // Unicode sequences outside the mapping range
        $outsideRange = '\u1234';  // Not in the predefined mapping
        self::assertEquals($outsideRange, StringManipulation::utf8Ansi($outsideRange));

        // Control characters in sequences
        $controlInSequence = "Hello\x00\u00e9\x01World";
        $expectedControl = "Hello\x00é\x01World";
        self::assertEquals($expectedControl, StringManipulation::utf8Ansi($controlInSequence));

        // Null bytes and sequence handling
        $nullByteString = "café\0\u00e9";
        $expectedNull = "café\0é";
        self::assertEquals($expectedNull, StringManipulation::utf8Ansi($nullByteString));
    }


    /**
     * Test edge cases and boundary conditions for utf8Ansi.
     */
    public function testUtf8AnsiEdgeCases(): void
    {
        // All possible valid sequences from the mapping
        foreach (self::UTF8_TO_ANSI_MAP as $utf8 => $ansi) {
            $result = StringManipulation::utf8Ansi($utf8);
            self::assertEquals($ansi, $result, sprintf('UTF-8 sequence %s should convert to %s', $utf8, $ansi));
        }

        // Boundary sequences
        $lowerBoundary = '\u00c0';  // First in mapping
        $upperBoundary = '\u00ff';  // Last in mapping
        self::assertEquals('À', StringManipulation::utf8Ansi($lowerBoundary));
        self::assertEquals('ÿ', StringManipulation::utf8Ansi($upperBoundary));

        // Just outside boundaries
        $belowRange = '\u00bf';  // Just below range
        $aboveRange = '\u0100';  // Just above range
        self::assertEquals($belowRange, StringManipulation::utf8Ansi($belowRange));
        self::assertEquals($aboveRange, StringManipulation::utf8Ansi($aboveRange));

        // Massive string with all mappings
        $allMappings = implode('', array_keys(self::UTF8_TO_ANSI_MAP));
        $result = StringManipulation::utf8Ansi($allMappings);
        foreach (array_values(self::UTF8_TO_ANSI_MAP) as $ansi) {
            self::assertStringContainsString($ansi, $result);
        }

        // Performance test with repeated patterns
        $repeatedPattern = str_repeat('\u00e9\u00e0\u00e7', 5000);
        $startTime = microtime(true);
        $result = StringManipulation::utf8Ansi($repeatedPattern);
        $duration = microtime(true) - $startTime;
        self::assertLessThan(0.5, $duration, 'Repeated pattern conversion should be fast');
        self::assertEquals(str_repeat('éàç', 5000), $result);

        // Unicode normalisation edge cases
        $denormalised = "e\u0301";  // e + combining acute accent (not in escape form)
        $result = StringManipulation::utf8Ansi($denormalised);
        self::assertEquals($denormalised, $result);  // Should pass through unchanged

        // Maximum input length test
        $maxLength = str_repeat('a\u00e9', 100000);
        $startTime = microtime(true);
        $result = StringManipulation::utf8Ansi($maxLength);
        $duration = microtime(true) - $startTime;
        self::assertLessThan(2.0, $duration, 'Maximum length conversion should complete in reasonable time');
        self::assertStringContainsString('aé', $result);
    }
}
