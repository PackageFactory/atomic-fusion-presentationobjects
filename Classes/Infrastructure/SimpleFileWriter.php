<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Infrastructure;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

use Neos\Utility\Files;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\FileWriterInterface;

final class SimpleFileWriter implements FileWriterInterface
{
    public function writeFile(string $filePath, string $fileContents): void
    {
        $dirname = dirname($filePath);

        if (!file_exists($dirname)) {
            Files::createDirectoryRecursively($dirname);
        }

        file_put_contents($filePath, $fileContents);
    }
}
