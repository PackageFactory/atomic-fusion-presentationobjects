<?php
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component;

use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\Component;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropTypeRepository;
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
                'nullableString:?string'
            ],
            new PropTypeRepository()
        );
    }

    public function testGetInterfaceContent(): void
    {
        Assert::assertSame('<?php
namespace Acme\Site\Presentation\MyComponent;

/*
 * This file is part of the Acme.Site package.
 */

interface MyComponentInterface
{
    public function getBool(): bool;

    public function getNullableBool(): ?bool;

    public function getFloat(): float;

    public function getNullableFloat(): ?float;

    public function getInt(): int;

    public function getNullableInt(): ?int;

    public function getString(): string;

    public function getNullableString(): ?string;
}
',
            $this->subject->getInterfaceContent()
        );
    }

    public function testGetClassContent(): void
    {
        Assert::assertSame('<?php
namespace Acme\Site\Presentation\MyComponent;

/*
 * This file is part of the Acme.Site package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

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

    public function __construct(
        bool $bool,
        ?bool $nullableBool,
        float $float,
        ?float $nullableFloat,
        int $int,
        ?int $nullableInt,
        string $string,
        ?string $nullableString
    ) {
        $this->bool = $bool;
        $this->nullableBool = $nullableBool;
        $this->float = $float;
        $this->nullableFloat = $nullableFloat;
        $this->int = $int;
        $this->nullableInt = $nullableInt;
        $this->string = $string;
        $this->nullableString = $nullableString;
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
}
',
            $this->subject->getClassContent()
        );
    }

    public function testGetFactoryContent(): void
    {
        Assert::assertSame('<?php
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
        Assert::assertSame('prototype(Acme.Site:Component.MyComponent) < prototype(PackageFactory.AtomicFusion.PresentationObjects:PresentationObjectComponent) {
    @presentationObjectInterface = \'Acme\\Site\\Presentation\\MyComponent\\MyComponentInterface\'

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
    </dl>`
}
',
            $this->subject->getFusionContent()
        );
    }
}
