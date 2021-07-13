<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\Link;

/*
 * This file is part of the Vendor.Site package
 */

use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;
use Neos\Flow\Annotations as Flow;
use Psr\Http\Message\UriInterface;

/**
 * Link component for test purposes
 * @Flow\Proxy(false)
 */
final class Link extends AbstractComponentPresentationObject implements LinkInterface
{
    private UriInterface $uri;

    private ?string $title;

    public function __construct(
        UriInterface $uri,
        ?string $title
    ) {
        $this->uri = $uri;
        $this->title = $title;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
}
