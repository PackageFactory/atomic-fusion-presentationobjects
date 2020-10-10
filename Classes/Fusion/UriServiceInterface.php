<?php
namespace PackageFactory\AtomicFusion\PresentationObjects\Fusion;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Service\Context as ContentContext;
use Neos\Flow\Mvc\Controller\ControllerContext;
use Neos\Media\Domain\Model\AssetInterface;

interface UriServiceInterface
{
    /**
     * @param string $rawLinkUri
     * @param ContentContext $subgraph
     * @return string
     */
    public function getNodeUri(TraversableNodeInterface $documentNode, bool $absolute = false): string;

    /**
     * @param string $rawLinkUri
     * @param ContentContext $subgraph
     * @return string
     */
    public function getResourceUri(string $packageKey, string $resourcePath): string;

    /**
     * @param string $rawLinkUri
     * @param ContentContext $subgraph
     * @return string
     */
    public function getAssetUri(AssetInterface $asset): string;

    /**
     * @param string $rawLinkUri
     * @param ContentContext $subgraph
     * @return string
     */
    public function getDummyImageBaseUri(): string;

    /**
     * @param string $rawLinkUri
     * @param ContentContext $subgraph
     * @return string
     */
    public function getControllerContext(): ControllerContext;

    /**
     * @param string $rawLinkUri
     * @param ContentContext $subgraph
     * @return string
     */
    public function resolveLinkUri(string $rawLinkUri, ContentContext $subgraph): string;
}
