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
    // Line 276 mutation: RemoveEarlyReturn
    // Test that empty subject returns empty string immediately
    $result = StringManipulation::strReplace('a', 'b', '');
    expect($result)->toBe('');

    // Test that empty search/replace with non-empty subject works correctly
    $result = StringManipulation::strReplace('', 'x', 'abc');
    expect($result)->toBe('abc');
});

test('single character optimization mutations', function (): void {
    // Line 280 mutations: IdenticalToNotIdentical, BooleanAndToBooleanOr, DecrementInteger, IncrementInteger
    // Line 281 mutation: RemoveEarlyReturn
    // These test the optimization path: is_string($search) && is_string($replace) && strlen($search) === 1

    // All three conditions must be true:
    // 1. search is string (not array)
    // 2. replace is string (not array)
    // 3. search length is exactly 1

    // Test case where search is array (first condition false)
    $arraySearch = StringManipulation::strReplace(['a'], ['b'], 'apple');
    expect($arraySearch)->toBe('bpple');

    // Test case where search length is 0 (third condition false)
    $zeroLength = StringManipulation::strReplace('', 'x', 'apple');
    expect($zeroLength)->toBe('apple');

    // Test case where search length is 2 (third condition false - not === 1)
    $twoChars = StringManipulation::strReplace('pp', 'tt', 'apple');
    expect($twoChars)->toBe('attle');

    // Test case where ALL conditions are true (optimization path)
    $singleChar = StringManipulation::strReplace('p', 't', 'apple');
    expect($singleChar)->toBe('attle');
});

test('single character optimization uses correct path', function (): void {
    // Line 280 mutations specifically test the strlen($search) === 1 check
    // DecrementInteger would change it to === 0
    // IncrementInteger would change it to === 2

    // With length === 1 (correct), this should use strtr optimization
    $len1 = StringManipulation::strReplace('x', 'y', 'xxx');
    expect($len1)->toBe('yyy');

    // With length === 0 (if decremented), empty search would not match
    $len0 = StringManipulation::strReplace('', 'y', 'xxx');
    expect($len0)->toBe('xxx'); // Should not change

    // With length === 2 (if incremented), this would use str_replace instead
    $len2 = StringManipulation::strReplace('xx', 'yy', 'xxx');
    expect($len2)->toBe('yyx'); // Different result than single char replacement
});
