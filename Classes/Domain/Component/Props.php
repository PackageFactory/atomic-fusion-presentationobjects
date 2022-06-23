<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\IsComponent;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\PropTypeFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\PropTypeInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\PropTypeIsInvalid;

/**
 * @Flow\Proxy(false)
 * @implements \IteratorAggregate<string,PropTypeInterface>
 */
final class Props implements \IteratorAggregate
{
    /**
     * @var array<string,PropTypeInterface>
     */
    private array $props;

    /**
     * @param array<string,PropTypeInterface> $props
     */
    public function __construct(array $props)
    {
        foreach ($props as $propName => $propType) {
            if (!is_string($propName)) {
                throw new \InvalidArgumentException('Prop names must be strings', 1656015548);
            }
            if (!$propType instanceof PropTypeInterface) {
                throw new \InvalidArgumentException('Props collections must only contain PropType objects', 1656015581);
            }
        }
        $this->props = $props;
    }

    /**
     * @param ComponentName $componentName
     * @param string[] $input
     * @return self
     * @throws PropTypeIsInvalid
     */
    public static function fromInputArray(ComponentName $componentName, array $input): self
    {
        $props = [];
        foreach ($input as $serializedProp) {
            $pivot = \mb_strpos($serializedProp, ':');
            if (is_int($pivot)) {
                $propName = \mb_substr($serializedProp, 0, $pivot);
                $serializedPropType = \mb_substr($serializedProp, $pivot + 1);
                $props[$propName] = PropTypeFactory::fromInputString($componentName, $serializedPropType);
            } else {
                throw PropsCannotBeDeserialized::becauseTheyAreNoColonList($serializedProp);
            }
        }

        return new self($props);
    }

    /**
     * @phpstan-param class-string<mixed> $className
     * @param string $className
     * @return self
     */
    public static function fromClassName(string $className): self
    {
        if (!IsComponent::isSatisfiedByClassName($className)) {
            throw PropTypeIsInvalid::becauseItIsNoKnownComponentValueOrPrimitive($className);
        }
        $reflection = new \ReflectionClass($className);
        $props = self::extractPropsFromReflectionClass($reflection);

        return new self($props);
    }

    /**
     * @return array<string,PropTypeInterface>
     */
    /** @phpstan-ignore-next-line */
    private static function extractPropsFromReflectionClass(\ReflectionClass $reflectionClass): array
    {
        $parentReflectionClass = $reflectionClass->getParentClass();
        if ($parentReflectionClass instanceof \ReflectionClass
            && IsComponent::isSatisfiedByReflectionClass($parentReflectionClass)
        ) {
            $props = self::extractPropsFromReflectionClass($parentReflectionClass);
        } else {
            $props = [];
        }

        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $props[$reflectionProperty->getName()] = PropTypeFactory::fromReflectionProperty($reflectionProperty);
        }

        return $props;
    }

    public function renderUseStatements(): string
    {
        $statements = '';

        $statedTypes = [];
        foreach ($this as $propType) {
            if (!isset($statedTypes[$propType->getSimpleName()])) {
                $statedTypes[$propType->getSimpleName()] = true;
                $statements .= $propType->getUseStatement();
            }
        }

        return $statements;
    }

    public function renderStyleGuideProps(int $nestingLevel = 0): string
    {
        $styleGuideProps = [];
        foreach ($this as $propName => $propType) {
            $styleGuideProps[] = $propName . ' ' . $propType->getStyleGuideValue($nestingLevel + 1);
        }
        $padding = self::leftPad($nestingLevel);

        return $padding . implode("\n" . $padding, $styleGuideProps);
    }

    /**
     * :D
     */
    private static function leftPad(int $nestingLevel): string
    {
        return '            ' . \str_repeat(' ', $nestingLevel  * 4);
    }

    public function renderDefinitionTerms(): string
    {
        $terms = [];
        foreach ($this as $propName => $propType) {
            $terms[] = '        <dt>' . $propName . ':</dt>
        <dd>' . $propType->getDefinitionData($propName) . '</dd>';
        }

        return trim(implode("\n", $terms));
    }

    /**
     * @return \ArrayIterator<string,PropTypeInterface>|PropTypeInterface[]
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->props);
    }
}
