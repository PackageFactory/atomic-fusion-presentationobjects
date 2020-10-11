<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

/**
 * The interface to be implemented by prop type repositories
 */
interface PropTypeRepositoryInterface
{
    public function findByType(?string $packageKey, ?string $componentName, string $type): ?PropType;

    public function findPropTypeIdentifier(
        string $packageKey,
        string $componentName,
        string $type
    ): ?PropTypeIdentifier;

    public function knowsByType(string $packageKey, string $componentName, string $type): bool;
}
