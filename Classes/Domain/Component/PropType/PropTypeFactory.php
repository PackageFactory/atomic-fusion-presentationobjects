<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use GuzzleHttp\Psr7\Uri;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentName;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\StringLike;
use Psr\Http\Message\UriInterface;
use Sitegeist\Kaleidoscope\Domain\ImageSourceInterface;

/**
 * @Flow\Proxy(false)
 */
final class PropTypeFactory
{
    /**
     * @throws PropTypeIsInvalid
     */
    public static function fromInputString(ComponentName $parentComponentName, string $input): PropTypeInterface
    {
        $nullable = false;
        if (\mb_substr($input, 0, 1) === '?') {
            $nullable = true;
            $input = \mb_substr($input, 1);
        }
        switch ($input) {
            case 'string':
                return new StringPropType($nullable);
            case 'int':
                return new IntPropType($nullable);
            case 'float':
                return new FloatPropType($nullable);
            case 'bool':
                return new BoolPropType($nullable);
            case 'Uri':
                return new UriPropType($nullable);
            case 'ImageSource':
                return new ImageSourcePropType($nullable);
            case 'slot':
                return new SlotPropType($nullable);
            default:
                if ($isComponentArray = IsComponentArray::isSatisfiedByInputString($input)) {
                    $input = \mb_substr($input, 6, \mb_strlen($input) - 7);
                }
                $componentName = $parentComponentName->mergeInput($input);

                if (IsComponent::isSatisfiedByClassName($componentName->getFullyQualifiedClassName())) {
                    return $isComponentArray
                        ? new ComponentArrayPropType($componentName)
                        : new ComponentPropType($componentName, $nullable);
                }
                if (!$isComponentArray) {
                    $enumClassName = $componentName->getFullyQualifiedEnumName($parentComponentName);
                    if (IsEnum::isSatisfiedByClassName($enumClassName)) {
                        return new EnumPropType($enumClassName, $nullable);
                    }
                }
        }

        throw PropTypeIsInvalid::becauseItIsNoKnownComponentValueOrPrimitive($input);
    }

    public static function fromReflectionProperty(\ReflectionProperty $property): PropTypeInterface
    {
        if ($type = $property->getType()) {
            $nullable = $type->allowsNull();
            $typeString = ltrim((string)$type, '?');
            switch ($typeString) {
                case 'string':
                    return new StringPropType($nullable);
                case 'int':
                    return new IntPropType($nullable);
                case 'float':
                    return new FloatPropType($nullable);
                case 'bool':
                    return new BoolPropType($nullable);
                case UriInterface::class:
                case Uri::class:
                    return new UriPropType($nullable);
                case ImageSourceInterface::class:
                    return new ImageSourcePropType($nullable);
                case SlotInterface::class:
                    return new SlotPropType($nullable);
                case StringLike::class:
                    return new StringLikePropType($nullable);
                default:
                    if (IsEnum::isSatisfiedByClassName($typeString)) {
                        /** @phpstan-var class-string<mixed> $typeString */
                        return new EnumPropType($typeString, $nullable);
                    }
                    if (IsComponent::isSatisfiedByClassName($typeString)) {
                        /** @phpstan-var class-string<mixed> $typeString */
                        $componentName = ComponentName::fromClassName($typeString);
                        return new ComponentPropType($componentName, $nullable);
                    }
                    if (IsComponentArray::isSatisfiedByClassName($typeString)) {
                        /** @phpstan-var class-string<mixed> $typeString */
                        $componentName = ComponentName::fromClassName($typeString);
                        return new ComponentArrayPropType($componentName);
                    }
            }

            throw PropTypeIsInvalid::becauseItIsNoKnownComponentValueOrPrimitive($typeString);
        }

        throw PropTypeIsInvalid::becausePropertyIsNotTyped($property);
    }
}
