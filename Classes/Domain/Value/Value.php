<?php
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Value;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;

/**
 * @deprecated 2.0
 * @Flow\Proxy(false)
 */
final class Value
{
    /**
     * @var string
     */
    private $packageKey;

    /**
     * @var string
     */
    private $componentName;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array|null
     */
    private $values;

    public function __construct(string $packageKey, string $componentName, string $name, string $type, ?array $values)
    {
        $this->packageKey = $packageKey;
        $this->componentName = $componentName;
        $this->name = $name;
        if ($type !== 'string' && $type !== 'int') {
            throw new \InvalidArgumentException('Only values of type string or int are supported at this point.', 1582502049);
        }
        $this->type = $type;
        $this->values = $values;
    }

    public function getPackageKey(): string
    {
        return $this->packageKey;
    }

    public function getComponentName(): string
    {
        return $this->componentName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getValues(): ?array
    {
        return $this->values;
    }

    public function getClassPath(string $packagePath): string
    {
        return $packagePath . 'Classes/Presentation/' . $this->componentName . '/' . $this->name . '.php';
    }

    public function getClassContent(): string
    {
        $variable = '$' . $this->type;
        return '<?php
namespace ' . $this->getNamespace() . ';

/*
 * This file is part of the ' . $this->getPackageKey() . ' package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class ' . $this->getName() . '
{
    ' . $this->renderConstants() . '

    /**
     * @var ' . $this->type . '
     */
    private $value;

    private function __construct(' . $this->type . ' $value)
    {
        $this->value = $value;
    }

    public static function from' . ucfirst($this->type) . '(' . $this->type . ' ' . $variable . '): self
    {
        if (!in_array(' . $variable . ', self::getValues())) {
            throw ' . $this->name . 'IsInvalid::becauseItMustBeOneOfTheDefinedConstants(' . $variable . ');
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
    }' . ($this->type === 'string' ? '

    public function __toString(): string
    {
        return $this->value;
    }' : '') .'
}
';
    }

    public function getExceptionPath(string $packagePath): string
    {
        return $packagePath . 'Classes/Presentation/' . $this->componentName . '/' . $this->name . 'IsInvalid.php';
    }

    public function getExceptionContent(): string
    {
        return '<?php
namespace ' . $this->getNamespace() . ';

/*
 * This file is part of the ' . $this->getPackageKey() . ' package.
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class ' . $this->getName() . 'IsInvalid extends \DomainException
{
    public static function becauseItMustBeOneOfTheDefinedConstants(' . $this->type . ' $attemptedValue): self
    {
        return new self(\'The given value "\' . $attemptedValue . \'" is no valid ' . $this->name . ', must be one of the defined constants. \', ' . time() . ');
    }
}
';
    }

    public function getProviderPath(string $packagePath): string
    {
        return $packagePath . 'Classes/Application/' . $this->name . 'Provider.php';
    }

    public function getProviderContent(): string
    {
        $arrayName = lcfirst($this->getPluralName());
        return '<?php
namespace ' . $this->getDataSourceNamespace() . ';

/*
 * This file is part of the ' . $this->packageKey . ' package.
 */

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Translator;
use Neos\Neos\Service\DataSource\AbstractDataSource;
use Neos\Eel\ProtectedContextAwareInterface;
use ' . $this->getNamespace() . '\\' . $this->name . ';

class ' . $this->name . 'Provider extends AbstractDataSource implements ProtectedContextAwareInterface
{
    /**
     * @Flow\Inject
     * @var Translator
     */
    protected $translator;

    /**
     * @var string
     */
    protected static $identifier = \'' . $this->getDataSourceIdentifier() . '\';

    public function getData(NodeInterface $node = null, array $arguments = []): array
    {
        $' . $arrayName . ' = [];
        foreach (' . $this->name . '::getValues() as $value) {
            $' . $arrayName . '[$value][\'label\'] = $this->translator->translateById(\'' . lcfirst($this->name) . '.\' . $value, [], null, null, \'' . $this->componentName . '\', \'' . $this->packageKey . '\') ?: $value;
        }

        return $' . $arrayName . ';
    }

    /**
     * @return array|' . $this->type . '[]
     */
    public function getValues(): array
    {
        return ' . $this->name . '::getValues();
    }

    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }
}
';
    }

    private function getPluralName(): string
    {
        return \mb_substr($this->name, -1) === 'y'
            ? \mb_substr($this->name, 0, \mb_strlen($this->name) - 1) . 'ies'
            : $this->name . 's';
    }

    private function getDataSourceIdentifier(): string
    {
        return strtolower(str_replace('.', '-', $this->packageKey) . '-' .  implode('-', $this->splitName(true)));
    }

    private function getDataSourceNamespace(): string
    {
        return \str_replace('.', '\\', $this->packageKey) . '\Application';
    }

    private function getNamespace(): string
    {
        return \str_replace('.', '\\', $this->packageKey) . '\Presentation\\' . $this->componentName;
    }

    private function renderConstants(): string
    {
        $constants = [];
        foreach ($this->values as $value) {
            $constants[] = 'const ' . $this->getConstantName($value) . ' = \'' . $value . '\';';
        }

        return trim(implode("\n    ", $constants));
    }

    private function renderNamedConstructors(): string
    {
        $constructors = [];
        foreach ($this->values as $value) {

            $constructors[]  = 'public static function ' . $value . '(): self
    {
        return new self(self::' . $this->getConstantName($value) . ');
    }';
        }

        return trim(implode("\n\n    ", $constructors));
    }

    private function renderComparators(): string
    {
        $comparators = [];
        foreach ($this->values as $value) {

            $comparators[]  = 'public function getIs' . ucfirst($value) . '(): bool
    {
        return $this->value === self::' . $this->getConstantName($value) . ';
    }';
        }

        return trim(implode("\n\n    ", $comparators));
    }

    public function renderValues(): string
    {
        $values = [];
        foreach ($this->values as $value) {
            $values[] = 'self::' . $this->getConstantName($value) . ',';
        }

        return trim(trim(implode("\n            ", $values)), ',');
    }

    private function getConstantName(string $value): string
    {
        $parts = $this->splitName();
        if (count($parts) > 1) {
            return strtoupper(end($parts) . '_' . $value);
        }

        return 'VALUE_' . strtoupper($value);
    }

    private function splitName(bool $plural = false): array
    {
        $name = $plural ? $this->getPluralName() : $this->name;
        $nameParts = [];
        $parts = preg_split("/([A-Z])/", $name, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        foreach ($parts as $i => $part) {
            if ($i % 2 === 0) {
                $nameParts[$i / 2] = $part;
            } else {
                $nameParts[($i - 1) / 2] .= $part;
            }
        }

        return $nameParts;
    }
}
