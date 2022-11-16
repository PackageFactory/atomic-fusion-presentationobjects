<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component;

use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\Component;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentName;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\FusionNamespace;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageKey;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\Props;
use PHPUnit\Framework\Assert;

/**
 * Test cases for Component
 */
class ComponentTest extends UnitTestCase
{
    /**
     * @var Component
     */
    private $subject;

    public function setUp(): void
    {
        parent::setUp();

        $componentName = new ComponentName(
            new PackageKey('Vendor.Site'),
            FusionNamespace::default(),
            'MyNewComponent',
        );
        $this->subject = new Component(
            $componentName,
            Props::fromInputArray(
                $componentName,
                [
                    'bool:bool',
                    'nullableBool:?bool',
                    'float:float',
                    'nullableFloat:?float',
                    'int:int',
                    'nullableInt:?int',
                    'string:string',
                    'nullableString:?string',
                    'uri:Uri',
                    'nullableUri:?Uri',
                    'image:ImageSource',
                    'nullableImage:?ImageSource',
                    'subComponent:MyComponent',
                    'nullableSubComponent:?MyComponent',
                    'componentArray:array<MyComponent>',
                    'enum:MyStringEnum',
                    'nullableEnum:?MyStringEnum',
                    'slot:slot',
                    'nullableSlot:?slot',
                ]
            )
        );
    }

    public function testGetClassContent(): void
    {
        Assert::assertSame(
            '<?php

/*
 * This file is part of the Vendor.Site package.
 */

declare(strict_types=1);

namespace Vendor\Site\Presentation\Component\MyNewComponent;

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Psr\Http\Message\UriInterface;
use Sitegeist\Kaleidoscope\Domain\ImageSourceInterface;
use Vendor\Site\Presentation\Component\MyComponent\MyComponent;
use Vendor\Site\Presentation\Component\MyComponent\MyComponents;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;

#[Flow\Proxy(false)]
final class MyNewComponent extends AbstractComponentPresentationObject
{
    public function __construct(
        public readonly bool $bool,
        public readonly ?bool $nullableBool,
        public readonly float $float,
        public readonly ?float $nullableFloat,
        public readonly int $int,
        public readonly ?int $nullableInt,
        public readonly string $string,
        public readonly ?string $nullableString,
        public readonly UriInterface $uri,
        public readonly ?UriInterface $nullableUri,
        public readonly ImageSourceInterface $image,
        public readonly ?ImageSourceInterface $nullableImage,
        public readonly MyComponent $subComponent,
        public readonly ?MyComponent $nullableSubComponent,
        public readonly MyComponents $componentArray,
        public readonly MyStringEnum $enum,
        public readonly ?MyStringEnum $nullableEnum,
        public readonly SlotInterface $slot,
        public readonly ?SlotInterface $nullableSlot
    ) {
    }
}
',
            $this->subject->getClassContent()
        );
    }

    public function testGetFusionContent(): void
    {
        Assert::assertSame(
            'prototype(Vendor.Site:Component.MyNewComponent) < prototype(PackageFactory.AtomicFusion.PresentationObjects:PresentationObjectComponent) {
    @presentationObjectInterface = \'Vendor\\\\Site\\\\Presentation\\\\Component\\\\MyNewComponent\\\\MyNewComponent\'

    @styleguide {
        title = \'MyNewComponent\'

        props {
            bool = true
            nullableBool = true
            float = 47.11
            nullableFloat = 47.11
            int = 4711
            nullableInt = 4711
            string = \'Text\'
            nullableString = \'Text\'
            uri = \'https://www.neos.io\'
            nullableUri = \'https://www.neos.io\'
            image = Sitegeist.Kaleidoscope:DummyImageSource {
                height = 1920
                width = 1080
            }
            nullableImage = Sitegeist.Kaleidoscope:DummyImageSource {
                height = 1920
                width = 1080
            }
            subComponent {
                text = \'Text\'
                other {
                    number = 4711
                }
            }
            nullableSubComponent {
                text = \'Text\'
                other {
                    number = 4711
                }
            }
            componentArray {
                0 {
                    text = \'Text\'
                    other {
                        number = 4711
                    }
                }
                1 {
                    text = \'Text\'
                    other {
                        number = 4711
                    }
                }
            }
            enum = \'myValue\'
            nullableEnum = \'myValue\'
            slot = \'- Add Slot Content -\'
            nullableSlot = \'- Add Slot Content -\'
        }
    }

    renderer = afx`
        <div>[MyNewComponent]</div>
        <dl>
            <dt>bool:</dt>
            <dd>{presentationObject.bool}</dd>
            <dt>nullableBool:</dt>
            <dd>{presentationObject.nullableBool}</dd>
            <dt>float:</dt>
            <dd>{presentationObject.float}</dd>
            <dt>nullableFloat:</dt>
            <dd>{presentationObject.nullableFloat}</dd>
            <dt>int:</dt>
            <dd>{presentationObject.int}</dd>
            <dt>nullableInt:</dt>
            <dd>{presentationObject.nullableInt}</dd>
            <dt>string:</dt>
            <dd>{presentationObject.string}</dd>
            <dt>nullableString:</dt>
            <dd>{presentationObject.nullableString}</dd>
            <dt>uri:</dt>
            <dd>{presentationObject.uri}</dd>
            <dt>nullableUri:</dt>
            <dd>{presentationObject.nullableUri}</dd>
            <dt>image:</dt>
            <dd>
                <Sitegeist.Kaleidoscope:Image imageSource={presentationObject.image} />
            </dd>
            <dt>nullableImage:</dt>
            <dd>
                <Sitegeist.Kaleidoscope:Image imageSource={presentationObject.nullableImage} @if={presentationObject.nullableImage} />
            </dd>
            <dt>subComponent:</dt>
            <dd>
                <Vendor.Site:Component.MyComponent presentationObject={presentationObject.subComponent} />
            </dd>
            <dt>nullableSubComponent:</dt>
            <dd>
                <Vendor.Site:Component.MyComponent presentationObject={presentationObject.nullableSubComponent} @if={presentationObject.nullableSubComponent} />
            </dd>
            <dt>componentArray:</dt>
            <dd>
                <Neos.Fusion:Loop items={presentationObject.componentArray}>
                    <Vendor.Site:Component.MyComponent presentationObject={item} />
                </Neos.Fusion:Loop>
            </dd>
            <dt>enum:</dt>
            <dd>{presentationObject.enum.value}</dd>
            <dt>nullableEnum:</dt>
            <dd>{presentationObject.nullableEnum.value}</dd>
            <dt>slot:</dt>
            <dd>
                <PackageFactory.AtomicFusion.PresentationObjects:Slot presentationObject={presentationObject.slot} />
            </dd>
            <dt>nullableSlot:</dt>
            <dd>
                <PackageFactory.AtomicFusion.PresentationObjects:Slot presentationObject={presentationObject.nullableSlot} @if={presentationObject.nullableSlot} />
            </dd>
        </dl>
    `
}
',
            $this->subject->getFusionContent()
        );
    }

    public function testGetComponentArrayContent(): void
    {
        Assert::assertSame(
            '<?php

/*
 * This file is part of the Vendor.Site package.
 */

declare(strict_types=1);

namespace Vendor\Site\Presentation\Component\MyNewComponent;

use Neos\Flow\Annotations as Flow;

/**
 * @implements \IteratorAggregate<int,MyNewComponent>
 */
#[Flow\Proxy(false)]
final class MyNewComponents implements \IteratorAggregate, \Countable
{
    /**
     * @var array<int,MyNewComponent>
     */
    private array $myNewComponents;

    public function __construct(MyNewComponent ...$myNewComponents)
    {
        $this->myNewComponents = $myNewComponents;
    }

    /**
     * @return \ArrayIterator<int,MyNewComponent>|MyNewComponent[]
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->myNewComponents);
    }

    public function count(): int
    {
        return count($this->myNewComponents);
    }
}
',
            $this->subject->getComponentArrayContent()
        );
    }
}
