<?php

declare(strict_types=1);
use MarjovanLier\StringManipulation\StringManipulation;

const LOVE_APPLE = 'I love apple.';
/**
 * @var array<int, string>
 */
const SEARCH = [
    'H',
    'e',
    'W',
];
/**
 * @var array<int, string>
 */
const REPLACE = [
    'h',
    'x',
    'w',
];
const SUBJECT = 'Hello World';
test('str replace with not found search', function (): void {
    $result = StringManipulation::strReplace('pineapple', 'banana', LOVE_APPLE);
    expect($result)->toBe(LOVE_APPLE);
});
test('str replace function', function (): void {
    // Basic test.
    expect(StringManipulation::strReplace('a', 'b', 'a'))->toBe('b');

    // Replace multiple characters.
    expect(StringManipulation::strReplace(['H', 'W'], ['h', 'w'], 'Helloworld'))->toBe('helloworld');

    // Replace multiple occurrences of a single character.
    expect(StringManipulation::strReplace('e', 'x', 'hello world'))->toBe('hxllo world');
    expect(StringManipulation::strReplace(SEARCH, REPLACE, SUBJECT))->toBe('hxllo world');
});
test('str replace', function (): void {
    $result = StringManipulation::strReplace('apple', 'banana', LOVE_APPLE);
    expect($result)->toBe('I love banana.');
});
test('single character optimization', function (): void {
    // Test with a single character (should use strtr optimization).
    $result1 = StringManipulation::strReplace('a', 'z', 'banana');
    expect($result1)->toBe('bznznz');

    // Test with a two-character string (should use str_replace).
    $result2 = StringManipulation::strReplace('an', 'z', 'banana');
    expect($result2)->toBe('bzza');

    // This verifies the behavior difference - if the mutation changes the length check.
    // from === 1 to === 2, both calls would produce the same behavior, and this test would fail.
});
test('single character vs multiple character', function (): void {
    // Create a scenario where strtr and str_replace have observable differences.
    // Case 1: Using a single character replacement (should use strtr).
    $subject = 'abababa';
    $result1 = StringManipulation::strReplace('a', 'c', $subject);

    // Case 2: Using an array with equivalent replacements (should use str_replace).
    $result2 = StringManipulation::strReplace(['a'], ['c'], $subject);

    // Both should produce the same result despite taking different code paths.
    expect($result1)->toBe('cbcbcbc');
    expect($result2)->toBe($result1);

    // This next test specifically looks at behavior that would be different.
    // if the optimization wasn't properly working.
    // Using overlapping replacements, the order matters in str_replace but not in strtr.
    $complex = 'abcabc';

    // Directly using strtr for comparison.
    $expected = strtr($complex, ['a' => 'z', 'z' => 'y']);

    // Using our optimized function which should handle this the same way.
    $actual = StringManipulation::strReplace('a', 'z', $complex);
    expect($actual)->toBe('zbczbc');
    expect($actual)->toBe($expected);
});
test('empty string optimization', function (): void {
    // Test that empty subject returns empty string immediately.
    $result = StringManipulation::strReplace('a', 'b', '');
    expect($result)->toBe('');

    // Test that empty search/replace with non-empty subject works correctly.
    $result = StringManipulation::strReplace('', 'x', 'abc');
    expect($result)->toBe('abc');
});
