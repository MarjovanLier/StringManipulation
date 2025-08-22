<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Unit;

use MarjovanLier\StringManipulation\StringManipulation;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \MarjovanLier\StringManipulation\StringManipulation::removeAccents
 */
final class RemoveAccentsTest extends TestCase
{
    /**
     * Test the removeAccents function.
     */
    public function testRemoveAccentsFunction(): void
    {
        self::assertEquals('aeiou', StringManipulation::removeAccents('áéíóú'));
        self::assertEquals('AEIOU', StringManipulation::removeAccents('ÁÉÍÓÚ'));
        self::assertEquals('AeOeUe', StringManipulation::removeAccents('ÄëÖëÜë'));
        self::assertEquals('Nino', StringManipulation::removeAccents('Niño'));
        self::assertEquals("cote d'Ivoire", StringManipulation::removeAccents('côte d’Ivoire'));
    }


    /**
     * Negative tests for the removeAccents function.
     */
    public function testRemoveAccentsFunctionNegative(): void
    {
        // Passing empty string
        self::assertEquals('', StringManipulation::removeAccents(''));

        // Passing numbers
        self::assertEquals('12345', StringManipulation::removeAccents('12345'));

        // Passing special characters
        self::assertEquals('!@#$%', StringManipulation::removeAccents('!@#$%'));

        // Passing a string without accents
        self::assertEquals('abcdef', StringManipulation::removeAccents('abcdef'));
    }


    public function testRemoveAccents(): void
    {
        $string = 'ÀÁÂÃÄÅ';
        $result = StringManipulation::removeAccents($string);
        self::assertEquals('AAAAAA', $result);
    }


    public function testRemoveAccentsWithNoAccents(): void
    {
        $string = 'ABCDEF';
        $result = StringManipulation::removeAccents($string);
        self::assertEquals('ABCDEF', $result);
    }


    /**
     * Test comprehensive Unicode accent removal.
     */
    public function testRemoveAccentsComprehensive(): void
    {
        // Latin Extended-A accents
        self::assertEquals('AaeE', StringManipulation::removeAccents('ĀāėĖ'));
        self::assertEquals('IiOoUu', StringManipulation::removeAccents('ĪīŌōŪū'));

        // Latin Extended-B accents
        self::assertEquals('SsZz', StringManipulation::removeAccents('ŠšŽž'));
        self::assertEquals('CcDd', StringManipulation::removeAccents('ČčĎď'));

        // French accents comprehensive
        self::assertEquals('eEeEaAcCuUiI', StringManipulation::removeAccents('éÉèÈàÀçÇùÙîÎ'));
        self::assertEquals('oOaAeE', StringManipulation::removeAccents('ôÔâÂêÊ'));

        // German umlauts
        self::assertEquals('AOU', StringManipulation::removeAccents('ÄÖÜ'));
        self::assertEquals('aou', StringManipulation::removeAccents('äöü'));
        self::assertEquals('s', StringManipulation::removeAccents('ß'));

        // Spanish characters
        self::assertEquals('Nn', StringManipulation::removeAccents('Ññ'));
        self::assertEquals('¡¿', StringManipulation::removeAccents('¡¿'));

        // Portuguese accents
        self::assertEquals('aoAO', StringManipulation::removeAccents('ãõÃÕ'));

        // Italian accents
        self::assertEquals('eE', StringManipulation::removeAccents('èÈ'));

        // Mixed language text
        self::assertEquals(
            'Cafe Restauraǹt Menu',
            StringManipulation::removeAccents('Café Restauraǹt Menü'),
        );

        // Complex accented words
        self::assertEquals(
            'Constantinople',
            StringManipulation::removeAccents('Constantinoplë'),
        );

        self::assertEquals(
            'Francais',
            StringManipulation::removeAccents('Français'),
        );

        self::assertEquals(
            'Munchen',
            StringManipulation::removeAccents('München'),
        );
    }


    /**
     * Test accent removal with numbers and mixed content.
     */
    public function testRemoveAccentsWithMixedContent(): void
    {
        // Text with numbers
        self::assertEquals(
            'Address 123 Rue de la Paix',
            StringManipulation::removeAccents('Addréss 123 Ruë de là Pàix'),
        );

        // Text with special characters
        self::assertEquals(
            'Email: user@domain com',
            StringManipulation::removeAccents('Emaíl: ùser@domaín.cóm'),
        );

        // Mixed case with symbols
        self::assertEquals(
            'Price: $19 99 (15% off)',
            StringManipulation::removeAccents('Pricé: $19.99 (15% óff)'),
        );
    }


    /**
     * Test accent removal performance and edge cases.
     */
    public function testRemoveAccentsPerformanceEdgeCases(): void
    {
        // Long string with many accents
        $longAccentedString = str_repeat('áéíóúàèìòùâêîôûäëïöü', 100);
        $expectedLongString = str_repeat('aeiouaeiouaeiouaeiou', 100);
        self::assertEquals($expectedLongString, StringManipulation::removeAccents($longAccentedString));

        // Single character tests
        self::assertEquals('a', StringManipulation::removeAccents('á'));
        self::assertEquals('A', StringManipulation::removeAccents('À'));

        // Repetitive accent patterns
        self::assertEquals('aaaa', StringManipulation::removeAccents('áàâä'));
        self::assertEquals('eeee', StringManipulation::removeAccents('éèêë'));
        self::assertEquals('iiii', StringManipulation::removeAccents('íìîï'));
        self::assertEquals('oooo', StringManipulation::removeAccents('óòôö'));
        self::assertEquals('uuuu', StringManipulation::removeAccents('úùûü'));
    }


    /**
     * Test accent removal with various text patterns.
     */
    public function testRemoveAccentsTextPatterns(): void
    {
        // Words with multiple accent types
        self::assertEquals(
            'communication',
            StringManipulation::removeAccents('cómmunìcâtion'),
        );

        // Sentences with mixed accents
        self::assertEquals(
            'The quick brown fox jumps over the lazy dog ',
            StringManipulation::removeAccents('Thé qüick bröwn fóx jümps ovér thè läzy dög.'),
        );

        // All capitals with accents
        self::assertEquals(
            'MAXIMUM EFFICIENCY',
            StringManipulation::removeAccents('MÀXIMÜM ÈFFICIÉNCY'),
        );

        // Text with quotes and accents
        self::assertEquals(
            '"Hello", said the visitor ',
            StringManipulation::removeAccents('"Hëllo", saíd thé visítör.'),
        );
    }


    /**
     * Test negative flow scenarios for removeAccents function.
     */
    public function testRemoveAccentsNegativeFlow(): void
    {
        // Malformed Unicode sequences
        $malformedUtf8 = "\xFF\xFE\xFD";
        $result = StringManipulation::removeAccents($malformedUtf8);
        self::assertEquals($malformedUtf8, $result);

        // Invalid character encodings (non-UTF-8)
        $invalidEncoding = "\x80\x81\x82\x83";
        $result = StringManipulation::removeAccents($invalidEncoding);
        self::assertEquals($invalidEncoding, $result);

        // Binary data mixed with text
        $binaryMixed = "Hello\x00\x01\x02World";
        $result = StringManipulation::removeAccents($binaryMixed);
        self::assertEquals($binaryMixed, $result);

        // Very long string with performance implications
        $veryLongString = str_repeat('áéíóúàèìòùâêîôûäëïöü', 10000);
        $startTime = microtime(true);
        $result = StringManipulation::removeAccents($veryLongString);
        $duration = microtime(true) - $startTime;
        self::assertLessThan(2.0, $duration, 'RemoveAccents should handle large strings efficiently');
        self::assertStringNotContainsString('á', $result);

        // Unicode normalisation edge cases
        $denormalised = "e\u{0301}"; // e + combining acute accent
        $result = StringManipulation::removeAccents($denormalised);
        // This should handle combining characters appropriately
        self::assertNotEmpty($result);

        // High Unicode ranges not typically handled
        $highUnicode = "\u{1F600}\u{1F601}"; // Emoji
        $result = StringManipulation::removeAccents($highUnicode);
        self::assertEquals($highUnicode, $result);

        // Mixed script text (Cyrillic, Greek, etc.)
        $cyrillicText = 'Привет мир'; // Russian
        $result = StringManipulation::removeAccents($cyrillicText);
        self::assertEquals($cyrillicText, $result);

        $greekText = 'Γεια σας'; // Greek
        $result = StringManipulation::removeAccents($greekText);
        self::assertEquals($greekText, $result);

        // Control characters mixed with accented text
        $controlMixed = "\x01\x02á\x03é\x04";
        $result = StringManipulation::removeAccents($controlMixed);
        self::assertEquals("\x01\x02a\x03e\x04", $result);

        // Null bytes in string
        $nullByteString = "caf\0é";
        $result = StringManipulation::removeAccents($nullByteString);
        self::assertEquals("caf\0e", $result);
    }


    /**
     * Test edge cases and boundary conditions for removeAccents.
     */
    public function testRemoveAccentsEdgeCases(): void
    {
        // Single accented character
        self::assertEquals('a', StringManipulation::removeAccents('á'));
        self::assertEquals('A', StringManipulation::removeAccents('À'));

        // String with only accents
        $onlyAccents = 'áéíóúàèìòùâêîôûäëïöü';
        $result = StringManipulation::removeAccents($onlyAccents);
        self::assertStringNotContainsString('á', $result);
        self::assertStringNotContainsString('é', $result);

        // Unicode boundary characters
        $boundary = "\u{00FF}\u{0100}"; // End of Latin-1, start of Latin Extended-A
        $result = StringManipulation::removeAccents($boundary);
        self::assertNotEmpty($result);

        // Maximum string length scenarios
        $maxString = str_repeat('café', 50000);
        $startTime = microtime(true);
        $result = StringManipulation::removeAccents($maxString);
        $duration = microtime(true) - $startTime;
        self::assertLessThan(5.0, $duration, 'Very large strings should be processed efficiently');
        self::assertStringContainsString('cafe', $result);

        // Repeated accent patterns
        $repeatedPattern = str_repeat('éàé', 1000);
        $result = StringManipulation::removeAccents($repeatedPattern);
        self::assertEquals(str_repeat('eae', 1000), $result);

        // Mixed encoding attempts
        $convertedString = mb_convert_encoding('café', 'ISO-8859-1', 'UTF-8');
        $mixedAttempt = 'café' . ($convertedString !== false ? $convertedString : '');
        $result = StringManipulation::removeAccents($mixedAttempt);
        // Should handle gracefully - result will be a string
        self::assertNotEmpty($result);

        // Stress test with all possible accented characters
        $allAccents = '';
        for ($i = 192; $i <= 255; ++$i) {
            if ($i !== 215 && $i !== 247) { // Skip multiplication and division signs
                $converted = mb_convert_encoding('&#' . (string) $i . ';', 'UTF-8', 'HTML-ENTITIES');
                $allAccents .= ($converted !== false ? $converted : '');
            }
        }

        $result = StringManipulation::removeAccents($allAccents);
        self::assertNotEmpty($result);
    }
}
