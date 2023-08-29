<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Infrastructure;

use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Uri;
use Neos\ContentRepository\Core\Projection\ContentGraph\ContentSubgraphInterface;
use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use Neos\ContentRepository\Core\SharedModel\Node\NodeAggregateId;
use Neos\ContentRepositoryRegistry\ContentRepositoryRegistry;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ControllerContext;
use Neos\Flow\Mvc\Routing\UriBuilder;
use Neos\Flow\ResourceManagement\PersistentResource;
use Neos\Flow\ResourceManagement\ResourceManager;
use Neos\Flow\Http;
use Neos\Media\Domain\Model\AssetInterface;
use Neos\Media\Domain\Repository\AssetRepository;
use Neos\Neos\FrontendRouting\NodeAddressFactory;
use Neos\Neos\FrontendRouting\NodeUriBuilder;
use Neos\Flow\Mvc;
use Neos\Flow\Core\Bootstrap;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\UriServiceInterface;
use Psr\Http\Message\UriInterface;

/**
 * The URI service
 */
#[Flow\Scope('singleton')]
final class UriService implements UriServiceInterface
{
    private ?ControllerContext $controllerContext = null;

    public function __construct(
        private readonly ContentRepositoryRegistry $contentRepositoryRegistry,
        private readonly ResourceManager $resourceManager,
        private readonly AssetRepository $assetRepository,
        private readonly Bootstrap $bootstrap
    ) {
    }

    public function useControllerContext(ControllerContext $controllerContext): void
    {
        $this->controllerContext = $controllerContext;
    }

    public function getNodeUri(Node $documentNode, bool $absolute = false, ?string $format = null): UriInterface
    {
        $contentRepository = $this->contentRepositoryRegistry->get(
            $documentNode->subgraphIdentity->contentRepositoryId
        );
        $nodeAddressFactory = NodeAddressFactory::create($contentRepository);
        $nodeAddress = $nodeAddressFactory->createFromNode($documentNode);

        $uriBuilder = new UriBuilder();
        $uriBuilder->setRequest($this->getControllerContext()->getRequest());
        $uriBuilder
            ->setCreateAbsoluteUri($absolute)
            ->setFormat($format ?: 'html');

        return NodeUriBuilder::fromUriBuilder($uriBuilder)
            ->uriFor($nodeAddress);
    }

    public function getResourceUri(string $packageKey, string $resourcePath): UriInterface
    {
        return new Uri($this->resourceManager->getPublicPackageResourceUri($packageKey, $resourcePath));
    }

    public function getPersistentResourceUri(PersistentResource $resource): ?UriInterface
    {
        $uri = $this->resourceManager->getPublicPersistentResourceUri($resource);

        return is_string($uri)
            ? new Uri($uri)
            : null;
    }

    public function getAssetUri(AssetInterface $asset): UriInterface
    {
        $uri = $this->resourceManager->getPublicPersistentResourceUri($asset->getResource());

        return new Uri(is_string($uri) ? $uri : '#');
    }

    public function getDummyImageBaseUri(): UriInterface
    {
        $uriBuilder = $this->getControllerContext()->getUriBuilder();

        return new Uri($uriBuilder->uriFor(
            'image',
            [],
            'dummyImage',
            'Sitegeist.Kaleidoscope'
        ));
    }

    public function getControllerContext(): ControllerContext
    {
        if (!$this->controllerContext) {
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

    public function resolveLinkUri(string $rawLinkUri, ContentSubgraphInterface $subgraph): UriInterface
    {
        if (\mb_substr($rawLinkUri, 0, 7) === 'node://') {
            $serializedNodeAggregateId = \mb_substr($rawLinkUri, 7);
            $node = $subgraph->findNodeById(NodeAggregateId::fromString($serializedNodeAggregateId));
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
