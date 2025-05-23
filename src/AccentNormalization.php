<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation;

/**
 * Trait AccentNormalization.
 *
 * This trait provides a set of constants and methods for normalizing accents in a string.
 * It defines two constants: REMOVE_ACCENTS_FROM and REMOVE_ACCENTS_TO, which are arrays of characters.
 * The REMOVE_ACCENTS_FROM array contains characters with accents, and the REMOVE_ACCENTS_TO array contains
 * the corresponding characters without accents.
 *
 * The trait does not provide any methods, but the constants can be used in conjunction with string manipulation
 * functions such as str_replace() to remove accents from a string.
 *
 * Example usage:
 * $normalized = str_replace(REMOVE_ACCENTS_FROM, REMOVE_ACCENTS_TO, $stringWithAccents);
 *
 * Note: This trait is intended to be used in a class that requires accent normalization functionality.
 */
trait AccentNormalization
{
    /**
     * An array of characters with accents and special characters. These characters are intended to be replaced
     * in a string to normalize it.
     */
    private const array REMOVE_ACCENTS_FROM = [
        '*',
        '?',
        '’',
        '.',
        ',',
        '“',
        '”',
        'À',
        'Á',
        'Â',
        'Ã',
        'Ä',
        'Å',
        'Æ',
        'Ç',
        'È',
        'É',
        'Ê',
        'Ë',
        'Ì',
        'Í',
        'Î',
        'Ï',
        'Ð',
        'Ñ',
        'Ò',
        'Ó',
        'Ô',
        'Õ',
        'Ö',
        'Ø',
        'Ù',
        'Ú',
        'Û',
        'Ü',
        'Ý',
        'ß',
        'à',
        'á',
        'â',
        'ã',
        'ä',
        'å',
        'æ',
        'ç',
        'è',
        'é',
        'ê',
        'ë',
        'ì',
        'í',
        'î',
        'ï',
        'ñ',
        'ò',
        'ó',
        'ô',
        'õ',
        'ö',
        'ø',
        'ù',
        'ú',
        'û',
        'ü',
        'ý',
        'ÿ',
        'Ā',
        'ā',
        'Ă',
        'ă',
        'Ą',
        'ą',
        'Ć',
        'ć',
        'Ĉ',
        'ĉ',
        'Ċ',
        'ċ',
        'Č',
        'č',
        'Ď',
        'ď',
        'Đ',
        'đ',
        'Ē',
        'ē',
        'Ĕ',
        'ĕ',
        'Ė',
        'ė',
        'Ę',
        'ę',
        'Ě',
        'ě',
        'Ĝ',
        'ĝ',
        'Ğ',
        'ğ',
        'Ġ',
        'ġ',
        'Ģ',
        'ģ',
        'Ĥ',
        'ĥ',
        'Ħ',
        'ħ',
        'Ĩ',
        'ĩ',
        'Ī',
        'ī',
        'Ĭ',
        'ĭ',
        'Į',
        'į',
        'İ',
        'ı',
        'Ĳ',
        'ĳ',
        'Ĵ',
        'ĵ',
        'Ķ',
        'ķ',
        'Ĺ',
        'ĺ',
        'Ļ',
        'ļ',
        'Ľ',
        'ľ',
        'Ŀ',
        'ŀ',
        'Ł',
        'ł',
        'Ń',
        'ń',
        'Ņ',
        'ņ',
        'Ň',
        'ň',
        'ŉ',
        'Ō',
        'ō',
        'Ŏ',
        'ŏ',
        'Ő',
        'ő',
        'Œ',
        'œ',
        'Ŕ',
        'ŕ',
        'Ŗ',
        'ŗ',
        'Ř',
        'ř',
        'Ś',
        'ś',
        'Ŝ',
        'ŝ',
        'Ş',
        'ş',
        'Š',
        'š',
        'Ţ',
        'ţ',
        'Ť',
        'ť',
        'Ŧ',
        'ŧ',
        'Ũ',
        'ũ',
        'Ū',
        'ū',
        'Ŭ',
        'ŭ',
        'Ů',
        'ů',
        'Ű',
        'ű',
        'Ų',
        'ų',
        'Ŵ',
        'ŵ',
        'Ŷ',
        'ŷ',
        'Ÿ',
        'Ź',
        'ź',
        'Ż',
        'ż',
        'Ž',
        'ž',
        'ſ',
        'ƒ',
        'Ơ',
        'ơ',
        'Ư',
        'ư',
        'Ǎ',
        'ǎ',
        'Ǐ',
        'ǐ',
        'Ǒ',
        'ǒ',
        'Ǔ',
        'ǔ',
        'Ǖ',
        'ǖ',
        'Ǘ',
        'ǘ',
        'Ǚ',
        'ǚ',
        'Ǜ',
        'ǜ',
        'Ǻ',
        'ǻ',
        'Ǽ',
        'ǽ',
        'Ǿ',
        'ǿ',
        'Ά',
        'ά',
        'Έ',
        'έ',
        'Ό',
        'ό',
        'Ώ',
        'ώ',
        'Ί',
        'ί',
        'ϊ',
        'ΐ',
        'Ύ',
        'ύ',
        'ϋ',
        'ΰ',
        'Ή',
        'ή',
    ];

    /**
     * An array of characters without accents. These characters correspond to the characters in the
     * REMOVE_ACCENTS_FROM array and are used to replace them in a string.
     */
    private const array REMOVE_ACCENTS_TO = [
        ' ',
        ' ',
        "'",
        ' ',
        ', ',
        '',
        '',
        'A',
        'A',
        'A',
        'A',
        'A',
        'A',
        'AE',
        'C',
        'E',
        'E',
        'E',
        'E',
        'I',
        'I',
        'I',
        'I',
        'D',
        'N',
        'O',
        'O',
        'O',
        'O',
        'O',
        'O',
        'U',
        'U',
        'U',
        'U',
        'Y',
        's',
        'a',
        'a',
        'a',
        'a',
        'a',
        'a',
        'ae',
        'c',
        'e',
        'e',
        'e',
        'e',
        'i',
        'i',
        'i',
        'i',
        'n',
        'o',
        'o',
        'o',
        'o',
        'o',
        'o',
        'u',
        'u',
        'u',
        'u',
        'y',
        'y',
        'A',
        'a',
        'A',
        'a',
        'A',
        'a',
        'C',
        'c',
        'C',
        'c',
        'C',
        'c',
        'C',
        'c',
        'D',
        'd',
        'D',
        'd',
        'E',
        'e',
        'E',
        'e',
        'E',
        'e',
        'E',
        'e',
        'E',
        'e',
        'G',
        'g',
        'G',
        'g',
        'G',
        'g',
        'G',
        'g',
        'H',
        'h',
        'H',
        'h',
        'I',
        'i',
        'I',
        'i',
        'I',
        'i',
        'I',
        'i',
        'I',
        'i',
        'IJ',
        'ij',
        'J',
        'j',
        'K',
        'k',
        'L',
        'l',
        'L',
        'l',
        'L',
        'l',
        'L',
        'l',
        'l',
        'l',
        'N',
        'n',
        'N',
        'n',
        'N',
        'n',
        'n',
        'O',
        'o',
        'O',
        'o',
        'O',
        'o',
        'OE',
        'oe',
        'R',
        'r',
        'R',
        'r',
        'R',
        'r',
        'S',
        's',
        'S',
        's',
        'S',
        's',
        'S',
        's',
        'T',
        't',
        'T',
        't',
        'T',
        't',
        'U',
        'u',
        'U',
        'u',
        'U',
        'u',
        'U',
        'u',
        'U',
        'u',
        'U',
        'u',
        'W',
        'w',
        'Y',
        'y',
        'Y',
        'Z',
        'z',
        'Z',
        'z',
        'Z',
        'z',
        's',
        'f',
        'O',
        'o',
        'U',
        'u',
        'A',
        'a',
        'I',
        'i',
        'O',
        'o',
        'U',
        'u',
        'U',
        'u',
        'U',
        'u',
        'U',
        'u',
        'U',
        'u',
        'A',
        'a',
        'AE',
        'ae',
        'O',
        'o',
        'Α',
        'α',
        'Ε',
        'ε',
        'Ο',
        'ο',
        'Ω',
        'ω',
        'Ι',
        'ι',
        'ι',
        'ι',
        'Υ',
        'υ',
        'υ',
        'υ',
        'Η',
        'η',
    ];
}
