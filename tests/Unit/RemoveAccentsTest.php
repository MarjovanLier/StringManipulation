<?php

declare(strict_types=1);
use MarjovanLier\StringManipulation\StringManipulation;

test('remove accents function', function (): void {
    expect(StringManipulation::removeAccents('áéíóú'))->toBe('aeiou');
    expect(StringManipulation::removeAccents('ÁÉÍÓÚ'))->toBe('AEIOU');
    expect(StringManipulation::removeAccents('ÄëÖëÜë'))->toBe('AeOeUe');
    expect(StringManipulation::removeAccents('Niño'))->toBe('Nino');
    expect(StringManipulation::removeAccents('côte d’Ivoire'))->toBe("cote d'Ivoire");
});
test('remove accents function negative', function (): void {
    // Passing empty string
    expect(StringManipulation::removeAccents(''))->toBe('');

    // Passing numbers
    expect(StringManipulation::removeAccents('12345'))->toBe('12345');

    // Passing special characters
    expect(StringManipulation::removeAccents('!@#$%'))->toBe('!@#$%');

    // Passing a string without accents
    expect(StringManipulation::removeAccents('abcdef'))->toBe('abcdef');
});
test('remove accents', function (): void {
    $string = 'ÀÁÂÃÄÅ';
    $result = StringManipulation::removeAccents($string);
    expect($result)->toBe('AAAAAA');
});
test('remove accents with no accents', function (): void {
    $string = 'ABCDEF';
    $result = StringManipulation::removeAccents($string);
    expect($result)->toBe('ABCDEF');
});
