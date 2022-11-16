<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain;

use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\Component;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentName;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\DummyFactoryRenderer;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\FusionNamespace;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageKey;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\Props;
use PHPUnit\Framework\Assert;

/**
 * Test cases for DummyFactoryRenderer
 */
class DummyFactoryRendererTest extends UnitTestCase
{
    private ?Component $component = null;

    public function setUp(): void
    {
        parent::setUp();

        $componentName = new ComponentName(
            new PackageKey('Vendor.Site'),
            FusionNamespace::default(),
            'MyNewComponent',
        );
        $this->component = new Component(
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

    public function testGetFactoryContent(): void
    {
        Assert::assertSame(
            '<?php

/*
 * This file is part of the Vendor.Site package.
 */

declare(strict_types=1);

namespace Vendor\Site\Presentation\Component\MyNewComponent;

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;

final class MyNewComponentFactory extends AbstractComponentPresentationObjectFactory
{
}
',
            (new DummyFactoryRenderer())->renderFactoryContent($this->component)
        );
    }
}
