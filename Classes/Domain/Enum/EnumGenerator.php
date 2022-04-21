<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum;

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentName;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\FileWriterInterface;

/**
 * The enum generator domain service
 */
#[Flow\Proxy(false)]
final class EnumGenerator
{
    public function __construct(
        private FileWriterInterface $fileWriter
    ) {
    }

    /**
     * @param array<string> $values
     */
    public function generateEnum(
        ComponentName $componentName,
        string $name,
        string $type,
        array $values,
        string $packagePath,
        bool $colocate
    ): void {
        $enumType = EnumType::from($type);
        $enumName = new EnumName(
            $componentName,
            $name
        );
        $enum = new Enum($enumName, $enumType, $enumType->processValueArray($values));

        $this->fileWriter->writeFile(
            $enumName->getClassPath($packagePath, $colocate),
            $enum->getClassContent()
        );
    }
}
