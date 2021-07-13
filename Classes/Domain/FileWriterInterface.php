<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

interface FileWriterInterface
{
    /**
     * @param string $filePath
     * @param string $fileContents
     * @return void
     */
    public function writeFile(string $filePath, string $fileContents): void;
}
