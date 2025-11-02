<?php

declare(strict_types=1);

use MarjovanLier\StringManipulation\StringManipulation;

test('name fix function', function (): void {

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
        expect(StringManipulation::nameFix($input))->toBe($expected);
    }

    // Negative tests
    $negativeTests = [
        '!@#$%' => '!@#$%',
    ];

    foreach ($negativeTests as $input => $expected) {
        // For each negative test, we assert that the output of the nameFix function is equal to the input.
        expect(StringManipulation::nameFix($input))->toBe($expected);
    }

    // Test null input separately
    expect(StringManipulation::nameFix(null))->toBeNull();
});

test('name fix with numeric input', function (): void {

    expect(StringManipulation::nameFix('12345'))->toBe('12345');
});

test('name fix handles empty string correctly', function (): void {
    // Lines 136, 167 mutations: EmptyStringToNotEmpty
    // Test that preg_replace returning null is handled correctly
    expect(StringManipulation::nameFix(''))->toBe('');
});

test('name fix handles mc prefix edge cases', function (): void {
    // Line 144 mutation: BooleanAndToBooleanOr
    // Test that both conditions must be true: contains 'mc' AND regex matches 'mc' without space

    // Contains 'mc' but WITH space after it - should NOT trigger mcFix
    expect(StringManipulation::nameFix('mc donald'))->toBe('Mc Donald');

    // Contains 'mc' AND WITHOUT space after it - should trigger mcFix
    expect(StringManipulation::nameFix('mcdonald'))->toBe('McDonald');

    // Does NOT contain 'mc' at all - should not trigger mcFix
    expect(StringManipulation::nameFix('donald'))->toBe('Donald');
});

test('name fix handles mac prefix edge cases', function (): void {
    // Line 151 mutation: BooleanAndToBooleanOr
    // Test that both conditions must be true: contains 'mac' AND regex matches 'mac' without space

    // Contains 'mac' but WITH space after it - should NOT trigger macFix
    expect(StringManipulation::nameFix('mac donald'))->toBe('Mac Donald');

    // Contains 'mac' AND WITHOUT space after it - should trigger macFix
    expect(StringManipulation::nameFix('macdonald'))->toBe('MacDonald');

    // Does NOT contain 'mac' at all - should not trigger macFix
    expect(StringManipulation::nameFix('donald'))->toBe('Donald');
});

test('name fix handles name prefixes correctly', function (): void {
    // Line 162 mutation: DecrementInteger
    // Tests that the regex callback uses $matches[1] (captured group) not $matches[0] (full match)
    // The regex pattern captures the prefix in group 1, and we need to lowercase only that group

    // Test van prefix
    expect(StringManipulation::nameFix('VAN LIER'))->toBe('van Lier');
    expect(StringManipulation::nameFix('Van Lier'))->toBe('van Lier');

    // Test von prefix
    expect(StringManipulation::nameFix('VON SMITH'))->toBe('von Smith');

    // Test multiple prefixes
    expect(StringManipulation::nameFix('Van Der Saar'))->toBe('van der Saar');
    expect(StringManipulation::nameFix('De La Hoya'))->toBe('de la Hoya');
});
