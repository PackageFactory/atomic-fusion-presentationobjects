<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Enum;

use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\EnumType;
use PHPUnit\Framework\Assert;

/**
 * Test cases for EnumType
 */
class EnumTypeTest extends UnitTestCase
{
    /**
     * @dataProvider enumTypeProvider
     * @param array<mixed> $valueArray
     * @param array<mixed> $expectedValueArray
     */
    public function testProcessValueArray(EnumType $subject, array $valueArray, array $expectedValueArray): void
    {
        Assert::assertSame($expectedValueArray, $subject->processValueArray($valueArray));
    }

    /**
     * @return array<mixed>
     */
    public static function enumTypeProvider(): array
    {
        return [
            [
                EnumType::TYPE_STRING,
                ['a', 'b', 'c'],
                ['a' => 'a', 'b' => 'b', 'c' => 'c']
            ],
            [
                EnumType::TYPE_INT,
                ['a:1', 'b:2', 'c:3'],
                ['a' => 1, 'b' => 2, 'c' => 3]
            ]
        ];
    }
}
