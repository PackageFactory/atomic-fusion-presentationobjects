<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component\PropType;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\SlotPropType;
use PHPUnit\Framework\Assert;

/**
 * Test cases for the Slot PropType
 */
final class SlotPropTypeTest extends UnitTestCase
{
    public function testGetType(): void
    {
        $subject = new SlotPropType(false);
        Assert::assertSame('SlotInterface', $subject->getType());
        $nullableSubject = new SlotPropType(true);
        Assert::assertSame('?SlotInterface', $nullableSubject->getType());
    }

    /**
     * @dataProvider definitionDataProvider
     * @param SlotPropType $subject
     * @param string $propName
     * @param string $expectedDefinitionData
     * @return void
     */
    public function testGetDefinitionData(SlotPropType $subject, string $propName, string $expectedDefinitionData): void
    {
        Assert::assertSame($expectedDefinitionData, $subject->getDefinitionData($propName));
    }

    /**
     * @return array<mixed>
     */
    public function definitionDataProvider(): array
    {
        return [
            [
                new SlotPropType(false),
                'myProperty',
                '
            <PackageFactory.AtomicFusion.PresentationObjects:Slot presentationObject={presentationObject.myProperty} />
        '
            ],
            [
                new SlotPropType(true),
                'myProperty',
                '
            <PackageFactory.AtomicFusion.PresentationObjects:Slot presentationObject={presentationObject.myProperty} @if.isToBeRendered={presentationObject.myProperty} />
        '
            ]
        ];
    }
}
