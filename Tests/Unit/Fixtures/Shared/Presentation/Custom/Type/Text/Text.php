<?php declare(strict_types=1);
namespace Vendor\Shared\Presentation\Custom\Type\Text;

/*
 * This file is part of the Vendor.Shared package
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Neos\Flow\Annotations as Flow;

/**
 * Text component for test purposes
 * @Flow\Proxy(false)
 */
final class Text extends AbstractComponentPresentationObject implements TextInterface
{
    private string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
