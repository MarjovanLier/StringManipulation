<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Unit;

use MarjovanLier\StringManipulation\StringManipulation;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversMethod(\MarjovanLier\StringManipulation\StringManipulation::class, 'removeAccents')]
final class RemoveAccentsTest extends TestCase
{
    /**
     * Test the removeAccents function.
     */
    public function testRemoveAccentsFunction(): void
    {
        self::assertEquals('aeiou', StringManipulation::removeAccents('áéíóú'));
        self::assertEquals('AEIOU', StringManipulation::removeAccents('ÁÉÍÓÚ'));
        self::assertEquals('AeOeUe', StringManipulation::removeAccents('ÄëÖëÜë'));
        self::assertEquals('Nino', StringManipulation::removeAccents('Niño'));
        self::assertEquals("cote d'Ivoire", StringManipulation::removeAccents('côte d’Ivoire'));
    }


    /**
     * Negative tests for the removeAccents function.
     */
    public function testRemoveAccentsFunctionNegative(): void
    {
        // Passing empty string
        self::assertEquals('', StringManipulation::removeAccents(''));

        // Passing numbers
        self::assertEquals('12345', StringManipulation::removeAccents('12345'));

        // Passing special characters
        self::assertEquals('!@#$%', StringManipulation::removeAccents('!@#$%'));

        // Passing a string without accents
        self::assertEquals('abcdef', StringManipulation::removeAccents('abcdef'));
    }


    public function testRemoveAccents(): void
    {
        $string = 'ÀÁÂÃÄÅ';
        $result = StringManipulation::removeAccents($string);
        self::assertEquals('AAAAAA', $result);
    }


    public function testRemoveAccentsWithNoAccents(): void
    {
        $string = 'ABCDEF';
        $result = StringManipulation::removeAccents($string);
        self::assertEquals('ABCDEF', $result);
    }
}
