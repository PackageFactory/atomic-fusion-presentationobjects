<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Fusion;

use Neos\ContentRepository\Core\Projection\ContentGraph\ContentSubgraphInterface;
use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use Neos\Flow\Mvc\Controller\ControllerContext;
use Neos\Flow\ResourceManagement\PersistentResource;
use Neos\Media\Domain\Model\AssetInterface;
use Psr\Http\Message\UriInterface;

interface UriServiceInterface
{
    public function getNodeUri(
        Node $documentNode,
        bool $absolute = false,
        ?string $format = null
    ): UriInterface;

    public function useControllerContext(ControllerContext $controllerContext): void;

    public function getResourceUri(string $packageKey, string $resourcePath): UriInterface;

    public function getPersistentResourceUri(PersistentResource $resource): ?UriInterface;

    public function getAssetUri(AssetInterface $asset): UriInterface;

    public function getDummyImageBaseUri(): UriInterface;

    public function getControllerContext(): ControllerContext;

    public function resolveLinkUri(string $rawLinkUri, ContentSubgraphInterface $subgraph): UriInterface;
}
