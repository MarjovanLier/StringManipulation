<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Unit;

use MarjovanLier\StringManipulation\StringManipulation;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \MarjovanLier\StringManipulation\StringManipulation::nameFix
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
final class NameFixTest extends TestCase
{
    /**
     * Test the nameFix function.
     */
    public function testNameFixFunction(): void
    {
        // Basic tests
        self::assertEquals('McDonald', StringManipulation::nameFix('mcdonald'));
        self::assertEquals('MacArthur', StringManipulation::nameFix('macarthur'));
        self::assertEquals('van der Saar', StringManipulation::nameFix('van der saar'));
        self::assertEquals('de la Hoya', StringManipulation::nameFix('de la hoya'));
        self::assertEquals("O'reilly", StringManipulation::nameFix('o’reilly'));

        // Tests with accents
        self::assertEquals('de la Torre', StringManipulation::nameFix('de la tòrré'));
        self::assertEquals('McDonald', StringManipulation::nameFix('mcdónald'));
    }


    /**
     * Negative tests for the nameFix function.
     */
    public function testNameFixFunctionNegative(): void
    {
        // Passing null
        self::assertNull(StringManipulation::nameFix(null));

        // Passing numbers
        self::assertEquals('12345', StringManipulation::nameFix('12345'));

        // Passing special characters
        self::assertEquals('!@#$%', StringManipulation::nameFix('!@#$%'));
    }


    public function testNameFix(): void
    {
        $lastName = 'mcDonald';
        $result = StringManipulation::nameFix($lastName);
        self::assertEquals('McDonald', $result);
    }


    public function testNameFixUpper(): void
    {
        $lastName = 'MCDONALD';
        $result = StringManipulation::nameFix($lastName);
        self::assertEquals('McDonald', $result);
    }


    public function testNameFixUpper2(): void
    {
        $lastName = 'VAN LIER';
        $result = StringManipulation::nameFix($lastName);
        self::assertEquals('van Lier', $result);
    }


    public function testNameFixSpace(): void
    {
        $lastName = ' mcDonald ';
        $result = StringManipulation::nameFix($lastName);
        self::assertEquals('McDonald', $result);
    }


    public function testNameFixWithNoSpecialPrefix(): void
    {
        $lastName = 'Johnson';
        $result = StringManipulation::nameFix($lastName);
        self::assertEquals('Johnson', $result);
    }


    public function testNameFixWithMacPrefixAndNotNullLastName(): void
    {
        $result = StringManipulation::nameFix('macIntosh');
        self::assertEquals('MacIntosh', $result);
    }


    public function testNameFixWithNoMacPrefixAndNotNullLastName(): void
    {
        $result = StringManipulation::nameFix('Smith');
        self::assertEquals('Smith', $result);
    }


    public function testNameFixWithMacPrefixAndNullLastName(): void
    {
        $result = StringManipulation::nameFix('mac');
        self::assertEquals('Mac', $result);
    }


    public function testNameFixWithNoMacPrefixAndNullLastName(): void
    {
        $result = StringManipulation::nameFix(null);
        self::assertNull($result);
    }


    public function testNameFixWithHyphenatedName(): void
    {
        $inputName = 'macdonald-smith-jones';
        $result = StringManipulation::nameFix($inputName);
        self::assertEquals('MacDonald-Smith-Jones', $result);
    }


    public function testNameFixWithMixedCaseHyphenatedName(): void
    {
        $inputName = 'mACdonald-sMith-jOnes';
        $result = StringManipulation::nameFix($inputName);
        self::assertEquals('MacDonald-Smith-Jones', $result);
    }


    public function testNameFixWithLowercaseAfterHyphen(): void
    {
        $inputName = 'MacDonald-sMith-jOnes';
        $result = StringManipulation::nameFix($inputName);
        self::assertEquals('MacDonald-Smith-Jones', $result);
    }


    public function testNameFixWithMacSpacePrefix(): void
    {
        $inputName = 'mac jones';
        $result = StringManipulation::nameFix($inputName);
        self::assertEquals('Mac Jones', $result);
    }


    public function testNameFixWithMacPrefixWithoutSpace(): void
    {
        $inputName = 'macjones';
        $result = StringManipulation::nameFix($inputName);
        self::assertEquals('MacJones', $result);
    }


    public function testNameFixWithoutMacPrefixWithSpace(): void
    {
        $inputName = 'mac jones';
        $result = StringManipulation::nameFix($inputName);
        self::assertEquals('Mac Jones', $result);
    }


    public function testNoMacIsUnchanged(): void
    {
        $inputName = 'johnson';
        $result = StringManipulation::nameFix($inputName);
        self::assertEquals('Johnson', $result);
    }


    public function testNoMcIsUnchanged(): void
    {
        $inputName = 'donald';
        $result = StringManipulation::nameFix($inputName);
        self::assertEquals('Donald', $result);
    }


    public function testMcWithSpaceIsUnchanged(): void
    {
        $inputName = 'Mc donald';
        $result = StringManipulation::nameFix($inputName);
        // Expecting the space to remain
        self::assertEquals('Mc Donald', $result);
    }


    public function testLeadingAndTrailingWhitespacesAreRemoved(): void
    {
        $inputName = '   macdonald   ';
        $result = StringManipulation::nameFix($inputName);
        // Expecting no leading or trailing whitespaces
        self::assertEquals('MacDonald', $result);
    }


    public function testTrimAfterRemovingAccents(): void
    {
        // Assuming the inputName will have leading/trailing spaces after removing accents,
        // Adjust this string accordingly based on the behaviour of removeAccents
        // Let's assume À turns into ' A' and È turns into 'E '
        $inputName = 'À Macdonald È';
        $result = StringManipulation::nameFix($inputName);
        // Asserting that there are no leading or trailing spaces
        self::assertEquals('A MacDonald E', $result);
    }


    public function testMcWithoutSpaceIsFixed(): void
    {
        $inputName = 'mcDonald';
        $result = StringManipulation::nameFix($inputName);
        self::assertEquals('McDonald', $result);
    }


    public function testMacWithoutSpaceIsFixed(): void
    {
        $inputName = 'macDonald';
        $result = StringManipulation::nameFix($inputName);
        self::assertEquals('MacDonald', $result);
    }
}
