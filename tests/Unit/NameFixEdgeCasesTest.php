<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Unit;

use MarjovanLier\StringManipulation\StringManipulation;
use PHPUnit\Framework\TestCase;

/**
 * Edge case test suite for nameFix function covering boundary conditions,
 * unusual but valid inputs, and corner cases that should still work correctly.
 *
 * @internal
 */
final class NameFixEdgeCasesTest extends TestCase
{
    /**
     * Test Unicode edge cases and international character handling.
     */
    public function testUnicodeEdgeCases(): void
    {
        $unicodeCases = [
            // Combining characters
            'café' => 'Cafe', // é as combining characters
            'naïve' => 'Naive',
            'résumé' => 'Resume',

            // Different Unicode normalisations (decomposed not handled by current removeAccents)
            'Müller' => 'Muller', // Precomposed ü
            "Mu\u0308ller" => "Mu\u0308ller", // Decomposed ü (u + diaeresis) - not converted

            // Emoji and symbols in names (unusual but possible)
            'john💕smith' => 'John💕smith',
            'test✓name' => 'Test✓name',
            'name★test' => 'Name★test',

            // Bidirectional text markers
            'name‎test' => 'Name‎test', // Left-to-right mark
            'name‏test' => 'Name‏test', // Right-to-left mark

            // Mathematical alphanumeric symbols
            '𝒋𝒐𝒉𝒏' => '𝒋𝒐𝒉𝒏', // Mathematical script
            '𝔰𝔪𝔦𝔱𝔥' => '𝔰𝔪𝔦𝔱𝔥', // Mathematical fraktur

            // Fullwidth characters (common in East Asian text)
            'ｊｏｈｎ' => 'ｊｏｈｎ',
            'ＳＭＩＴＨ' => 'ＳＭＩＴＨ',

            // Ancient and historical scripts
            'αλεξανδρος' => 'αλεξανδρος', // Greek
            'йван' => 'йван', // Cyrillic

            // Zero-width characters mixed in
            "jo\u200Bhn" => 'Jo\u200bhn', // Zero-width space
            "smi\u200Cth" => 'Smi\u200cth', // Zero-width non-joiner
        ];

        foreach ($unicodeCases as $input => $expected) {
            self::assertEquals($expected, StringManipulation::nameFix($input), sprintf("Failed for Unicode case: '%s'", $input));
        }
    }

    /**
     * Test uncommon but valid punctuation patterns.
     */
    public function testUncommonPunctuation(): void
    {
        $punctuationCases = [
            // Rare apostrophe variants (no automatic capitalisation without word boundary)
            "'brien" => "'brien", // Right single quotation mark (U+2019)
            'smith‛jones' => 'Smith‛jones', // Single high-reversed comma quotation mark

            // Compound punctuation
            'name...test' => 'Name Test', // Ellipsis in name
            'smith——jones' => 'Smith——jones', // Em dashes
            'test––name' => 'Test––name', // En dashes

            // Mixed quotation styles
            '"name"' => '"name"',
            '«name»' => '«name»', // French quotation marks
            '‹name›' => '‹name›', // Single angle quotation marks

            // Rare hyphens and dashes
            'smith‐jones' => 'Smith‐jones', // Hyphen (U+2010)
            'smith‑jones' => 'Smith‑jones', // Non-breaking hyphen
            'smith⸗jones' => 'Smith⸗jones', // Double oblique hyphen

            // Apostrophe-like characters
            'o`brien' => 'O`brien', // Grave accent as apostrophe
            'o´brien' => 'O´brien', // Acute accent as apostrophe
            'o′brien' => 'O′brien', // Prime symbol as apostrophe
        ];

        foreach ($punctuationCases as $input => $expected) {
            self::assertEquals($expected, StringManipulation::nameFix($input), sprintf("Failed for punctuation case: '%s'", $input));
        }
    }

    /**
     * Test prefix collision and ambiguity cases.
     */
    public function testPrefixCollisions(): void
    {
        $prefixCases = [
            // Mac vs Machine/Mackenzie disambiguation (current implementation triggers Mac for all)
            'machine' => 'MacHine', // Current implementation triggers Mac prefix
            'macaw' => 'MacAw', // Current implementation triggers Mac prefix
            'mace' => 'MacE', // Current implementation triggers Mac prefix
            'mackenzie' => 'MacKenzie', // Should trigger Mac prefix
            'macadamia' => 'MacAdamia', // Should trigger Mac prefix

            // Mc vs McDonald disambiguation
            'mcdonalds' => 'McDonalds', // Should trigger Mc prefix
            'mcdonnell' => 'McDonnell', // Should trigger Mc prefix
            'mccarthy' => 'McCarthy', // Should trigger Mc prefix

            // Van vs other words starting with Van
            'vancouver' => 'Vancouver', // Should NOT trigger van prefix (single word)
            'vanilla' => 'Vanilla', // Should NOT trigger van prefix
            'vandal' => 'Vandal', // Should NOT trigger van prefix
            'van morrison' => 'van Morrison', // Should trigger van prefix (two words)
            'van halen' => 'van Halen', // Should trigger van prefix

            // De vs other words
            'design' => 'Design', // Should NOT trigger de prefix
            'development' => 'Development', // Should NOT trigger de prefix
            'de facto' => 'de Facto', // Should trigger de prefix (two words)
            'de niro' => 'de Niro', // Should trigger de prefix

            // Multiple prefix words in sequence
            'van de van der berg' => 'van de van der Berg',
            'de la de los santos' => 'de la de Los Santos',
        ];

        foreach ($prefixCases as $input => $expected) {
            self::assertEquals($expected, StringManipulation::nameFix($input), sprintf("Failed for prefix case: '%s'", $input));
        }
    }

    /**
     * Test boundary conditions for prefix detection.
     */
    public function testPrefixBoundaries(): void
    {
        $boundaryCases = [
            // Exact minimum lengths
            'mac a' => 'Mac A', // Minimum Mac + single char
            'mc a' => 'Mc A', // Minimum Mc + single char
            'van a' => 'van A', // Minimum van + single char
            'de a' => 'de A', // Minimum de + single char

            // Prefix at end of string (single words, so van/de not lowercased)
            'name mac' => 'Name Mac', // Mac at end, no following name
            'name mc' => 'Name Mc', // Mc at end
            'name van' => 'Name van', // van at end - lowercased by prefix rule
            'name de' => 'Name de', // de at end - lowercased by prefix rule

            // Prefix with numbers
            'mac123' => 'Mac123', // Mac followed by numbers
            'mc456' => 'Mc456', // Mc followed by numbers
            'van789' => 'Van789', // van followed by numbers (single word)
            'van 789' => 'van 789', // van followed by numbers (two parts)

            // Case sensitivity boundaries
            'MAC donald' => 'Mac Donald', // ALL CAPS prefix
            'MC donald' => 'Mc Donald',
            'VAN der saar' => 'van der Saar',
            'DE la hoya' => 'de la Hoya',

            // Prefix embedded in larger words
            'macaroni' => 'MacAroni', // Current implementation triggers Mac
            'vacuum' => 'Vacuum', // Contains 'c' after 'a', should not trigger
            'rude' => 'Rude', // Contains 'de', should not trigger
        ];

        foreach ($boundaryCases as $input => $expected) {
            self::assertEquals($expected, StringManipulation::nameFix($input), sprintf("Failed for boundary case: '%s'", $input));
        }
    }

    /**
     * Test complex accent and diacritic combinations.
     */
    public function testComplexDiacritics(): void
    {
        $diacriticCases = [
            // Multiple diacritics on single character
            'josé maría' => 'Jose Maria',
            'noël' => 'Noel',
            'björk' => 'Bjork',
            'façade' => 'Facade',

            // Less common diacritics
            'antonín' => 'Antonin', // Czech
            'françois' => 'Francois', // French
            'østberg' => 'Ostberg', // Norwegian/Danish
            'škoda' => 'Skoda', // Czech/Slovak
            'žiga' => 'Ziga', // Slovenian

            // Diacritics in prefixes
            'dé la cruz' => 'de la Cruz',
            'ván der waals' => 'van der Waals',
            'mác donald' => 'Mac Donald',

            // Mixed scripts with diacritics
            'josé-müller' => 'Jose-Muller',
            'andré-björn' => 'Andre-Bjorn',
            'café-naïve' => 'Cafe-Naive',

            // Diacritics that change meaning
            'resume' => 'Resume', // vs résumé
            'expose' => 'Expose', // vs exposé
            'naive' => 'Naive', // vs naïve
        ];

        foreach ($diacriticCases as $input => $expected) {
            self::assertEquals($expected, StringManipulation::nameFix($input), sprintf("Failed for diacritic case: '%s'", $input));
        }
    }

    /**
     * Test unusual spacing and formatting edge cases.
     */
    public function testUnusualSpacing(): void
    {
        $spacingCases = [
            // Multiple space types (non-breaking space not converted by current implementation)
            "name\u00A0test" => "Name\u00a0test", // Non-breaking space preserved
            "name\u2002test" => 'Name\u2002test', // En space
            "name\u2003test" => 'Name\u2003test', // Em space
            "name\u2009test" => 'Name\u2009test', // Thin space
            "name\u200Atest" => 'Name\u200atest', // Hair space

            // Ideographic spaces
            "name\u3000test" => 'Name\u3000test', // Ideographic space (CJK)

            // Mixed spacing in prefixes
            "van\u00A0der\u00A0saar" => 'van\u00a0der\u00a0saar',
            "de\u2002la\u2002hoya" => 'de\u2002la\u2002hoya',

            // Extreme spacing scenarios
            'a' . str_repeat(' ', 100) . 'b' => 'A B',
            'van' . str_repeat(' ', 50) . 'der' . str_repeat(' ', 50) . 'saar' => 'van der Saar',

            // Tabs mixed with spaces
            "van\t der \tsaar" => "van\t der \tSaar",
            "mac\t\tdonald" => "Mac\t\tDonald",

            // Vertical spacing
            "name\vtest" => "Name\vTest", // Vertical tab
            "name\ftest" => "Name\fTest", // Form feed
        ];

        foreach ($spacingCases as $input => $expected) {
            self::assertEquals($expected, StringManipulation::nameFix($input), sprintf("Failed for spacing case: '%s'", $input));
        }
    }

    /**
     * Test unusual but valid name formats.
     */
    public function testUnusualValidFormats(): void
    {
        $formatCases = [
            // Names with periods (dots become spaces)
            'j.r.r. tolkien' => 'J R R Tolkien',
            'e.e. cummings' => 'E E Cummings',
            'f.d.r. roosevelt' => 'F D R Roosevelt',

            // Names with numbers
            'john smith ii' => 'John Smith Ii',
            'mary jones 3rd' => 'Mary Jones 3rd',
            'robert brown iv' => 'Robert Brown Iv',

            // Names with mathematical symbols
            'x æ a-xii' => 'X Ae A-Xii', // Elon Musk's child
            'pilot inspektor' => 'Pilot Inspektor',
            'audio science' => 'Audio Science',

            // Corporate/brand-inspired names
            'mclovin' => 'McLovin',
            'lemonjello' => 'Lemonjello',
            'orangejello' => 'Orangejello',

            // Names from different naming traditions
            'running bear' => 'Running Bear', // Native American style
            'sitting bull' => 'Sitting Bull',
            'red cloud' => 'Red Cloud',

            // Descriptive surnames
            'armstrong' => 'Armstrong',
            'strongbow' => 'Strongbow',
            'goldsmith' => 'Goldsmith',
            'blackwood' => 'Blackwood',

            // Place-based surnames
            'newcastle' => 'Newcastle',
            'manchester' => 'Manchester',
            'edinburgh' => 'Edinburgh',
            'cambridge' => 'Cambridge',
        ];

        foreach ($formatCases as $input => $expected) {
            self::assertEquals($expected, StringManipulation::nameFix($input), sprintf("Failed for format case: '%s'", $input));
        }
    }

    /**
     * Test interaction between multiple edge conditions.
     */
    public function testMultipleEdgeConditions(): void
    {
        $multiEdgeCases = [
            // Unicode + prefix + punctuation
            'mác dónald-o\'brien' => "Mac Donald-O'brien",
            'vân dẻr śãar' => 'van Dẻr Saar', // Some accent characters not fully converted

            // Long prefix chains with unicode
            'vañ dér vån dëns çafé' => 'van der van Dens Cafe',

            // Numbers + prefixes + symbols
            'mac123-donald&sons' => 'Mac123-Donald&sons',
            'van2der3saar' => 'Van2der3saar',

            // Multiple apostrophes + prefixes
            "o'brien-d'arcy-macdonald" => "O'brien-D'arcy-MacDonald",
            "d'angelo-o'sullivan-van der berg" => "D'angelo-O'sullivan-van der Berg",

            // Extreme mixed case + prefixes
            'mAcDoNaLd-vAn DeR sAaR' => 'MacDonald-van der Saar',
            "O'bRiEn-De La HoYa" => "O'brien-de la Hoya",

            // Unicode spaces + prefixes + case
            "VAN\u00A0DER\u00A0SAAR-MCDONALD" => 'van\u00a0der\u00a0saar-McDonald',
            "DE\u2002LA\u2002HOYA-O'BRIEN" => 'de\u2002la\u2002hoya-O\'brien',

            // Performance edge with multiple conditions
            str_repeat('mác-', 100) . 'dónald' => str_repeat('Mac-', 100) . 'Donald',
        ];

        foreach ($multiEdgeCases as $input => $expected) {
            self::assertEquals($expected, StringManipulation::nameFix($input), sprintf("Failed for multi-edge case: '%s'", $input));
        }
    }

    /**
     * Test historically problematic patterns.
     */
    public function testHistoricalProblems(): void
    {
        $historicalCases = [
            // Common real-world issues
            "mcdonald's restaurant" => "McDonald's Restaurant", // Possessive handling (no extra capitalisation)
            "o'reilly publishing" => "O'reilly Publishing", // Company names
            'van der waals equation' => 'van der Waals Equation', // Scientific terms

            // Mixed language business names
            'café rené' => 'Cafe Rene',
            'chez françois' => 'Chez Francois',
            'casa de la cultura' => 'Casa de la Cultura',

            // Academic and professional titles
            'prof. van der berg' => 'Prof van der Berg',
            "dr. o'malley" => "Dr O'malley",
            'sir macdonald' => 'Sir MacDonald',

            // Geographic names
            'new amsterdam' => 'New Amsterdam',
            'san francisco' => 'San Francisco',
            'las vegas' => 'Las Vegas',
            'los angeles' => 'Los Angeles',

            // Names that often cause confusion
            'macbeth' => 'MacBeth', // Shakespeare character
            'machete' => 'MacHete', // Current implementation triggers Mac
            'vacuum' => 'Vacuum', // Contains 'ac' but not Mac
            'mcintosh' => 'McIntosh', // Apple variety
        ];

        foreach ($historicalCases as $input => $expected) {
            self::assertEquals($expected, StringManipulation::nameFix($input), sprintf("Failed for historical case: '%s'", $input));
        }
    }

    /**
     * Test performance with edge case combinations.
     */
    public function testPerformanceWithEdgeCases(): void
    {
        // Generate complex test cases
        $complexCases = [
            // Deep nesting with unicode
            str_repeat('van der ', 50) . 'ñáme',
            str_repeat('mac', 100) . 'dónald',
            str_repeat("o'brien-", 20) . 'smith',

            // Mixed everything
            'VAN DER ' . str_repeat('CAFÉ-', 30) . 'MÜLLER-O\'BRIEN',
        ];

        foreach ($complexCases as $complexCase) {
            $startTime = microtime(true);
            $result = StringManipulation::nameFix($complexCase);
            $duration = microtime(true) - $startTime;

            // Should complete quickly even for complex edge cases
            self::assertLessThan(0.5, $duration, "Edge case processing too slow for: " . substr($complexCase, 0, 50) . '...');
            self::assertIsString($result, 'Result should be string');
            self::assertGreaterThan(0, strlen($result), 'Result should not be empty for non-empty input');
        }
    }
}
