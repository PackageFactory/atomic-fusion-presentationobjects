<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Infrastructure;

use GuzzleHttp\Psr7\Uri;
use Neos\ContentRepository\Core\Projection\ContentGraph\ContentSubgraphInterface;
use Neos\ContentRepositoryRegistry\ContentRepositoryRegistry;
use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Core\RequestHandlerInterface;
use Neos\Flow\Mvc\Controller\ControllerContext;
use Neos\Flow\Mvc\Routing\UriBuilder;
use Neos\Flow\ResourceManagement\PersistentResource;
use Neos\Flow\ResourceManagement\ResourceManager;
use Neos\Flow\Tests\UnitTestCase;
use Neos\Media\Domain\Model\AssetInterface;
use Neos\Media\Domain\Repository\AssetRepository;
use PackageFactory\AtomicFusion\PresentationObjects\Infrastructure\UriService;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

/**
 * Test for the UriService
 */
final class UriServiceTest extends UnitTestCase
{
    /**
     * @var Prophet
     */
    private $prophet;

    /**
     * @var ObjectProphecy<ResourceManager>
     */
    private $resourceManager;

    /**
     * @var ObjectProphecy<AssetRepository>
     */
    private $assetRepository;

    /**
     * @var ObjectProphecy<Bootstrap>
     */
    private $bootstrap;

    /**
     * @var ObjectProphecy<UriBuilder>
     */
    private $uriBuilder;

    /**
     * @var ObjectProphecy<ContentRepositoryRegistry>
     */
    private $contentRepositoryRegistry;

    /**
     * @var UriService
     */
    private $uriService;

    /**
     * @before
     */
    public function setUpUriService(): void
    {
        $this->prophet = new Prophet();

        $this->resourceManager = $this->prophet->prophesize(ResourceManager::class);
        $this->assetRepository = $this->prophet->prophesize(AssetRepository::class);

        $this->bootstrap = $this->prophet->prophesize(Bootstrap::class);
        $this->bootstrap
            ->getActiveRequestHandler()
            ->willReturn($this->prophet->prophesize(RequestHandlerInterface::class)->reveal());

        $this->uriBuilder = $this->prophet->prophesize(UriBuilder::class);
        #$this->contentRepositoryRegistry = $this->prophet->prophesize(ContentRepositoryRegistry::class);

        /*
        $this->uriService = new UriService(
            $this->contentRepositoryRegistry->reveal(),
            $this->resourceManager->reveal(),
            $this->assetRepository->reveal(),
            $this->bootstrap->reveal()
        );
        */
    }

    public function testTearDownUriService(): void
    {
        $this->markTestSkipped('Cannot mock the content repository registry yet');
        $this->prophet->checkPredictions();
    }

    public function testProvidesUrisForResources(): void
    {
        $this->markTestSkipped('Cannot mock the content repository registry yet');
        $this->resourceManager
            ->getPublicPackageResourceUri('Vendor.Site', 'Images/logo.png')
            ->willReturn('/_Resources/Static/Vendor.Site/Public/Images/logo.png');

        $this->assertEquals(
            new Uri('/_Resources/Static/Vendor.Site/Public/Images/logo.png'),
            $this->uriService->getResourceUri('Vendor.Site', 'Images/logo.png')
        );
    }

    public function testProvidesUrisForAssets(): void
    {
        $this->markTestSkipped('Cannot mock the content repository registry yet');
        $resource = $this->prophet->prophesize(PersistentResource::class);
        $asset = $this->prophet->prophesize(AssetInterface::class);
        $asset->getResource()->willReturn($resource);

        $this->resourceManager
            ->getPublicPersistentResourceUri($resource)
            ->willReturn('/_Resources/Persistent/path/to/resource');

        $this->assertEquals(
            new Uri('/_Resources/Persistent/path/to/resource'),
            $this->uriService->getAssetUri($asset->reveal())
        );
    }

    public function testProvidesADummyImageUri(): void
    {
        $this->markTestSkipped('Cannot mock the content repository registry yet');
        $this->uriBuilder
            ->uriFor(
                'image',
                [],
                'dummyImage',
                'Sitegeist.Kaleidoscope'
            )
            ->willReturn('/path/to/dummy-image');

        $this->assertEquals(new Uri('/path/to/dummy-image'), $this->uriService->getDummyImageBaseUri());
    }

    public function testProvidesAControllerContext(): void
    {
        $this->markTestSkipped('Cannot mock the content repository registry yet');
        $controllerContext = $this->uriService->controllerContext;

        $this->assertTrue($controllerContext instanceof ControllerContext);
        $this->assertSame($controllerContext, $this->uriService->controllerContext);
    }

    public function testResolvesLinkUrisWithNodeProtocolToHashIfNodeCannotBeFound(): void
    {
        $this->markTestSkipped('Cannot mock the content repository registry yet');
        $subgraph = $this->prophet->prophesize(ContentSubgraphInterface::class);

        $this->assertEquals(
            new Uri('#'),
            $this->uriService->resolveLinkUri('node://a520cadb-eedf-42a5-b03d-796821b35e73', $subgraph->reveal())
        );
    }

    public function testResolvesLinkUrisWithAssetProtocol(): void
    {
        $this->markTestSkipped('Cannot mock the content repository registry yet');
        $resource = $this->prophet->prophesize(PersistentResource::class);
        $asset = $this->prophet->prophesize(AssetInterface::class);
        $asset->getResource()->willReturn($resource);

        $this->resourceManager
            ->getPublicPersistentResourceUri($resource)
            ->willReturn('/_Resources/Persistent/path/to/49638323-a25d-43a3-a0b3-66693239439a');

        $this->assetRepository
            ->findByIdentifier('49638323-a25d-43a3-a0b3-66693239439a')
            ->willReturn($asset);

        $subgraph = $this->prophet->prophesize(ContentSubgraphInterface::class);

        $this->assertEquals(
            new Uri('/_Resources/Persistent/path/to/49638323-a25d-43a3-a0b3-66693239439a'),
            $this->uriService->resolveLinkUri('asset://49638323-a25d-43a3-a0b3-66693239439a', $subgraph->reveal())
        );
    }

    public function testResolvesLinkUrisWithAssetProtocolToHashIfAssetCannotBeFound(): void
    {
        $this->markTestSkipped('Cannot mock the content repository registry yet');
        $subgraph = $this->prophet->prophesize(ContentSubgraphInterface::class);

        $this->assertEquals(
            new Uri('#'),
            $this->uriService->resolveLinkUri('asset://49638323-a25d-43a3-a0b3-66693239439a', $subgraph->reveal())
        );
    }

    public function testResolvesLinkUrisWithHttpProtocol(): void
    {
        $this->markTestSkipped('Cannot mock the content repository registry yet');
        $subgraph = $this->prophet->prophesize(ContentSubgraphInterface::class);

        $this->assertEquals(
            new Uri('http://some.domain/some/path'),
            $this->uriService->resolveLinkUri('http://some.domain/some/path', $subgraph->reveal())
        );
    }

    public function testResolvesLinkUrisWithHttpsProtocol(): void
    {
        $this->markTestSkipped('Cannot mock the content repository registry yet');
        $subgraph = $this->prophet->prophesize(ContentSubgraphInterface::class);

        $this->assertEquals(
            new Uri('https://some.domain/some/path'),
            $this->uriService->resolveLinkUri('https://some.domain/some/path', $subgraph->reveal())
        );
    }

    public function testResolvesLinkUrisToHashWhenProtocolIsUnknown(): void
    {
        $this->markTestSkipped('Cannot mock the content repository registry yet');
        $subgraph = $this->prophet->prophesize(ContentSubgraphInterface::class);

        $this->assertEquals(new Uri('#'), $this->uriService->resolveLinkUri('ftp://some.domain/some/path', $subgraph->reveal()));
        $this->assertEquals(new Uri('#'), $this->uriService->resolveLinkUri('#top', $subgraph->reveal()));
        $this->assertEquals(new Uri('#'), $this->uriService->resolveLinkUri('something-cmopletely-different', $subgraph->reveal()));
    }
}
