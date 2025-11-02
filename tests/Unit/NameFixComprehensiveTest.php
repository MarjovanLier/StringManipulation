<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Unit;

use MarjovanLier\StringManipulation\StringManipulation;
use PHPUnit\Framework\TestCase;

/**
 * Happy path test suite for nameFix function covering standard international names,
 * common prefixes, and typical name formatting scenarios that should work correctly.
 * This class focuses on the positive/happy flow scenarios where inputs are
 * well-formed and expected to produce standard formatted output.
 *
 * @internal
 */
final class NameFixComprehensiveTest extends TestCase
{
    /**
     * Test Scottish and Irish name prefixes (Mac/Mc variations).
     */
    public function testScottishIrishPrefixes(): void
    {
        $names = [
            // Mac prefixes
            'macarthur' => 'MacArthur',
            'MACARTHUR' => 'MacArthur',
            'macbeth' => 'MacBeth',
            'maccallum' => 'MacCallum',
            'macdonald' => 'MacDonald',
            'macgregor' => 'MacGregor',
            'machale' => 'MacHale',
            'macintosh' => 'MacIntosh',
            'macjones' => 'MacJones',
            'mackenzie' => 'MacKenzie',
            'maclean' => 'MacLean',
            'macmillan' => 'MacMillan',
            'macneil' => 'MacNeil',
            'macpherson' => 'MacPherson',
            'macqueen' => 'MacQueen',
            'macrae' => 'MacRae',

            // Mc prefixes
            'mcdonald' => 'McDonald',
            'MCDONALD' => 'McDonald',
            'mcbride' => 'McBride',
            'mccallum' => 'McCallum',
            'mcdermott' => 'McDermott',
            'mcgregor' => 'McGregor',
            'mckenzie' => 'McKenzie',
            'mclean' => 'McLean',
            'mcmillan' => 'McMillan',
            'mcneil' => 'McNeil',
            'mcpherson' => 'McPherson',

            // Edge cases with spacing
            'mac donald' => 'Mac Donald',
            'mc donald' => 'Mc Donald',
            'mac arthur' => 'Mac Arthur',
            'mc arthur' => 'Mc Arthur',

            // Mixed case variations
            'MacArthur' => 'MacArthur',
            'McArthur' => 'McArthur',
            'mAcArThUr' => 'MacArthur',
            'mCaRtHuR' => 'McArthur',
        ];

        foreach ($names as $input => $expected) {
            self::assertEquals($expected, StringManipulation::nameFix($input), sprintf("Failed for input: '%s'", $input));
        }
    }

    /**
     * Test Dutch name prefixes and variations.
     */
    public function testDutchPrefixes(): void
    {
        $names = [
            // Van prefixes
            'van lier' => 'van Lier',
            'VAN LIER' => 'van Lier',
            'van der saar' => 'van der Saar',
            'VAN DER SAAR' => 'van der Saar',
            'van den berg' => 'van den Berg',
            'van de berg' => 'van de Berg',
            'van der berg' => 'van der Berg',
            'van den broek' => 'van den Broek',
            'van der waals' => 'van der Waals',

            // De prefixes
            'de jong' => 'de Jong',
            'DE JONG' => 'de Jong',
            'de wit' => 'de Wit',
            'de boer' => 'de Boer',
            'de vries' => 'de Vries',

            // Den/ter/te prefixes - only 'den' is in the prefix list
            'den uyl' => 'den Uyl',
            'ter haar' => 'Ter Haar', // 'ter' not in prefix list, capitalizes normally
            'te kamp' => 'Te Kamp', // 'te' not in prefix list, capitalizes normally

            // Combined prefixes
            'van der van der saar' => 'van der van der Saar',
            'de van der berg' => 'de van der Berg',
        ];

        foreach ($names as $input => $expected) {
            self::assertEquals($expected, StringManipulation::nameFix($input), sprintf("Failed for input: '%s'", $input));
        }
    }

    /**
     * Test Spanish, Italian, and Portuguese name prefixes.
     */
    public function testIbericPrefixes(): void
    {
        $names = [
            // Spanish de/del - only 'de' is in the prefix list
            'de la hoya' => 'de la Hoya',
            'DE LA HOYA' => 'de la Hoya',
            'de la cruz' => 'de la Cruz',
            'del rio' => 'Del Rio', // 'del' not in prefix list, capitalizes normally
            'de los santos' => 'de Los Santos',
            'de las torres' => 'de Las Torres',

            // Italian di/da/della - none in prefix list, capitalize normally
            'di caprio' => 'Di Caprio',
            'da silva' => 'Da Silva',
            'della croce' => 'Della Croce',
            'degli antoni' => 'Degli Antoni',

            // Portuguese dos/das - none in prefix list, capitalize normally
            'dos santos' => 'Dos Santos',
            'das neves' => 'Das Neves',
        ];

        foreach ($names as $input => $expected) {
            self::assertEquals($expected, StringManipulation::nameFix($input), sprintf("Failed for input: '%s'", $input));
        }
    }

    /**
     * Test French name prefixes.
     */
    public function testFrenchPrefixes(): void
    {
        $names = [
            'le blanc' => 'le Blanc',
            'LE BLANC' => 'le Blanc',
            'la fontaine' => 'la Fontaine',
            'les bernard' => 'Les Bernard', // 'les' not in handled prefix list, capitalizes normally
            'du pont' => 'du Pont',
            'des jardins' => 'des Jardins',
            'de la fontaine' => 'de la Fontaine',
        ];

        foreach ($names as $input => $expected) {
            self::assertEquals($expected, StringManipulation::nameFix($input), sprintf("Failed for input: '%s'", $input));
        }
    }

    /**
     * Test German name prefixes.
     */
    public function testGermanPrefixes(): void
    {
        $names = [
            'von neumann' => 'von Neumann', // 'von' is in prefix list
            'VON NEUMANN' => 'von Neumann',
            'zu guttenberg' => 'Zu Guttenberg', // 'zu' not in prefix list, capitalizes normally
            'von und zu liechtenstein' => 'von Und Zu Liechtenstein', // only 'von' is handled
        ];

        foreach ($names as $input => $expected) {
            self::assertEquals($expected, StringManipulation::nameFix($input), sprintf("Failed for input: '%s'", $input));
        }
    }

    /**
     * Test names with apostrophes in various positions.
     */
    public function testApostropheNames(): void
    {
        $names = [
            // Irish O' names
            "o'brien" => "O'brien",
            "O'BRIEN" => "O'brien",
            "o'connor" => "O'connor",
            "o'donnell" => "O'donnell",
            "o'hara" => "O'hara",
            "o'malley" => "O'malley",
            "o'neill" => "O'neill",
            "o'reilly" => "O'reilly",
            "o'sullivan" => "O'sullivan",

            // French D' names
            "d'artagnan" => "D'artagnan",
            "d'angelo" => "D'angelo",

            // Italian names with apostrophes
            "dell'arte" => "Dell'arte",

            // Multiple apostrophes
            "o'brien-o'connor" => "O'brien-O'connor",

            // Apostrophes in middle
            "jean-d'arc" => "Jean-D'arc",
        ];

        foreach ($names as $input => $expected) {
            self::assertEquals($expected, StringManipulation::nameFix($input), sprintf("Failed for input: '%s'", $input));
        }
    }

    /**
     * Test hyphenated and compound names.
     */
    public function testHyphenatedNames(): void
    {
        $names = [
            // Double-barreled surnames
            'smith-jones' => 'Smith-Jones',
            'SMITH-JONES' => 'Smith-Jones',
            'taylor-brown' => 'Taylor-Brown',
            'wilson-davis' => 'Wilson-Davis',

            // Triple names
            'smith-jones-williams' => 'Smith-Jones-Williams',
            'van der saar-mcdonald' => 'van der Saar-McDonald',

            // Mac/Mc in compound names
            'macdonald-smith' => 'MacDonald-Smith',
            'smith-mcdonald' => 'Smith-McDonald',
            'macarthur-jones-brown' => 'MacArthur-Jones-Brown',

            // Prefixes in compound names
            'van lier-de jong' => 'van Lier-de Jong',
            'de la hoya-santos' => 'de la Hoya-Santos',

            // Multiple hyphens and complex combinations
            "van der saar-mcdonald-o'brien" => "van der Saar-McDonald-O'brien",
        ];

        foreach ($names as $input => $expected) {
            self::assertEquals($expected, StringManipulation::nameFix($input), sprintf("Failed for input: '%s'", $input));
        }
    }

    /**
     * Test names with accented characters and international scripts.
     */
    public function testAccentedNames(): void
    {
        $names = [
            // Spanish accents
            'garcía' => 'Garcia',
            'GARCÍA' => 'Garcia',
            'rodríguez' => 'Rodriguez',
            'martínez' => 'Martinez',
            'lópez' => 'Lopez',
            'pérez' => 'Perez',
            'sánchez' => 'Sanchez',
            'gómez' => 'Gomez',

            // French accents
            'françois' => 'Francois',
            'andré' => 'Andre',
            'rené' => 'Rene',
            'josé' => 'Jose',

            // German umlauts
            'müller' => 'Muller',
            'MÜLLER' => 'Muller',
            'björn' => 'Bjorn',

            // Mixed accents in compound names
            'garcía-müller' => 'Garcia-Muller',
            'françois-rodriguez' => 'Francois-Rodriguez',

            // Prefixes with accents
            'de la tòrré' => 'de la Torre',
            'josé maría' => 'Jose Maria',
        ];

        foreach ($names as $input => $expected) {
            self::assertEquals($expected, StringManipulation::nameFix($input), sprintf("Failed for input: '%s'", $input));
        }
    }



    /**
     * Test international names from various cultures.
     */
    public function testInternationalNames(): void
    {
        $names = [
            // Eastern European
            'nowak' => 'Nowak',
            'kowalski' => 'Kowalski',
            'petrova' => 'Petrova',

            // Asian names (romanized)
            'zhang' => 'Zhang',
            'wang' => 'Wang',
            'li' => 'Li',
            'suzuki' => 'Suzuki',
            'tanaka' => 'Tanaka',

            // Arabic names (romanized) - bin capitalizes like other words
            'al-rashid' => 'Al-Rashid',
            'bin hamad' => 'Bin Hamad',
            'abdul rahman' => 'Abdul Rahman',

            // Nordic names
            'andersen' => 'Andersen',
            'larsson' => 'Larsson',
            'eriksson' => 'Eriksson',

            // Mixed cultural compound names
            'smith-tanaka' => 'Smith-Tanaka',
            'garcia-andersen' => 'Garcia-Andersen',
        ];

        foreach ($names as $input => $expected) {
            self::assertEquals($expected, StringManipulation::nameFix($input), sprintf("Failed for input: '%s'", $input));
        }
    }


    /**
     * Test null input handling.
     */
    public function testNullInput(): void
    {
        self::assertNull(StringManipulation::nameFix(null));
    }

}
