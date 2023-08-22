<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Fusion;

use GuzzleHttp\Psr7\Uri;
use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Vendor\Site\Presentation\Component\AnotherComponent\AnotherComponent;
use Vendor\Site\Presentation\Component\Link\Link;
use Vendor\Site\Presentation\Component\MyComponent\MyComponent;
use Vendor\Site\Presentation\Component\Text\Text as SiteText;
use Vendor\Shared\Presentation\Component\Text\Text as SharedText;

/**
 * Test for the AbstractComponentPresentationObject
 */
final class AbstractComponentPresentationObjectTest extends UnitTestCase
{
    /**
     * @return array<mixed>
     */
    public function presentationObjectsAndPrototypeNamesProvider(): array
    {
        return [
            AnotherComponent::class => [
                new AnotherComponent(42),
                'Vendor.Site:Component.AnotherComponent'
            ],
            Link::class => [
                new Link(new Uri('https://neos.io/'), null),
                'Vendor.Site:Component.Link'
            ],
            MyComponent::class => [
                new MyComponent('Test', new AnotherComponent(42)),
                'Vendor.Site:Component.MyComponent'
            ],
            SiteText::class => [
                new SiteText('Lorem ipsum...'),
                'Vendor.Site:Component.Text'
            ],
            SharedText::class => [
                new SharedText('Lorem ipsum...'),
                'Vendor.Shared:Component.Text'
            ],
        ];
    }

    /**
     * @dataProvider presentationObjectsAndPrototypeNamesProvider
     * @test
     * @small
     * @param AbstractComponentPresentationObject $implementation
     * @param string $expectedPrototypeName
     * @return void
     */
    public function implementsSlotInterfaceAndSmartlyGuessesFusionPrototypeNameFromInheritingClassNames(
        AbstractComponentPresentationObject $implementation,
        string $expectedPrototypeName
    ): void {
        $this->assertInstanceOf(SlotInterface::class, $implementation);
        $this->assertEquals($expectedPrototypeName, $implementation->getPrototypeName());
    }
}
