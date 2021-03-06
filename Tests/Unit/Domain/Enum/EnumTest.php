<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Enum;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

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
            EnumType::string(),
            [
                'primary' => 'primary',
                'secondary' => 'secondary'
            ]
        );
    }

    public function testGetClassContent(): void
    {
        Assert::assertSame(
            '<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\MyComponent;

/*
 * This file is part of the Vendor.Site package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class MyComponentType implements PseudoEnumInterface
{
    const TYPE_PRIMARY = \'primary\';
    const TYPE_SECONDARY = \'secondary\';

    /**
     * @var array<string,self>|self[]
     */
    private static array $instances;

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function from(string $string): self
    {
        if (!isset(self::$instances[$string])) {
            if ($string !== self::TYPE_PRIMARY
                && $string !== self::TYPE_SECONDARY) {
                throw MyComponentTypeIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function primary(): self
    {
        return self::from(self::TYPE_PRIMARY);
    }

    public static function secondary(): self
    {
        return self::from(self::TYPE_SECONDARY);
    }

    public function getIsPrimary(): bool
    {
        return $this->value === self::TYPE_PRIMARY;
    }

    public function getIsSecondary(): bool
    {
        return $this->value === self::TYPE_SECONDARY;
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::TYPE_PRIMARY),
            self::from(self::TYPE_SECONDARY)
        ];
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
',
            $this->subject->getClassContent()
        );
    }

    public function testGetExceptionContent(): void
    {
        Assert::assertSame(
            '<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\MyComponent;

/*
 * This file is part of the Vendor.Site package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class MyComponentTypeIsInvalid extends \DomainException
{
    public static function becauseItMustBeOneOfTheDefinedConstants(string $attemptedValue): self
    {
        return new self(\'The given value "\' . $attemptedValue . \'" is no valid MyComponentType, must be one of the defined constants. \', 1602424261);
    }
}
',
            $this->subject->getExceptionContent(new \DateTimeImmutable('@1602424261'))
        );
    }
}
