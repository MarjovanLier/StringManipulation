<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation;

/**
 * Trait UnicodeMappings.
 *
 * This trait provides a mapping of Unicode characters to their ANSI counterparts.
 * It defines a constant: UTF8_ANSI2, which is an associative array where each key is a Unicode character
 * and the corresponding value is the ANSI character.
 *
 * The trait does not provide any methods, but the constant can be used in conjunction with string manipulation
 * functions such as strtr() to convert a string from Unicode to ANSI.
 *
 * Example usage:
 * $converted = strtr($unicodeString, UTF8_ANSI2);
 *
 * Note: This trait is intended to be used in a class that requires Unicode to ANSI conversion functionality.
 */
trait UnicodeMappings
{
    /**
     * An associative array mapping Unicode characters to their ANSI counterparts.
     * The keys of the array are Unicode characters, and the values are the corresponding ANSI characters.
     *
     * @var array<string, string>
     */
    private const UTF8_ANSI2 = [
        '\u00c0' => 'À',
        '\u00c1' => 'Á',
        '\u00c2' => 'Â',
        '\u00c3' => 'Ã',
        '\u00c4' => 'Ä',
        '\u00c5' => 'Å',
        '\u00c6' => 'Æ',
        '\u00c7' => 'Ç',
        '\u00c8' => 'È',
        '\u00c9' => 'É',
        '\u00ca' => 'Ê',
        '\u00cb' => 'Ë',
        '\u00cc' => 'Ì',
        '\u00cd' => 'Í',
        '\u00ce' => 'Î',
        '\u00cf' => 'Ï',
        '\u00d1' => 'Ñ',
        '\u00d2' => 'Ò',
        '\u00d3' => 'Ó',
        '\u00d4' => 'Ô',
        '\u00d5' => 'Õ',
        '\u00d6' => 'Ö',
        '\u00d8' => 'Ø',
        '\u00d9' => 'Ù',
        '\u00da' => 'Ú',
        '\u00db' => 'Û',
        '\u00dc' => 'Ü',
        '\u00dd' => 'Ý',
        '\u00df' => 'ß',
        '\u00e0' => 'à',
        '\u00e1' => 'á',
        '\u00e2' => 'â',
        '\u00e3' => 'ã',
        '\u00e4' => 'ä',
        '\u00e5' => 'å',
        '\u00e6' => 'æ',
        '\u00e7' => 'ç',
        '\u00e8' => 'è',
        '\u00e9' => 'é',
        '\u00ea' => 'ê',
        '\u00eb' => 'ë',
        '\u00ec' => 'ì',
        '\u00ed' => 'í',
        '\u00ee' => 'î',
        '\u00ef' => 'ï',
        '\u00f0' => 'ð',
        '\u00f1' => 'ñ',
        '\u00f2' => 'ò',
        '\u00f3' => 'ó',
        '\u00f4' => 'ô',
        '\u00f5' => 'õ',
        '\u00f6' => 'ö',
        '\u00f8' => 'ø',
        '\u00f9' => 'ù',
        '\u00fa' => 'ú',
        '\u00fb' => 'û',
        '\u00fc' => 'ü',
        '\u00fd' => 'ý',
        '\u00ff' => 'ÿ',
    ];
}
