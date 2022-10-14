<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component\PropType;

use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentName;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\ComponentPropType;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\UnionPropType;
use PHPUnit\Framework\Assert;
use Vendor\Shared\Presentation\Component\Text\Text;
use Vendor\Site\Presentation\Component\MyComponent\MyComponent;

/**
 * Test cases for the Union PropType
 */
final class UnionPropTypeTest extends UnitTestCase
{
    /**
     * @dataProvider typeProvider
     */
    public function testGetType(UnionPropType $subject, string $expectedType): void
    {
        Assert::assertSame($expectedType, $subject->getType());
    }

    /**
     * @return array<mixed>
     */
    public function typeProvider(): array
    {
        return [
            [
                new UnionPropType(
                    false,
                    new ComponentPropType(ComponentName::fromClassName(MyComponent::class), false),
                    new ComponentPropType(ComponentName::fromClassName(Text::class), false)
                ),
                'MyComponent|Text'
            ],
            [
                new UnionPropType(
                    true,
                    new ComponentPropType(ComponentName::fromClassName(MyComponent::class), false),
                    new ComponentPropType(ComponentName::fromClassName(Text::class), false)
                ),
                'MyComponent|Text|null'
            ]
        ];
    }

    /**
     * @dataProvider definitionDataProvider
     */
    public function testGetDefinitionData(UnionPropType $subject, string $propName, string $expectedStyleGuideValue): void
    {
        Assert::assertSame($expectedStyleGuideValue, $subject->getDefinitionData($propName));
    }

    /**
     * @return array<mixed>
     */
    public function definitionDataProvider(): array
    {
        return [
            [
                new UnionPropType(
                    false,
                    new ComponentPropType(ComponentName::fromClassName(MyComponent::class), false),
                    new ComponentPropType(ComponentName::fromClassName(Text::class), false)
                ),
                'myProperty',
                '
                <PackageFactory.AtomicFusion.PresentationObjects:Slot presentationObject={presentationObject.myProperty} />
            '
            ],
            [
                new UnionPropType(
                    true,
                    new ComponentPropType(ComponentName::fromClassName(MyComponent::class), false),
                    new ComponentPropType(ComponentName::fromClassName(Text::class), false)
                ),
                'myProperty',
                '
                <PackageFactory.AtomicFusion.PresentationObjects:Slot presentationObject={presentationObject.myProperty} @if={presentationObject.myProperty} />
            '
            ]
        ];
    }
}
