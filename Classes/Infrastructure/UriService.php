<?php
namespace PackageFactory\AtomicFusion\PresentationObjects\Infrastructure;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

use GuzzleHttp\Psr7\ServerRequest;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Service\Context as ContentContext;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ControllerContext;
use Neos\Flow\ResourceManagement\ResourceManager;
use Neos\Flow\Http;
use Neos\Media\Domain\Model\Asset;
use Neos\Media\Domain\Model\AssetInterface;
use Neos\Media\Domain\Repository\AssetRepository;
use Neos\Neos\Service\LinkingService;
use Neos\Flow\Mvc;
use Neos\Flow\Core\Bootstrap;

/**
 * The URI service
 */
final class UriService
{
    /**
     * @Flow\Inject
     * @var ResourceManager
     */
    protected $resourceManager;

    /**
     * @Flow\Inject
     * @var LinkingService
     */
    protected $linkingService;

    /**
     * @Flow\Inject
     * @var AssetRepository
     */
    protected $assetRepository;

    /**
     * @Flow\Inject
     * @var Bootstrap
     */
    protected $bootstrap;

    /**
     * @var ControllerContext
     */
    protected $controllerContext;

    /**
     * @param TraversableNodeInterface $documentNode
     * @param bool $absolute
     * @return string
     * @throws Http\Exception
     * @throws Mvc\Routing\Exception\MissingActionNameException
     * @throws \Neos\Flow\Persistence\Exception\IllegalObjectTypeException
     * @throws \Neos\Flow\Property\Exception
     * @throws \Neos\Flow\Security\Exception
     * @throws \Neos\Neos\Exception
     */
    public function getNodeUri(TraversableNodeInterface $documentNode, bool $absolute = false): string
    {
        return $this->linkingService->createNodeUri($this->getControllerContext(), $documentNode, null, null, $absolute);
    }

    public function getResourceUri(string $packageKey, string $resourcePath): string
    {
        return $this->resourceManager->getPublicPackageResourceUri($packageKey, $resourcePath);
    }

    public function getAssetUri(AssetInterface $asset): string
    {
        return $this->resourceManager->getPublicPersistentResourceUri($asset->getResource());
    }

    public function getDummyImageBaseUri(): string
    {
        $uriBuilder = $this->getControllerContext()->getUriBuilder();

        return $uriBuilder->uriFor(
            'image',
            [],
            'dummyImage',
            'Sitegeist.Kaleidoscope'
        );
    }

    public function getControllerContext(): ControllerContext
    {
        if (is_null($this->controllerContext)) {
            $requestHandler = $this->bootstrap->getActiveRequestHandler();
            if ($requestHandler instanceof Http\RequestHandler) {
                $request = $requestHandler->getHttpRequest();
            } else {
                $request = ServerRequest::fromGlobals();
            }
            $actionRequest = Mvc\ActionRequest::fromHttpRequest($request);
            $uriBuilder = new Mvc\Routing\UriBuilder();
            $uriBuilder->setRequest($actionRequest);
            $this->controllerContext = new Mvc\Controller\ControllerContext(
                $actionRequest,
                new Mvc\ActionResponse(),
                new Mvc\Controller\Arguments(),
                $uriBuilder
            );
        }

        return $this->controllerContext;
    }

    /**
     * @param string $rawLinkUri
     * @param ContentContext $subgraph
     * @return string
     * @throws Http\Exception
     * @throws Mvc\Routing\Exception\MissingActionNameException
     * @throws \Neos\Flow\Persistence\Exception\IllegalObjectTypeException
     * @throws \Neos\Flow\Property\Exception
     * @throws \Neos\Flow\Security\Exception
     * @throws \Neos\Neos\Exception
     */
    public function resolveLinkUri(string $rawLinkUri, ContentContext $subgraph): string
    {
        if (\mb_substr($rawLinkUri, 0, 7) === 'node://') {
            $nodeIdentifier = \mb_substr($rawLinkUri, 7);
            $node = $subgraph->getNodeByIdentifier($nodeIdentifier);
            $linkUri = $node ? $this->getNodeUri($node) : '#';
        } elseif (\mb_substr($rawLinkUri, 0, 8) === 'asset://') {
            $assetIdentifier = \mb_substr($rawLinkUri, 8);
            /** @var Asset $asset */
            $asset = $this->assetRepository->findByIdentifier($assetIdentifier);
            $linkUri = $this->getAssetUri($asset);
        } elseif (\mb_substr($rawLinkUri, 0, 8) === 'https://' || \mb_substr($rawLinkUri, 0, 7) === 'http://') {
            $linkUri = $rawLinkUri;
        } else {
            $linkUri = '#';
        }

        return $linkUri;
    }
}
