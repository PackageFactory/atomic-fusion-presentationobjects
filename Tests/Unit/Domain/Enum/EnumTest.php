<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Enum;

use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentName;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\Enum;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\EnumName;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\EnumType;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\FusionNamespace;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\PackageKey;
use PHPUnit\Framework\Assert;

/**
 * Test cases for Enum
 */
class EnumTest extends UnitTestCase
{
    /**
     * @var Enum
     */
    private $subject;

    public function setUp(): void
    {
        parent::setUp();

        $this->subject = new Enum(
            new EnumName(
                new ComponentName(new PackageKey('Vendor.Site'), FusionNamespace::default(), 'MyComponent'),
                'MyComponentType'
            ),
            EnumType::TYPE_STRING,
            [
                'primary' => 'primary',
                'secondary' => 'secondary',
                'yetAnother' => 'yetAnother'
            ]
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

namespace Vendor\Site\Presentation\Component\MyComponent;

use Neos\Eel\ProtectedContextAwareInterface;

enum MyComponentType:string implements ProtectedContextAwareInterface
{
    case TYPE_PRIMARY = \'primary\';
    case TYPE_SECONDARY = \'secondary\';
    case TYPE_YET_ANOTHER = \'yetAnother\';

    public function equals(string $other): bool
    {
        return $this === self::from($other);
    }

    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }
}
',
            $this->subject->getClassContent()
        );
    }
}
