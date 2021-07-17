<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Fusion;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

use GuzzleHttp\Psr7\Uri;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Service\Context as ContentContext;
use Neos\Flow\Mvc\Controller\ControllerContext;
use Neos\Media\Domain\Model\AssetInterface;

interface UriServiceInterface
{
    /**
     * @param TraversableNodeInterface $documentNode
     * @param boolean $absolute
     * @param string|null $format
     * @return Uri
     */
    public function getNodeUri(TraversableNodeInterface $documentNode, bool $absolute = false, ?string $format = null): Uri;

    /**
     * @param string $packageKey
     * @param string $resourcePath
     * @return Uri
     */
    public function getResourceUri(string $packageKey, string $resourcePath): Uri;

    /**
     * @param AssetInterface $asset
     * @return Uri
     */
    public function getAssetUri(AssetInterface $asset): Uri;

    /**
     * @return Uri
     */
    public function getDummyImageBaseUri(): Uri;

    /**
     * @return ControllerContext
     */
    public function getControllerContext(): ControllerContext;

    /**
     * @param string $rawLinkUri
     * @param ContentContext $subgraph
     * @return Uri
     */
    public function resolveLinkUri(string $rawLinkUri, ContentContext $subgraph): Uri;
}
