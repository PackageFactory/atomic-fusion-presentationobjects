<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

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
                    'componentArray:array<MyComponent>'
                ]
            ),
            true
        );
    }

    public function testGetInterfaceContent(): void
    {
        Assert::assertSame(
            '<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\MyNewComponent;

/*
 * This file is part of the Vendor.Site package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Psr\Http\Message\UriInterface;
use Sitegeist\Kaleidoscope\EelHelpers\ImageSourceHelperInterface;
use Vendor\Site\Presentation\Component\MyComponent\MyComponentInterface;
use Vendor\Site\Presentation\Component\MyComponent\MyComponents;

interface MyNewComponentInterface extends ComponentPresentationObjectInterface
{
    public function getBool(): bool;

    public function getNullableBool(): ?bool;

    public function getFloat(): float;

    public function getNullableFloat(): ?float;

    public function getInt(): int;

    public function getNullableInt(): ?int;

    public function getString(): string;

    public function getNullableString(): ?string;

    public function getUri(): UriInterface;

    public function getNullableUri(): ?UriInterface;

    public function getImage(): ImageSourceHelperInterface;

    public function getNullableImage(): ?ImageSourceHelperInterface;

    public function getSubComponent(): MyComponentInterface;

    public function getNullableSubComponent(): ?MyComponentInterface;

    public function getComponentArray(): MyComponents;
}
',
            $this->subject->getInterfaceContent()
        );
    }

    public function testGetClassContent(): void
    {
        Assert::assertSame(
            '<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\MyNewComponent;

/*
 * This file is part of the Vendor.Site package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Psr\Http\Message\UriInterface;
use Sitegeist\Kaleidoscope\EelHelpers\ImageSourceHelperInterface;
use Vendor\Site\Presentation\Component\MyComponent\MyComponentInterface;
use Vendor\Site\Presentation\Component\MyComponent\MyComponents;

/**
 * @Flow\Proxy(false)
 */
final class MyNewComponent extends AbstractComponentPresentationObject implements MyNewComponentInterface
{
    private bool $bool;

    private ?bool $nullableBool;

    private float $float;

    private ?float $nullableFloat;

    private int $int;

    private ?int $nullableInt;

    private string $string;

    private ?string $nullableString;

    private UriInterface $uri;

    private ?UriInterface $nullableUri;

    private ImageSourceHelperInterface $image;

    private ?ImageSourceHelperInterface $nullableImage;

    private MyComponentInterface $subComponent;

    private ?MyComponentInterface $nullableSubComponent;

    private MyComponents $componentArray;

    public function __construct(
        bool $bool,
        ?bool $nullableBool,
        float $float,
        ?float $nullableFloat,
        int $int,
        ?int $nullableInt,
        string $string,
        ?string $nullableString,
        UriInterface $uri,
        ?UriInterface $nullableUri,
        ImageSourceHelperInterface $image,
        ?ImageSourceHelperInterface $nullableImage,
        MyComponentInterface $subComponent,
        ?MyComponentInterface $nullableSubComponent,
        MyComponents $componentArray
    ) {
        $this->bool = $bool;
        $this->nullableBool = $nullableBool;
        $this->float = $float;
        $this->nullableFloat = $nullableFloat;
        $this->int = $int;
        $this->nullableInt = $nullableInt;
        $this->string = $string;
        $this->nullableString = $nullableString;
        $this->uri = $uri;
        $this->nullableUri = $nullableUri;
        $this->image = $image;
        $this->nullableImage = $nullableImage;
        $this->subComponent = $subComponent;
        $this->nullableSubComponent = $nullableSubComponent;
        $this->componentArray = $componentArray;
    }

    public function getBool(): bool
    {
        return $this->bool;
    }

    public function getNullableBool(): ?bool
    {
        return $this->nullableBool;
    }

    public function getFloat(): float
    {
        return $this->float;
    }

    public function getNullableFloat(): ?float
    {
        return $this->nullableFloat;
    }

    public function getInt(): int
    {
        return $this->int;
    }

    public function getNullableInt(): ?int
    {
        return $this->nullableInt;
    }

    public function getString(): string
    {
        return $this->string;
    }

    public function getNullableString(): ?string
    {
        return $this->nullableString;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function getNullableUri(): ?UriInterface
    {
        return $this->nullableUri;
    }

    public function getImage(): ImageSourceHelperInterface
    {
        return $this->image;
    }

    public function getNullableImage(): ?ImageSourceHelperInterface
    {
        return $this->nullableImage;
    }

    public function getSubComponent(): MyComponentInterface
    {
        return $this->subComponent;
    }

    public function getNullableSubComponent(): ?MyComponentInterface
    {
        return $this->nullableSubComponent;
    }

    public function getComponentArray(): MyComponents
    {
        return $this->componentArray;
    }
}
',
            $this->subject->getClassContent()
        );
    }

    public function testGetFactoryContent(): void
    {
        Assert::assertSame(
            '<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\MyNewComponent;

/*
 * This file is part of the Vendor.Site package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;

final class MyNewComponentFactory extends AbstractComponentPresentationObjectFactory
{
}
',
            $this->subject->getFactoryContent()
        );
    }

    public function testGetFusionContent(): void
    {
        Assert::assertSame(
            'prototype(Vendor.Site:Component.MyNewComponent) < prototype(PackageFactory.AtomicFusion.PresentationObjects:PresentationObjectComponent) {
    @presentationObjectInterface = \'Vendor\\\\Site\\\\Presentation\\\\Component\\\\MyNewComponent\\\\MyNewComponentInterface\'

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
        }
    }

    renderer = afx`<dl>
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
            <Sitegeist.Lazybones:Image imageSource={presentationObject.image} />
        </dd>
        <dt>nullableImage:</dt>
        <dd>
            <Sitegeist.Lazybones:Image imageSource={presentationObject.nullableImage} @if.isToBeRendered={presentationObject.nullableImage} />
        </dd>
        <dt>subComponent:</dt>
        <dd>
            <Vendor.Site:Component.MyComponent presentationObject={presentationObject.subComponent} />
        </dd>
        <dt>nullableSubComponent:</dt>
        <dd>
            <Vendor.Site:Component.MyComponent presentationObject={presentationObject.nullableSubComponent} @if.isToBeRendered={presentationObject.nullableSubComponent} />
        </dd>
        <dt>componentArray:</dt>
        <dd>
            <Neos.Fusion:Loop items={presentationObject.componentArray}>
                <Vendor.Site:Component.MyComponent presentationObject={item} />
            </Neos.Fusion:Loop>
        </dd>
    </dl>`
}
',
            $this->subject->getFusionContent()
        );
    }

    public function testGetComponentArrayContent(): void
    {
        Assert::assertSame(
            '<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\MyNewComponent;

/*
 * This file is part of the Vendor.Site package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class MyNewComponents implements \IteratorAggregate
{
    /**
     * @var array<int,MyNewComponentInterface>|MyNewComponentInterface[]
     */
    private array $myNewComponents;

    public function __construct($array)
    {
        foreach ($array as $element) {
            if (!$element instanceof MyNewComponentInterface) {
                throw new \InvalidArgumentException(self::class . \' can only consist of \' . MyNewComponentInterface::class);
            }
        }
        $this->myNewComponents = $array;
    }

    /**
     * @return \ArrayIterator<int,MyNewComponentInterface>|MyNewComponentInterface[]
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->myNewComponents);
    }
}
',
            $this->subject->getComponentArrayContent()
        );
    }
}
