<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Infrastructure;

use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Uri;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Service\Context as ContentContext;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ControllerContext;
use Neos\Flow\ResourceManagement\PersistentResource;
use Neos\Flow\ResourceManagement\ResourceManager;
use Neos\Flow\Http;
use Neos\Media\Domain\Model\AssetInterface;
use Neos\Media\Domain\Repository\AssetRepository;
use Neos\Neos\Service\LinkingService;
use Neos\Flow\Mvc;
use Neos\Flow\Core\Bootstrap;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\UriServiceInterface;

/**
 * The URI service
 */
final class UriService implements UriServiceInterface
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
     * @var null|ControllerContext
     */
    protected $controllerContext;

    /**
     * @param TraversableNodeInterface $documentNode
     * @param bool $absolute
     * @param string|null $format
     * @return Uri
     * @throws Http\Exception
     * @throws Mvc\Routing\Exception\MissingActionNameException
     * @throws \Neos\Flow\Persistence\Exception\IllegalObjectTypeException
     * @throws \Neos\Flow\Property\Exception
     * @throws \Neos\Flow\Security\Exception
     * @throws \Neos\Neos\Exception
     */
    public function getNodeUri(TraversableNodeInterface $documentNode, bool $absolute = false, ?string $format = null): Uri
    {
        return new Uri($this->linkingService->createNodeUri($this->getControllerContext(), $documentNode, null, $format, $absolute));
    }

    /**
     * @param string $packageKey
     * @param string $resourcePath
     * @return Uri
     */
    public function getResourceUri(string $packageKey, string $resourcePath): Uri
    {
        return new Uri($this->resourceManager->getPublicPackageResourceUri($packageKey, $resourcePath));
    }

    public function getPersistentResourceUri(PersistentResource $resource): ?Uri
    {
        $uri = $this->resourceManager->getPublicPersistentResourceUri($resource);

        return is_string($uri)
            ? new Uri($uri)
            : null;
    }

    /**
     * @param AssetInterface $asset
     * @return Uri
     */
    public function getAssetUri(AssetInterface $asset): Uri
    {
        $uri = $this->resourceManager->getPublicPersistentResourceUri($asset->getResource());

        return new Uri(is_string($uri) ? $uri : '#');
    }

    /**
     * @return Uri
     */
    public function getDummyImageBaseUri(): Uri
    {
        $uriBuilder = $this->getControllerContext()->getUriBuilder();

        return new Uri($uriBuilder->uriFor(
            'image',
            [],
            'dummyImage',
            'Sitegeist.Kaleidoscope'
        ));
    }

    /**
     * @return ControllerContext
     */
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
     * @return Uri
     * @throws Http\Exception
     * @throws Mvc\Routing\Exception\MissingActionNameException
     * @throws \Neos\Flow\Persistence\Exception\IllegalObjectTypeException
     * @throws \Neos\Flow\Property\Exception
     * @throws \Neos\Flow\Security\Exception
     * @throws \Neos\Neos\Exception
     */
    public function resolveLinkUri(string $rawLinkUri, ContentContext $subgraph): Uri
    {
        if (\mb_substr($rawLinkUri, 0, 7) === 'node://') {
            $nodeIdentifier = \mb_substr($rawLinkUri, 7);
            /** @var null|TraversableNodeInterface $node */
            $node = $subgraph->getNodeByIdentifier($nodeIdentifier);
            $linkUri = $node ? $this->getNodeUri($node) : new Uri('#');
        } elseif (\mb_substr($rawLinkUri, 0, 8) === 'asset://') {
            $assetIdentifier = \mb_substr($rawLinkUri, 8);
            /** @var null|AssetInterface $asset */
            $asset = $this->assetRepository->findByIdentifier($assetIdentifier);
            $linkUri = $asset ? $this->getAssetUri($asset) : new Uri('#');
        } elseif (\mb_substr($rawLinkUri, 0, 8) === 'https://' || \mb_substr($rawLinkUri, 0, 7) === 'http://') {
            $linkUri = new Uri($rawLinkUri);
        } else {
            $linkUri = new Uri('#');
        }

        return $linkUri;
    }
}
