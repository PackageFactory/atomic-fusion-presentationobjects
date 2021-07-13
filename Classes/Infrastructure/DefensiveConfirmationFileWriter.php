<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Infrastructure;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

use Neos\Flow\Cli\ConsoleOutput;
use Neos\Utility\Files;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\FileWriterInterface;

final class DefensiveConfirmationFileWriter implements FileWriterInterface
{
    private ConsoleOutput $output;

    private bool $assumeYes;

    public function __construct(ConsoleOutput $output, bool $assumeYes)
    {
        $this->output = $output;
        $this->assumeYes = $assumeYes;
    }

    public function writeFile(string $filePath, string $fileContents): void
    {
        $dirname = dirname($filePath);

        if (!file_exists($dirname)) {
            Files::createDirectoryRecursively($dirname);
        }

        if (!is_dir($dirname)) {
            $this->output->outputLine(
                '<error>Could not write file: %s</error>',
                [$filePath]
            );
            $this->output->outputLine(
                '"%s" is not a directory!',
                [$dirname]
            );
            return;
        }

        if (file_exists($filePath) && !$this->assumeYes) {
            $this->output->output(
                'Overwrite <b>"%s"</b>? (y/N)',
                [$this->localizeFilePath($filePath)]
            );

            if (!$this->output->askConfirmation(' ', false)) {
                return;
            }
        }

        file_put_contents($filePath, $fileContents);
        $this->output->outputLine(
            'File <success><b>"%s"</b></success> was written.',
            [$this->localizeFilePath($filePath)]
        );
    }

    protected function localizeFilePath(string $filePath): string
    {
        $localizedPath = $filePath;
        if (\mb_substr($localizedPath, 0, \mb_strlen(FLOW_PATH_ROOT)) == FLOW_PATH_ROOT) {
            $localizedPath = substr($localizedPath, strlen(FLOW_PATH_ROOT));
        }

        return implode('/', array_filter(explode('/', $localizedPath)));
    }
}
