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
