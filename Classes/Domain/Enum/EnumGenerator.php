<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\ComponentName;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\FileWriterInterface;

/**
 * The enum generator domain service
 *
 * @Flow\Proxy(false)
 */
final class EnumGenerator
{
    protected \DateTimeImmutable $now;

    private FileWriterInterface $fileWriter;

    public function __construct(?\DateTimeImmutable $now, FileWriterInterface $fileWriter)
    {
        $this->now = $now ?? new \DateTimeImmutable();
        $this->fileWriter = $fileWriter;
    }

    /**
     * @param ComponentName $componentName
     * @param string $name
     * @param string $type
     * @param array|string[] $values
     * @param string $packagePath
     * @param bool $colocate
     * @return void
     */
    public function generateEnum(
        ComponentName $componentName,
        string $name,
        string $type,
        array $values,
        string $packagePath,
        bool $colocate
    ): void {
        $enumType = EnumType::fromInput($type);
        $enumName = new EnumName(
            $componentName,
            $name
        );
        $enum = new Enum($enumName, $enumType, $enumType->processValueArray($values));

        $this->fileWriter->writeFile($enumName->getClassPath($packagePath, $colocate), $enum->getClassContent());
        $this->fileWriter->writeFile($enumName->getExceptionPath($packagePath, $colocate), $enum->getExceptionContent($this->now));
    }
}
