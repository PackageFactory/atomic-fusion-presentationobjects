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
            '<?php
namespace Vendor\Site\Presentation\Component\MyComponent;

/*
 * This file is part of the Vendor.Site package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\EnumInterface

/**
 * @Flow\Proxy(false)
 */
final class MyComponentType implements EnumInterface
{
    const TYPE_PRIMARY = \'primary\';
    const TYPE_SECONDARY = \'secondary\';

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromString(string $string): self
    {
        if (!in_array($string, self::getValues())) {
            throw MyComponentTypeIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
        }

        return new self($string);
    }

    public static function primary(): self
    {
        return new self(self::TYPE_PRIMARY);
    }

    public static function secondary(): self
    {
        return new self(self::TYPE_SECONDARY);
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
     * @return array|string[]
     */
    public static function getValues(): array
    {
        return [
            self::TYPE_PRIMARY,
            self::TYPE_SECONDARY
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
            '<?php
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

    public function testGetProviderContent(): void
    {
        Assert::assertSame(
            '<?php
namespace Vendor\Site\Application;

/*
 * This file is part of the Vendor.Site package.
 */

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Translator;
use Neos\Neos\Service\DataSource\AbstractDataSource;
use Neos\Eel\ProtectedContextAwareInterface;
use Vendor\Site\Presentation\Component\MyComponent\MyComponentType;

class MyComponentTypeProvider extends AbstractDataSource implements ProtectedContextAwareInterface
{
    /**
     * @Flow\Inject
     * @var Translator
     */
    protected $translator;

    /**
     * @var string
     */
    protected static $identifier = \'vendor-site-my-component-types\';

    public function getData(NodeInterface $node = null, array $arguments = []): array
    {
        $myComponentTypes = [];
        foreach (MyComponentType::getValues() as $value) {
            $myComponentTypes[$value][\'label\'] = $this->translator->translateById(
                \'myComponentType.\' . $value,
                [],
                null,
                null,
                \'MyComponent\',
                \'Vendor.Site\'
            ) ?: $value;
        }

        return $myComponentTypes;
    }

    /**
     * @return array|string[]
     */
    public function getValues(): array
    {
        return MyComponentType::getValues();
    }

    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }
}
',
            $this->subject->getProviderContent()
        );
    }
}
