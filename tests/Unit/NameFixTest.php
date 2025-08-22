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
            'o’reilly' => "O'reilly",
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
        ];

        foreach ($negativeTests as $input => $expected) {
            // For each negative test, we assert that the output of the nameFix function is equal to the input.
            self::assertEquals($expected, StringManipulation::nameFix($input));
        }

        // Test null input separately
        self::assertNull(StringManipulation::nameFix(null));
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


    /**
     * Test that Mac/Mc prefix handling requires both conditions to be true.
     * This targets the LogicalAnd mutations in the nameFix function.
     */
    public function testMacMcPrefixHandlingLogicalConditions(): void
    {
        // Test cases where 'mc' exists but is followed by a space (should NOT trigger fix)
        self::assertEquals('Mc Donald', StringManipulation::nameFix('mc donald'));
        self::assertEquals('Mc Lean', StringManipulation::nameFix('mc lean'));

        // Test cases where 'mac' exists but is followed by a space (should NOT trigger fix)
        self::assertEquals('Mac Donald', StringManipulation::nameFix('mac donald'));
        self::assertEquals('Mac Lean', StringManipulation::nameFix('mac lean'));

        // Test cases where 'mc' exists and is NOT followed by a space (SHOULD trigger fix)
        self::assertEquals('McDonald', StringManipulation::nameFix('mcdonald'));
        self::assertEquals('McLean', StringManipulation::nameFix('mclean'));

        // Test cases where 'mac' exists and is NOT followed by a space (SHOULD trigger fix)
        self::assertEquals('MacDonald', StringManipulation::nameFix('macdonald'));
        self::assertEquals('MacLean', StringManipulation::nameFix('maclean'));

        // Test cases where prefix doesn't exist at all
        self::assertEquals("O'brien", StringManipulation::nameFix("o'brien"));
        self::assertEquals('Johnson', StringManipulation::nameFix('johnson'));

        // Test complex cases with multiple occurrences
        self::assertEquals('MacDonald-McDonald', StringManipulation::nameFix('macdonald-mcdonald'));

        // Test edge case where both conditions in OR would be true but should only trigger once
        self::assertEquals('MacDonald Mac Smith', StringManipulation::nameFix('macdonald mac smith'));
    }
}
