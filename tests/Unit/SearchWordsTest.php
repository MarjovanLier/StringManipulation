<?php

declare(strict_types=1);
use MarjovanLier\StringManipulation\StringManipulation;

const HELLO_WORLD_LOWERCASE = 'hello world';
test('search words function', function (): void {
    // Basic tests
    expect(StringManipulation::searchWords('MacDonald'))->toBe('macdonald');
    expect(StringManipulation::searchWords('Hello World'))->toBe(HELLO_WORLD_LOWERCASE);
    expect(StringManipulation::searchWords('Hèllo Wørld'))->toBe(HELLO_WORLD_LOWERCASE);
    expect(StringManipulation::searchWords('a/b/c'))->toBe('a b c');
    expect(StringManipulation::searchWords('hello_world'))->toBe(HELLO_WORLD_LOWERCASE);
});
test('search words function negative', function (): void {
    // Passing null
    expect(StringManipulation::searchWords(null))->toBeNull();

    // Passing numbers
    expect(StringManipulation::searchWords('12345'))->toBe('12345');

    // Passing special characters
    expect(StringManipulation::searchWords('!@#$%'))->toBe('! #$%');

    // Passing strings with extra spaces
    expect(StringManipulation::searchWords('  hello   world  '))->toBe(HELLO_WORLD_LOWERCASE);

    // Passing strings with mixed special characters and extra spaces
    expect(StringManipulation::searchWords('hello / world'))->toBe(HELLO_WORLD_LOWERCASE);
    expect(StringManipulation::searchWords('  hello / world  '))->toBe(HELLO_WORLD_LOWERCASE);
});
test('search words returns lowercase output', function (): void {
    $result = StringManipulation::searchWords('HeLLo_World');
    expect($result)->toBe(HELLO_WORLD_LOWERCASE);
});
test('search words returns lowercase output regardless of input case', function (): void {
    $result = StringManipulation::searchWords('HeLLo_{WorLD}_(Test)');
    expect($result)->toBe('hello world test');
});
test('search words', function (): void {
    $words = '{Hello/World?}';
    $result = StringManipulation::searchWords($words);
    expect($result)->toBe(HELLO_WORLD_LOWERCASE);
});
test('search words upper', function (): void {
    $words = 'HELLO WORLD';
    $result = StringManipulation::searchWords($words);
    expect($result)->toBe(HELLO_WORLD_LOWERCASE);
});
test('search words with unlisted special characters', function (): void {
    $words = '[Hello*World!]';
    $result = StringManipulation::searchWords($words);
    expect($result)->toBe('[hello world!]');
});
