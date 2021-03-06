<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Enum;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

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
     * @param EnumType $subject
     * @param array<mixed> $valueArray
     * @param array<mixed> $expectedValueArray
     * @return void
     */
    public function testProcessValueArray(EnumType $subject, array $valueArray, array $expectedValueArray): void
    {
        Assert::assertSame($expectedValueArray, $subject->processValueArray($valueArray));
    }

    /**
     * @return array<mixed>
     */
    public function enumTypeProvider(): array
    {
        return [
            [
                EnumType::string(),
                ['a', 'b', 'c'],
                ['a' => 'a', 'b' => 'b', 'c' => 'c']
            ],
            [
                EnumType::int(),
                ['a:1', 'b:2', 'c:3'],
                ['a' => 1, 'b' => 2, 'c' => 3]
            ]
        ];
    }
}
