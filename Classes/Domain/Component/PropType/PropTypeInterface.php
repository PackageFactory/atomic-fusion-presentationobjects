<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

interface PropTypeInterface
{
    public function isNullable(): bool;

    public function getSimpleName(): string;

    public function getUseStatement(): string;

    public function getType(): string;

    public function getStyleGuideValue(int $nestingLevel = 0): string;

    public function getDefinitionData(string $propName): string;
}
