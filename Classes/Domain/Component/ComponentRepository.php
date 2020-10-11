<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;

/**
 * The repository for all supported components
 *
 * @Flow\Scope("singleton")
 */
final class ComponentRepository
{
    /**
     * @phpstan-param class-string $interfaceName
     * @param string $interfaceName
     * @return ComponentType
     */
    public function getComponentType(string $interfaceName): ComponentType
    {
        $reflection = new \ReflectionClass($interfaceName);
        foreach ($reflection->getMethods() as $method) {
            if (\mb_strpos($method->getName(), 'get') === 0 && interface_exists((string) $method->getReturnType())) {
                /** @phpstan-var class-string $getterReturnTypeName */
                $getterReturnTypeName = (string) $method->getReturnType();
                $reflection = new \ReflectionClass($getterReturnTypeName);

                if (in_array(ComponentPresentationObjectInterface::class, $reflection->getInterfaceNames())) {
                    return ComponentType::composite();
                }
            }
        }

        return ComponentType::leaf();
    }
}
