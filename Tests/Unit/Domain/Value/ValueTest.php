<?php
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Value;

use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Value\Value;
use PHPUnit\Framework\Assert;

/**
 * Test cases for Component
 */
class ValueTest extends UnitTestCase
{
    /**
     * @var Value
     */
    private $subject;

    public function setUp(): void
    {
        parent::setUp();

        $this->subject = new Value(
            'Acme.Site',
            'MyComponent',
            'MyComponentType',
            'string',
            [
                'primary',
                'secondary'
            ]
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

/**
 * @Flow\Proxy(false)
 */
final class MyComponentType
{
    const TYPE_PRIMARY = \'primary\';
    const TYPE_SECONDARY = \'secondary\';

    /**
     * @var string
     */
    private $value;

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
        Assert::assertSame('<?php
namespace Acme\Site\Presentation\MyComponent;

/*
 * This file is part of the Acme.Site package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class MyComponentTypeIsInvalid extends \DomainException
{
    public static function becauseItMustBeOneOfTheDefinedConstants(string $attemptedValue): self
    {
        return new self(\'The given value "\' . $attemptedValue . \'" is no valid MyComponentType, must be one of the defined constants. \', ' . time() . ');
    }
}
',
            $this->subject->getExceptionContent()
        );
    }

    public function testGetProviderContent(): void
    {
        Assert::assertSame('<?php
namespace Acme\Site\Application;

/*
 * This file is part of the Acme.Site package.
 */

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Translator;
use Neos\Neos\Service\DataSource\AbstractDataSource;
use Neos\Eel\ProtectedContextAwareInterface;
use Acme\Site\Presentation\MyComponent\MyComponentType;

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
    protected static $identifier = \'acme-site-my-component-types\';

    public function getData(NodeInterface $node = null, array $arguments = []): array
    {
        $myComponentTypes = [];
        foreach (MyComponentType::getValues() as $value) {
            $myComponentTypes[$value][\'label\'] = $this->translator->translateById(\'myComponentType.\' . $value, [], null, null, \'MyComponent\', \'Acme.Site\') ?: $value;
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
            $this->subject->getProviderContent());
    }
}
