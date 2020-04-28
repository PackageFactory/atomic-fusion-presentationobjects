<?php

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;

/**
 * The repository for all supported prop types
 *
 * @Flow\Scope("singleton")
 */
final class PropTypeRepository implements PropTypeRepositoryInterface
{
    public function findByType(?string $packageKey, ?string $componentName, string $type): ?PropType
    {
        if (!$this->knowsByType($packageKey, $componentName, $type)) {
            return null;
        }

        return PropType::create($packageKey, $componentName, $type, $this);
    }

    public function findPropTypeIdentifier(string $packageKey, string $componentName, string $type): ?PropTypeIdentifier
    {
        if (!$this->knowsByType($packageKey, $componentName, $type)) {
            return null;
        }

        $nullable = false;
        if (\mb_strpos($type, '?') === 0) {
            $nullable = true;
            $type = \mb_substr($type, 1);
        }

        if ($this->knowsPrimitive($type)) {
            return new PropTypeIdentifier($type, $type, $type, $nullable, PropTypeClass::primitive());
        }

        if ($this->knowsGlobalValue($type)) {
            $className = PropType::$globalValues[$type];
            return new PropTypeIdentifier($this->getSimpleClassName($className), $this->getSimpleClassName($className), $className, $nullable, PropTypeClass::globalValue());
        }

        if ($this->knowsValue($packageKey, $componentName, $type)) {
            $className = $this->getValueClassName($packageKey, $componentName, $type);
            return new PropTypeIdentifier($this->getSimpleClassName($className), $this->getSimpleClassName($className), $className, $nullable, PropTypeClass::value());
        }

        if ($this->knowsComponent($packageKey, $type)) {
            $interfaceName = $this->getComponentInterfaceName($packageKey, $type);
            return new PropTypeIdentifier($type, $this->getSimpleClassName($interfaceName), $interfaceName, $nullable, PropTypeClass::component());
        }

        return null;
    }

    private function getSimpleClassName(string $className): string
    {
        return \mb_substr($className, \mb_strrpos($className, '\\') + 1);
    }

    public function knowsByType(string $packageKey, string $componentName, string $type): bool
    {
        $type = trim($type, '?');

        return $this->knowsPrimitive($type)
            || $this->knowsGlobalValue($type)
            || $this->knowsValue($packageKey, $componentName, $type)
            || $this->knowsComponent($packageKey, $type);
    }

    private function knowsPrimitive(string $type): bool
    {
        return isset(PropType::$primitives[$type]);
    }

    private function knowsGlobalValue(string $type): bool
    {
        return isset(PropType::$globalValues[$type]);
    }

    private function knowsValue(string $packageKey, string $componentName, string $type): bool
    {
        return class_exists($this->getValueClassName($packageKey, $componentName, $type));
    }

    private function getValueClassName(string $packageKey, string $componentName, string $type): string
    {
        return \str_replace('.', '\\', $packageKey)
        . '\Presentation\\' . $componentName . '\\' . $type;
    }

    private function knowsComponent(string $packageKey, string $type): bool
    {
        return interface_exists($this->getComponentInterfaceName($packageKey, $type));
    }

    private function getComponentInterfaceName(string $packageKey, string $type): string
    {
        return  \str_replace('.', '\\', $packageKey)
            . '\Presentation\\' . $type . '\\' . $type . 'Interface';
    }
}
