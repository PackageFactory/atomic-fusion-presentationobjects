<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use GuzzleHttp\Psr7\Uri;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentName;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use Psr\Http\Message\UriInterface;
use Sitegeist\Kaleidoscope\EelHelpers\ImageSourceHelperInterface;

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

                if (IsComponent::isSatisfiedByInterfaceName($componentName->getFullyQualifiedInterfaceName())) {
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
            $type = (string) $type;
            switch ($type) {
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
                case ImageSourceHelperInterface::class:
                    return new ImageSourcePropType($nullable);
                case SlotInterface::class:
                    return new SlotPropType($nullable);
                default:
                    if (IsEnum::isSatisfiedByClassName($type)) {
                        /** @phpstan-var class-string<mixed> $type */
                        return new EnumPropType($type, $nullable);
                    }
                    if (IsComponent::isSatisfiedByInterfaceName($type)) {
                        /** @phpstan-var class-string<mixed> $type */
                        $componentName = ComponentName::fromClassName($type);
                        return new ComponentPropType($componentName, $nullable);
                    }
                    if (IsComponentArray::isSatisfiedByClassName($type)) {
                        /** @phpstan-var class-string<mixed> $type */
                        $componentName = ComponentName::fromClassName($type);
                        return new ComponentArrayPropType($componentName);
                    }
            }

            throw PropTypeIsInvalid::becauseItIsNoKnownComponentValueOrPrimitive($type);
        }

        throw PropTypeIsInvalid::becausePropertyIsNotTyped($property);
    }
}
