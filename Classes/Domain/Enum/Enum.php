<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;

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
     * @var null|string[]|int[]
     */
    private ?array $values;

    /**
     * @param EnumName $name
     * @param EnumType $type
     * @param null|string[]|int[] $values
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
        return '<?php declare(strict_types=1);
namespace ' . $this->name->getPhpNamespace() . ';

/*
 * This file is part of the ' . $this->name->getPackageKey() . ' package.
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * @Flow\Proxy(false)
 */
final class ' . $this->name->getName() . ' implements PseudoEnumInterface
{
    ' . $this->renderConstants() . '

    /**
     * @var array<' . $this->type . ',self>|self[]
     */
    private static array $instances;

    private ' . $this->type . ' $value;

    private function __construct(' . $this->type . ' $value)
    {
        $this->value = $value;
    }

    public static function from(' . $this->type . ' ' . $variable . '): self
    {
        if (!isset(self::$instances[' . $variable . '])) {
            ' . $this->renderValidation() . '
            self::$instances[' . $variable . '] = new self(' . $variable . ');
        }

        return self::$instances[' . $variable . '];
    }

    ' . $this->renderNamedConstructors() . '

    ' . $this->renderComparators() . '

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            ' . $this->renderCases() .'
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
        return '<?php declare(strict_types=1);
namespace ' . $this->name->getPhpNamespace() . ';

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
    private function renderValidation(): string
    {
        if (is_array($this->values)) {
            $variable = '$' . $this->type;
            $caseChecks = [];
            foreach ($this->values as $name => $value) {
                $caseChecks[] = $variable . ' !== self::' . $this->getConstantName($name);
            }

            return 'if (' . implode("\n                && ", $caseChecks) . ') {
                throw ' . $this->name->getExceptionName() . '::becauseItMustBeOneOfTheDefinedConstants(' . $variable . ');
            }';
        }

        return '';
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
        return self::from(self::' . $this->getConstantName($name) . ');
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
    public function renderCases(): string
    {
        $cases = [];

        if (is_array($this->values)) {
            foreach ($this->values as $name => $value) {
                $cases[] = 'self::from(self::' . $this->getConstantName($name) . '),';
            }
        }

        return trim(trim(implode("\n            ", $cases)), ',');
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
