<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PluralName;

/**
 * @Flow\Proxy(false)
 */
final class Enum
{
    /**
     * @var EnumName
     */
    private EnumName $name;

    /**
     * @var EnumType
     */
    private EnumType $type;

    /**
     * @var null|string[]|int[]|float[]
     */
    private ?array $values;

    /**
     * @param EnumName $name
     * @param EnumType $type
     * @param null|string[]|int[]|float[] $values
     */
    public function __construct(EnumName $name, EnumType $type, ?array $values)
    {
        $this->name = $name;
        $this->type = $type;
        $this->values = $values;
    }

    /**
     * @return string
     */
    public function getClassContent(): string
    {
        $variable = '$' . $this->type;
        return '<?php
namespace ' . $this->name->getNamespace() . ';

/*
 * This file is part of the ' . $this->name->getPackageKey() . ' package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\EnumInterface

/**
 * @Flow\Proxy(false)
 */
final class ' . $this->name->getName() . ' implements EnumInterface
{
    ' . $this->renderConstants() . '

    private ' . $this->type . ' $value;

    private function __construct(' . $this->type . ' $value)
    {
        $this->value = $value;
    }

    public static function from' . ucfirst((string)$this->type) . '(' . $this->type . ' ' . $variable . '): self
    {
        if (!in_array(' . $variable . ', self::getValues())) {
            throw ' . $this->name->getExceptionName() . '::becauseItMustBeOneOfTheDefinedConstants(' . $variable . ');
        }

        return new self(' . $variable . ');
    }

    ' . $this->renderNamedConstructors() . '

    ' . $this->renderComparators() . '

    /**
     * @return array|' . $this->type . '[]
     */
    public static function getValues(): array
    {
        return [
            ' . $this->renderValues() .'
        ];
    }

    public function getValue(): ' . $this->type . '
    {
        return $this->value;
    }' . ($this->type->isString() ? '

    public function __toString(): string
    {
        return $this->value;
    }' : '') .'
}
';
    }

    /**
     * @param \DateTimeImmutable $now
     * @return string
     */
    public function getExceptionContent(\DateTimeImmutable $now): string
    {
        return '<?php
namespace ' . $this->name->getNamespace() . ';

/*
 * This file is part of the ' . $this->name->getPackageKey() . ' package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class ' . $this->name->getExceptionName() . ' extends \DomainException
{
    public static function becauseItMustBeOneOfTheDefinedConstants(' . $this->type . ' $attemptedValue): self
    {
        return new self(\'The given value "\' . $attemptedValue . \'" is no valid ' . $this->name->getName() . ', must be one of the defined constants. \', ' . $now->getTimestamp() . ');
    }
}
';
    }

    /**
     * @return string
     */
    public function getProviderContent(): string
    {
        $arrayName = lcfirst(PluralName::forName($this->name->getName()));
        return '<?php
namespace ' . $this->name->getProviderNamespace() . ';

/*
 * This file is part of the ' . $this->name->getPackageKey() . ' package.
 */

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Translator;
use Neos\Neos\Service\DataSource\AbstractDataSource;
use Neos\Eel\ProtectedContextAwareInterface;
use ' . $this->name->getFullyQualifiedName() . ';

class ' . $this->name->getProviderName() . ' extends AbstractDataSource implements ProtectedContextAwareInterface
{
    /**
     * @Flow\Inject
     * @var Translator
     */
    protected $translator;

    /**
     * @var string
     */
    protected static $identifier = \'' . $this->name->getDataSourceIdentifier() . '\';

    public function getData(NodeInterface $node = null, array $arguments = []): array
    {
        $' . $arrayName . ' = [];
        foreach (' . $this->name->getName() . '::getValues() as $value) {
            $' . $arrayName . '[$value][\'label\'] = $this->translator->translateById(
                \'' . lcfirst($this->name->getName()) . '.\' . $value,
                [],
                null,
                null,
                \'' . $this->name->getComponentName() . '\',
                \'' . $this->name->getPackageKey() . '\'
            ) ?: $value;
        }

        return $' . $arrayName . ';
    }

    /**
     * @return array|' . $this->type . '[]
     */
    public function getValues(): array
    {
        return ' . $this->name->getName() . '::getValues();
    }

    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }
}
';
    }

    /**
     * @return string
     */
    private function renderConstants(): string
    {
        $constants = [];
        if (is_array($this->values)) {
            foreach ($this->values as $name => $value) {
                $renderedValue = $this->type->isString()
                    ? '\'' . $value . '\''
                    : $value;
                $constants[] = 'const ' . $this->getConstantName($name) . ' = ' . $renderedValue . ';';
            }
        }

        return trim(implode("\n    ", $constants));
    }

    /**
     * @return string
     */
    private function renderNamedConstructors(): string
    {
        $constructors = [];
        if (is_array($this->values)) {
            foreach ($this->values as $name => $value) {
                $constructors[]  = 'public static function ' . $name . '(): self
    {
        return new self(self::' . $this->getConstantName($name) . ');
    }';
            }
        }

        return trim(implode("\n\n    ", $constructors));
    }

    /**
     * @return string
     */
    private function renderComparators(): string
    {
        $comparators = [];
        if (is_array($this->values)) {
            foreach ($this->values as $name => $value) {
                $comparators[]  = 'public function getIs' . ucfirst($name) . '(): bool
    {
        return $this->value === self::' . $this->getConstantName($name) . ';
    }';
            }
        }

        return trim(implode("\n\n    ", $comparators));
    }

    /**
     * @return string
     */
    public function renderValues(): string
    {
        $values = [];

        if (is_array($this->values)) {
            foreach ($this->values as $name => $value) {
                $values[] = 'self::' . $this->getConstantName($name) . ',';
            }
        }

        return trim(trim(implode("\n            ", $values)), ',');
    }

    /**
     * @param string $value
     * @return string
     */
    private function getConstantName(string $value): string
    {
        $parts = $this->splitName();
        if (count($parts) > 1) {
            return strtoupper(end($parts) . '_' . $value);
        }

        return 'VALUE_' . strtoupper($value);
    }

    /**
     * @return string[]
     */
    private function splitName(): array
    {
        $nameParts = [];
        $parts = preg_split("/([A-Z])/", $this->name->getName(), -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        if (is_array($parts)) {
            foreach ($parts as $i => $part) {
                if ($i % 2 === 0) {
                    $nameParts[$i / 2] = $part;
                } else {
                    $nameParts[($i - 1) / 2] .= $part;
                }
            }
        }

        return $nameParts;
    }
}
