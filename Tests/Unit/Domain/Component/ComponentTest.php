<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component;

use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\Component;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropTypeClass;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropTypeIdentifier;
use PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Helper\DummyPropTypeRepository;
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

        $propTypeRepository = new DummyPropTypeRepository();
        $propTypeRepository->propTypeIdentifiers['Acme.Site']['MySubComponent'] = [
            'MySubComponent' => new PropTypeIdentifier('MySubComponent', 'MySubComponentInterface', 'Acme\Site\Presentation\MySubComponent\MySubComponentInterface', false, PropTypeClass::leaf()),
            '?MySubComponent' => new PropTypeIdentifier('MySubComponent', 'MySubComponentInterface', 'Acme\Site\Presentation\MySubComponent\MySubComponentInterface', true, PropTypeClass::leaf())
        ];

        $this->subject = Component::fromInput(
            'Acme.Site',
            'MyComponent',
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
                'subComponent:MySubComponent',
                'nullableSubComponent:?MySubComponent'
            ],
            $propTypeRepository
        );
    }

    public function testGetInterfaceContent(): void
    {
        Assert::assertSame(
            '<?php
namespace Acme\Site\Presentation\MyComponent;

/*
 * This file is part of the Acme.Site package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use Psr\Http\Message\UriInterface;
use Sitegeist\Kaleidoscope\EelHelpers\ImageSourceHelperInterface;
use Acme\Site\Presentation\MySubComponent\MySubComponentInterface;

interface MyComponentInterface extends ComponentPresentationObjectInterface
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

    public function getSubComponent(): MySubComponentInterface;

    public function getNullableSubComponent(): ?MySubComponentInterface;
}
',
            $this->subject->getInterfaceContent()
        );
    }

    public function testGetClassContent(): void
    {
        Assert::assertSame(
            '<?php
namespace Acme\Site\Presentation\MyComponent;

/*
 * This file is part of the Acme.Site package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Psr\Http\Message\UriInterface;
use Sitegeist\Kaleidoscope\EelHelpers\ImageSourceHelperInterface;
use Acme\Site\Presentation\MySubComponent\MySubComponentInterface;

/**
 * @Flow\Proxy(false)
 */
final class MyComponent extends AbstractComponentPresentationObject implements MyComponentInterface
{
    /**
     * @var bool
     */
    private $bool;

    /**
     * @var bool|null
     */
    private $nullableBool;

    /**
     * @var float
     */
    private $float;

    /**
     * @var float|null
     */
    private $nullableFloat;

    /**
     * @var int
     */
    private $int;

    /**
     * @var int|null
     */
    private $nullableInt;

    /**
     * @var string
     */
    private $string;

    /**
     * @var string|null
     */
    private $nullableString;

    /**
     * @var UriInterface
     */
    private $uri;

    /**
     * @var UriInterface|null
     */
    private $nullableUri;

    /**
     * @var ImageSourceHelperInterface
     */
    private $image;

    /**
     * @var ImageSourceHelperInterface|null
     */
    private $nullableImage;

    /**
     * @var MySubComponentInterface
     */
    private $subComponent;

    /**
     * @var MySubComponentInterface|null
     */
    private $nullableSubComponent;

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
        MySubComponentInterface $subComponent,
        ?MySubComponentInterface $nullableSubComponent
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

    public function getSubComponent(): MySubComponentInterface
    {
        return $this->subComponent;
    }

    public function getNullableSubComponent(): ?MySubComponentInterface
    {
        return $this->nullableSubComponent;
    }
}
',
            $this->subject->getClassContent()
        );
    }

    public function testGetFactoryContent(): void
    {
        Assert::assertSame(
            '<?php
namespace Acme\Site\Presentation\MyComponent;

/*
 * This file is part of the Acme.Site package.
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;

final class MyComponentFactory extends AbstractComponentPresentationObjectFactory
{
}
',
            $this->subject->getFactoryContent()
        );
    }

    public function testGetFusionContent(): void
    {
        Assert::assertSame(
            'prototype(Acme.Site:Composite.MyComponent) < prototype(PackageFactory.AtomicFusion.PresentationObjects:PresentationObjectComponent) {
    @presentationObjectInterface = \'Acme\\\\Site\\\\Presentation\\\\MyComponent\\\\MyComponentInterface\'

    @styleguide {
        title = \'MyComponent\'

        props {
            bool = true
            nullableBool = true
            float = 47.11
            nullableFloat = 47.11
            int = 4711
            nullableInt = 4711
            string = \'Text\'
            nullableString = \'Text\'
            uri = \'https://neos.io\'
            nullableUri = \'https://neos.io\'
            image = Sitegeist.Kaleidoscope:DummyImageSource {
                height = 1920
                width = 1080
            }
            nullableImage = Sitegeist.Kaleidoscope:DummyImageSource {
                height = 1920
                width = 1080
            }
            subComponent {
            }
            nullableSubComponent {
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
        <dd><Sitegeist.Lazybones:Image imageSource={presentationObject.image} /></dd>
        <dt>nullableImage:</dt>
        <dd><Sitegeist.Lazybones:Image imageSource={presentationObject.nullableImage} @if.isToBeRendered={presentationObject.nullableImage} /></dd>
        <dt>subComponent:</dt>
        <dd><Acme.Site:Leaf.MySubComponent presentationObject={presentationObject.subComponent} /></dd>
        <dt>nullableSubComponent:</dt>
        <dd><Acme.Site:Leaf.MySubComponent presentationObject={presentationObject.nullableSubComponent} @if.isToBeRendered={presentationObject.nullableSubComponent} /></dd>
    </dl>`
}
',
            $this->subject->getFusionContent()
        );
    }
}
