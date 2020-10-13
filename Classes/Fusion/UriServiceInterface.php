<?php declare(strict_types=1);
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
     * @param TraversableNodeInterface $documentNode
     * @param boolean $absolute
     * @return string
     */
    public function getNodeUri(TraversableNodeInterface $documentNode, bool $absolute = false): string;

    /**
     * @param string $packageKey
     * @param string $resourcePath
     * @return string
     */
    public function getResourceUri(string $packageKey, string $resourcePath): string;

    /**
     * @param AssetInterface $asset
     * @return string
     */
    public function getAssetUri(AssetInterface $asset): string;

    /**
     * @return string
     */
    public function getDummyImageBaseUri(): string;

    /**
     * @return ControllerContext
     */
    public function getControllerContext(): ControllerContext;

    /**
     * @param string $rawLinkUri
     * @param ContentContext $subgraph
     * @return string
     */
    public function resolveLinkUri(string $rawLinkUri, ContentContext $subgraph): string;
}
