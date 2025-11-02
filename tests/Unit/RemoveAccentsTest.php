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

test('remove accents handles double spaces', function (): void {
    // Line 232 & 233 mutations: RemoveArrayItem
    // Tests that the function correctly handles the '  ' => ' ' mapping
    // in the accentsReplacement array
    $stringWithDoubleSpaces = 'hello  world';
    $result = StringManipulation::removeAccents($stringWithDoubleSpaces);
    expect($result)->toBe('hello world');

    // Test multiple double spaces
    $stringWithMultipleDoubleSpaces = 'a  b  c  d';
    $result2 = StringManipulation::removeAccents($stringWithMultipleDoubleSpaces);
    expect($result2)->toBe('a b c d');
});

test('remove accents converts all A-based characters', function (): void {
    // Lines 194-199 mutations: RemoveArrayItem for Á, À, Â, Ä, Å, Ã
    expect(StringManipulation::removeAccents('Café Français'))->toBe('Cafe Francais');
    expect(StringManipulation::removeAccents('ÀÁÂÃÄÅ'))->toBe('AAAAAA');
    expect(StringManipulation::removeAccents('àáâãäå'))->toBe('aaaaaa');
});

test('remove accents converts all E-based characters', function (): void {
    // Lines 200-201 mutations: RemoveArrayItem for É, È, Ê, Ë
    expect(StringManipulation::removeAccents('ÉÈÊË'))->toBe('EEEE');
    expect(StringManipulation::removeAccents('éèêë'))->toBe('eeee');
});

test('remove accents converts all I-based characters', function (): void {
    // Lines 202-203 mutations: RemoveArrayItem for Í, Ì, Î, Ï
    expect(StringManipulation::removeAccents('ÍÌÎÏ'))->toBe('IIII');
    expect(StringManipulation::removeAccents('íìîï'))->toBe('iiii');
});

test('remove accents converts all O-based characters', function (): void {
    // Lines 204-206 mutations: RemoveArrayItem for Ó, Ò, Ô, Ö, Õ, Ø
    expect(StringManipulation::removeAccents('ÓÒÔÖÕØ'))->toBe('OOOOOO');
    expect(StringManipulation::removeAccents('óòôöõø'))->toBe('oooooo');
});

test('remove accents converts all U-based characters', function (): void {
    // Lines 207-208 mutations: RemoveArrayItem for Ú, Ù, Û, Ü
    expect(StringManipulation::removeAccents('ÚÙÛÜ'))->toBe('UUUU');
    expect(StringManipulation::removeAccents('úùûü'))->toBe('uuuu');
});

test('remove accents converts special Nordic and Germanic characters', function (): void {
    // Test basic umlaut conversion (Ä->A, not Ä->Ae)
    expect(StringManipulation::removeAccents('Ä'))->toBe('A');
    expect(StringManipulation::removeAccents('ä'))->toBe('a');
    expect(StringManipulation::removeAccents('Ö'))->toBe('O');
    expect(StringManipulation::removeAccents('ö'))->toBe('o');
    expect(StringManipulation::removeAccents('Ü'))->toBe('U');
    expect(StringManipulation::removeAccents('ü'))->toBe('u');
    expect(StringManipulation::removeAccents('ß'))->toBe('s');
});

test('remove accents converts C and N special characters', function (): void {
    // Lines 219-222 mutations: RemoveArrayItem for Ç, ç, Ñ, ñ
    expect(StringManipulation::removeAccents('Ç'))->toBe('C');
    expect(StringManipulation::removeAccents('ç'))->toBe('c');
    expect(StringManipulation::removeAccents('Ñ'))->toBe('N');
    expect(StringManipulation::removeAccents('ñ'))->toBe('n');
});

test('remove accents converts Y special characters', function (): void {
    // Lines 223-224 mutations: RemoveArrayItem for Ý, ý, ÿ
    expect(StringManipulation::removeAccents('Ý'))->toBe('Y');
    expect(StringManipulation::removeAccents('ý'))->toBe('y');
    expect(StringManipulation::removeAccents('ÿ'))->toBe('y');
});

test('remove accents converts special ligatures and symbols', function (): void {
    // Test ligatures: Æ, æ, Œ, œ (these DO convert to double letters)
    expect(StringManipulation::removeAccents('Æ'))->toBe('AE');
    expect(StringManipulation::removeAccents('æ'))->toBe('ae');
    expect(StringManipulation::removeAccents('Œ'))->toBe('OE');
    expect(StringManipulation::removeAccents('œ'))->toBe('oe');

    // Test Eth character (Ð->D, not ð->d; lowercase ð is not in the mapping)
    expect(StringManipulation::removeAccents('Ð'))->toBe('D');

    // Test curly apostrophe conversion
    expect(StringManipulation::removeAccents("côte d'Ivoire"))->toBe("cote d'Ivoire");
});
