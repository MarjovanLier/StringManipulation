<?php

declare(strict_types=1);
use MarjovanLier\StringManipulation\StringManipulation;

test('search words function', function (): void {
    // Basic tests
    expect(StringManipulation::searchWords('MacDonald'))->toBe('macdonald');
    expect(StringManipulation::searchWords('Hello World'))->toBe('hello world');
    expect(StringManipulation::searchWords('Hèllo Wørld'))->toBe('hello world');
    expect(StringManipulation::searchWords('a/b/c'))->toBe('a b c');
    expect(StringManipulation::searchWords('hello_world'))->toBe('hello world');
});
test('search words function negative', function (): void {
    // Passing null
    expect(StringManipulation::searchWords(null))->toBeNull();

    // Passing numbers
    expect(StringManipulation::searchWords('12345'))->toBe('12345');

    // Passing special characters
    expect(StringManipulation::searchWords('!@#$%'))->toBe('! #$%');

    // Passing strings with extra spaces
    expect(StringManipulation::searchWords('  hello   world  '))->toBe('hello world');

    // Passing strings with mixed special characters and extra spaces
    expect(StringManipulation::searchWords('hello / world'))->toBe('hello world');
    expect(StringManipulation::searchWords('  hello / world  '))->toBe('hello world');
});
test('search words returns lowercase output', function (): void {
    $result = StringManipulation::searchWords('HeLLo_World');
    expect($result)->toBe('hello world');
});
test('search words returns lowercase output regardless of input case', function (): void {
    $result = StringManipulation::searchWords('HeLLo_{WorLD}_(Test)');
    expect($result)->toBe('hello world test');
});
test('search words', function (): void {
    $words = '{Hello/World?}';
    $result = StringManipulation::searchWords($words);
    expect($result)->toBe('hello world');
});
test('search words upper', function (): void {
    $words = 'HELLO WORLD';
    $result = StringManipulation::searchWords($words);
    expect($result)->toBe('hello world');
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

test('search words converts question mark to space', function (): void {
    // Line 90 mutation: RemoveArrayItem for ? (question mark)
    expect(StringManipulation::searchWords('hello?world'))->toBe('hello world');
    expect(StringManipulation::searchWords('what?where?'))->toBe('what where');
    expect(StringManipulation::searchWords('question?'))->toBe('question');
});

test('search words converts at symbol to space', function (): void {
    // Line 90 mutation: RemoveArrayItem for @ (at symbol)
    expect(StringManipulation::searchWords('hello@world'))->toBe('hello world');
    expect(StringManipulation::searchWords('user@domain'))->toBe('user domain');
});

test('search words converts opening parenthesis to space', function (): void {
    // Line 90 mutation: RemoveArrayItem for ( (opening parenthesis)
    expect(StringManipulation::searchWords('hello(world'))->toBe('hello world');
    expect(StringManipulation::searchWords('(test'))->toBe('test');
});

test('search words converts opening brace to space', function (): void {
    // Line 90 mutation: RemoveArrayItem for { (opening brace)
    expect(StringManipulation::searchWords('hello{world'))->toBe('hello world');
    expect(StringManipulation::searchWords('{test}'))->toBe('test');
});

test('search words converts forward slash to space', function (): void {
    // Line 90 mutation: RemoveArrayItem for / (forward slash)
    expect(StringManipulation::searchWords('hello/world'))->toBe('hello world');
    expect(StringManipulation::searchWords('path/to/file'))->toBe('path to file');
});

test('search words converts underscore to space', function (): void {
    // Line 90 mutation: RemoveArrayItem for _ (underscore)
    expect(StringManipulation::searchWords('hello_world'))->toBe('hello world');
    expect(StringManipulation::searchWords('snake_case_name'))->toBe('snake case name');
});
