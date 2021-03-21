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
use PackageFactory\AtomicFusion\PresentationObjects\Domain\AbstractImmutableArrayObject;

/**
 * @Flow\Proxy(false)
 * @extends AbstractImmutableArrayObject<string,PropTypeInterface>
 */
final class Props extends AbstractImmutableArrayObject
{
    /**
     * @param array<string,PropTypeInterface> $array
     */
    private function __construct(array $array)
    {
        parent::__construct($array);
    }

    /**
     * @param string $packageKey
     * @param string $componentName
     * @param string[] $input
     * @return self
     * @throws PropTypeIsInvalid
     */
    public static function fromInputArray(string $packageKey, string $componentName, array $input): self
    {
        $props = [];
        foreach ($input as $serializedProp) {
            list($propName, $serializedPropType) = explode(':', $serializedProp);
            $props[$propName] = PropTypeFactory::fromInputString($packageKey, $componentName, $serializedPropType);
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
        $props = [];
        $reflection = new \ReflectionClass($className);
        foreach ($reflection->getProperties() as $reflectionProperty) {
            $props[$reflectionProperty->getName()] = PropTypeFactory::fromReflectionProperty($reflectionProperty);
        }

        return new self($props);
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
     * @param string $key
     * @return PropTypeInterface|false
     */
    public function offsetGet($key)
    {
        return parent::offsetGet($key) ?: false;
    }

    /**
     * @return array|PropTypeInterface[]
     */
    public function getArrayCopy(): array
    {
        return parent::getArrayCopy();
    }

    /**
     * @return \ArrayIterator<string,PropTypeInterface>|PropTypeInterface[]
     */
    public function getIterator()
    {
        return parent::getIterator();
    }
}
