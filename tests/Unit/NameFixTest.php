<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Unit;

use MarjovanLier\StringManipulation\StringManipulation;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \MarjovanLier\StringManipulation\StringManipulation::nameFix
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 *
 * This class is a test case for the nameFix function in the StringManipulation class.
 * It tests the function with a variety of inputs.
 */
final class NameFixTest extends TestCase
{
    /**
     * Test the nameFix function with a variety of inputs.
     *
     * This function tests the nameFix function with a variety of names, including basic and advanced name handling.
     * It also includes negative tests where the input is not a name.
     */
    public function testNameFixFunction(): void
    {
        // Basic and advanced name handling
        $names = [
            'o’reilly' => "O'reilly",
            'de la hoya' => 'de la Hoya',
            'de la tòrré' => 'de la Torre',
            'donald' => 'Donald',
            'johnson' => 'Johnson',
            'macarthur' => 'MacArthur',
            '   macdonald   ' => 'MacDonald',
            'macdonald-smith-jones' => 'MacDonald-Smith-Jones',
            'mACdonald-sMith-jOnes' => 'MacDonald-Smith-Jones',
            'MacDonald-sMith-jOnes' => 'MacDonald-Smith-Jones',
            'macIntosh' => 'MacIntosh',
            'mac jones' => 'Mac Jones',
            'macjones' => 'MacJones',
            'mcdonald' => 'McDonald',
            'MCDONALD' => 'McDonald',
            ' mcDonald ' => 'McDonald',
            'Mc donald' => 'Mc Donald',
            'mcdónald' => 'McDonald',
            'van der saar' => 'van der Saar',
            'VAN LIER' => 'van Lier',
            'À Macdonald È' => 'A MacDonald E',
        ];

        foreach ($names as $input => $expected) {
            // For each name, we assert that the output of the nameFix function is equal to the expected output.
            self::assertEquals($expected, StringManipulation::nameFix($input));
        }

        // Negative tests
        $negativeTests = [
            '!@#$%' => '!@#$%',
            null => null,
        ];

        foreach ($negativeTests as $input => $expected) {
            // For each negative test, we assert that the output of the nameFix function is equal to the input.
            self::assertEquals($expected, StringManipulation::nameFix($input));
        }
    }


    /**
     * Test the nameFix function with numeric input.
     *
     * This function tests the nameFix function with a numeric input.
     * The function is expected to return the input as is in this case.
     */
    public function testNameFixWithNumericInput(): void
    {
        self::assertEquals('12345', StringManipulation::nameFix('12345'));
    }
}
