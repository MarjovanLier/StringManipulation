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

test('search words converts all special characters to spaces', function (): void {
    // Test each character from the searchChars array:
    // {, }, (, ), /, \, @, :, ", ?, ,, ., _

    // Characters that were NOT being tested (6 surviving mutations):
    expect(StringManipulation::searchWords('hello}world'))->toBe('hello world');
    expect(StringManipulation::searchWords('hello)world'))->toBe('hello world');
    expect(StringManipulation::searchWords('hello\\world'))->toBe('hello world');
    expect(StringManipulation::searchWords('hello:world'))->toBe('hello world');
    expect(StringManipulation::searchWords('hello,world'))->toBe('hello world');
    expect(StringManipulation::searchWords('hello.world'))->toBe('hello world');
});

test('search words converts double quote to space', function (): void {
    // Line 90 mutation: RemoveArrayItem for " (double quote)
    expect(StringManipulation::searchWords('hello"world'))->toBe('hello world');
    expect(StringManipulation::searchWords('"quoted"'))->toBe('quoted');
    expect(StringManipulation::searchWords('say "hello" world'))->toBe('say hello world');
});

test('search words handles empty string correctly', function (): void {
    // Line 100 mutation: EmptyStringToNotEmpty
    // Test that preg_replace returning null is handled correctly
    expect(StringManipulation::searchWords(''))->toBe('');
});
