<?php

declare(strict_types=1);

namespace MarjovanLier\StringManipulation\Tests\Unit;

use MarjovanLier\StringManipulation\StringManipulation;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Integration tests for both critical bug fixes working together.
 *
 * Tests that both the uppercase accent mapping fix and array validation fix
 * work correctly in combination.
 *
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversMethod(\MarjovanLier\StringManipulation\StringManipulation::class, 'searchWords')]
#[\PHPUnit\Framework\Attributes\CoversMethod(\MarjovanLier\StringManipulation\StringManipulation::class, 'removeAccents')]
final class CriticalBugFixIntegrationTest extends TestCase
{
    /**
     * Reset static cache between tests to ensure clean test state.
     */
    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->resetStaticCache();
    }

    #[\Override]
    protected function tearDown(): void
    {
        $this->resetStaticCache();
        parent::tearDown();
    }

    /**
     * Reset static cache properties to ensure clean test state.
     * @psalm-suppress UnusedMethodCall
     */
    private function resetStaticCache(): void
    {
        $reflectionClass = new ReflectionClass(StringManipulation::class);

        $reflectionProperty = $reflectionClass->getProperty('searchWordsMapping');
        $reflectionProperty->setValue(null, []);

        $accentsReplacement = $reflectionClass->getProperty('accentsReplacement');
        $accentsReplacement->setValue(null, []);
    }

    /**
     * Test that both fixes work together correctly.
     */
    public function testBothFixesIntegrationHappyFlow(): void
    {
        // Reset cache to ensure clean test
        $this->resetStaticCache();

        // Test the specific case mentioned in the bug report
        $result = StringManipulation::searchWords('À');
        self::assertEquals('a', $result, "The critical bug case: searchWords('À') must return 'a', not 'A'");

        // Test multiple uppercase accented characters
        $result = StringManipulation::searchWords('ÀÁÇ');
        self::assertEquals('aac', $result, "Multiple uppercase accents should all be lowercase");

        // Test that removeAccents preserves case but uses proper arrays
        $result = StringManipulation::removeAccents('ÀÁÇ');
        self::assertEquals('AAC', $result, "removeAccents should preserve uppercase");

        // Test mixed sentence with various accent types
        $result = StringManipulation::searchWords('Café À la CARTE');
        self::assertEquals('cafe a la carte', $result, "Mixed case sentence should be all lowercase");
    }

    /**
     * Test error conditions with both fixes in place.
     */
    public function testBothFixesIntegrationNegativeFlow(): void
    {
        // Test with problematic inputs that previously could cause issues
        $problematicInputs = [
            'À' . str_repeat('x', 1000),  // Uppercase accent + long string
            str_repeat('ÀÁÇ', 500),       // Many uppercase accents
            'À' . "\x00\x01\x02" . 'Ç',  // Uppercase accents with binary data
        ];

        foreach ($problematicInputs as $problematicInput) {
            // Both methods should handle these gracefully
            StringManipulation::searchWords($problematicInput);
            StringManipulation::removeAccents($problematicInput);
        }

        // Test passes if we reach this point without exceptions
        self::expectNotToPerformAssertions();
    }
}
