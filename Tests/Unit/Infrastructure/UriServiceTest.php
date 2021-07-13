<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Infrastructure;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Service\Context as ContentContext;
use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Core\RequestHandlerInterface;
use Neos\Flow\Mvc\Controller\ControllerContext;
use Neos\Flow\Mvc\Routing\UriBuilder;
use Neos\Flow\ResourceManagement\PersistentResource;
use Neos\Flow\ResourceManagement\ResourceManager;
use Neos\Flow\Tests\UnitTestCase;
use Neos\Media\Domain\Model\AssetInterface;
use Neos\Media\Domain\Repository\AssetRepository;
use Neos\Neos\Service\LinkingService;
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
     * @var ObjectProphecy<LinkingService>
     */
    private $linkingService;

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
     * @var UriService
     */
    private $uriService;

    /**
     * @before
     * @return void
     */
    public function setUpUriService(): void
    {
        $this->prophet = new Prophet();

        $this->resourceManager = $this->prophet->prophesize(ResourceManager::class);
        $this->linkingService = $this->prophet->prophesize(LinkingService::class);
        $this->assetRepository = $this->prophet->prophesize(AssetRepository::class);

        $this->bootstrap = $this->prophet->prophesize(Bootstrap::class);
        $this->bootstrap
            ->getActiveRequestHandler()
            ->willReturn($this->prophet->prophesize(RequestHandlerInterface::class)->reveal());

        $this->uriBuilder = $this->prophet->prophesize(UriBuilder::class);

        $this->uriService = new UriService();

        $this->inject($this->uriService, 'resourceManager', $this->resourceManager->reveal());
        $this->inject($this->uriService, 'linkingService', $this->linkingService->reveal());
        $this->inject($this->uriService, 'assetRepository', $this->assetRepository->reveal());
        $this->inject($this->uriService, 'bootstrap', $this->bootstrap->reveal());

        $this->inject($this->uriService->getControllerContext(), 'uriBuilder', $this->uriBuilder->reveal());
    }

    /**
     * @after
     * @return void
     */
    public function tearDownUriService(): void
    {
        $this->prophet->checkPredictions();
    }

    /**
     * @test
     * @return void
     */
    public function providesUrisForNodes(): void
    {
        $documentNode = $this->prophet
            ->prophesize(TraversableNodeInterface::class)
            ->willImplement(NodeInterface::class);
        
        $controllerContext = $this->uriService->getControllerContext();

        $this->linkingService
            ->createNodeUri($controllerContext, $documentNode, null, null, false)
            ->willReturn('/path/to/document');
        $this->linkingService
            ->createNodeUri($controllerContext, $documentNode, null, null, true)
            ->willReturn('https://vendor.site/path/to/document');

        $this->assertEquals('/path/to/document', $this->uriService->getNodeUri($documentNode->reveal(), false));
        $this->assertEquals('https://vendor.site/path/to/document', $this->uriService->getNodeUri($documentNode->reveal(), true));
    }

    /**
     * @test
     * @return void
     */
    public function providesUrisForResources(): void
    {
        $this->resourceManager
            ->getPublicPackageResourceUri('Vendor.Site', 'Images/logo.png')
            ->willReturn('/_Resources/Static/Vendor.Site/Public/Images/logo.png');

        $this->assertEquals(
            '/_Resources/Static/Vendor.Site/Public/Images/logo.png',
            $this->uriService->getResourceUri('Vendor.Site', 'Images/logo.png')
        );
    }

    /**
     * @test
     * @return void
     */
    public function providesUrisForAssets(): void
    {
        $resource = $this->prophet->prophesize(PersistentResource::class);
        $asset = $this->prophet->prophesize(AssetInterface::class);
        $asset->getResource()->willReturn($resource);

        $this->resourceManager
            ->getPublicPersistentResourceUri($resource)
            ->willReturn('/_Resources/Persistent/path/to/resource');

        $this->assertEquals(
            '/_Resources/Persistent/path/to/resource',
            $this->uriService->getAssetUri($asset->reveal())
        );
    }

    /**
     * @test
     * @return void
     */
    public function providesADummyImageUri(): void
    {
        $this->uriBuilder
            ->uriFor(
                'image',
                [],
                'dummyImage',
                'Sitegeist.Kaleidoscope'
            )
            ->willReturn('/path/to/dummy-image');

        $this->assertEquals('/path/to/dummy-image', $this->uriService->getDummyImageBaseUri());
    }

    /**
     * @test
     * @return void
     */
    public function providesAControllerContext(): void
    {
        $controllerContext = $this->uriService->getControllerContext();

        $this->assertTrue($controllerContext instanceof ControllerContext);
        $this->assertSame($controllerContext, $this->uriService->getControllerContext());
    }

    /**
     * @test
     * @return void
     */
    public function resolvesLinkUrisWithNodeProtocol(): void
    {
        $documentNode = $this->prophet
            ->prophesize(TraversableNodeInterface::class)
            ->willImplement(NodeInterface::class);
        
        $controllerContext = $this->uriService->getControllerContext();

        $this->linkingService
            ->createNodeUri($controllerContext, $documentNode, null, null, false)
            ->willReturn('/blog/2020/10/10/coronavirus-sucks.html');

        $subgraph = $this->prophet->prophesize(ContentContext::class);
        $subgraph->getNodeByIdentifier('7f2939f6-db07-476c-afac-7cac59466242')->willReturn($documentNode);

        $this->assertEquals(
            '/blog/2020/10/10/coronavirus-sucks.html',
            $this->uriService->resolveLinkUri('node://7f2939f6-db07-476c-afac-7cac59466242', $subgraph->reveal())
        );
    }

    /**
     * @test
     * @return void
     */
    public function resolvesLinkUrisWithNodeProtocolToHashIfNodeCannotBeFound(): void
    {
        $subgraph = $this->prophet->prophesize(ContentContext::class);

        $this->assertEquals(
            '#',
            $this->uriService->resolveLinkUri('node://a520cadb-eedf-42a5-b03d-796821b35e73', $subgraph->reveal())
        );
    }

    /**
     * @test
     * @return void
     */
    public function resolvesLinkUrisWithAssetProtocol(): void
    {
        $resource = $this->prophet->prophesize(PersistentResource::class);
        $asset = $this->prophet->prophesize(AssetInterface::class);
        $asset->getResource()->willReturn($resource);

        $this->resourceManager
            ->getPublicPersistentResourceUri($resource)
            ->willReturn('/_Resources/Persistent/path/to/49638323-a25d-43a3-a0b3-66693239439a');

        $this->assetRepository
            ->findByIdentifier('49638323-a25d-43a3-a0b3-66693239439a')
            ->willReturn($asset);

        $subgraph = $this->prophet->prophesize(ContentContext::class);

        $this->assertEquals(
            '/_Resources/Persistent/path/to/49638323-a25d-43a3-a0b3-66693239439a',
            $this->uriService->resolveLinkUri('asset://49638323-a25d-43a3-a0b3-66693239439a', $subgraph->reveal())
        );
    }

    /**
     * @test
     * @return void
     */
    public function resolvesLinkUrisWithAssetProtocolToHashIfAssetCannotBeFound(): void
    {
        $subgraph = $this->prophet->prophesize(ContentContext::class);

        $this->assertEquals(
            '#',
            $this->uriService->resolveLinkUri('asset://49638323-a25d-43a3-a0b3-66693239439a', $subgraph->reveal())
        );
    }

    /**
     * @test
     * @return void
     */
    public function resolvesLinkUrisWithHttpProtocol(): void
    {
        $subgraph = $this->prophet->prophesize(ContentContext::class);

        $this->assertEquals(
            'http://some.domain/some/path',
            $this->uriService->resolveLinkUri('http://some.domain/some/path', $subgraph->reveal())
        );
    }

    /**
     * @test
     * @return void
     */
    public function resolvesLinkUrisWithHttpsProtocol(): void
    {
        $subgraph = $this->prophet->prophesize(ContentContext::class);

        $this->assertEquals(
            'https://some.domain/some/path',
            $this->uriService->resolveLinkUri('https://some.domain/some/path', $subgraph->reveal())
        );
    }

    /**
     * @test
     * @return void
     */
    public function resolvesLinkUrisToHashWhenProtocolIsUnknown(): void
    {
        $subgraph = $this->prophet->prophesize(ContentContext::class);

        $this->assertEquals('#', $this->uriService->resolveLinkUri('ftp://some.domain/some/path', $subgraph->reveal()));
        $this->assertEquals('#', $this->uriService->resolveLinkUri('#top', $subgraph->reveal()));
        $this->assertEquals('#', $this->uriService->resolveLinkUri('something-cmopletely-different', $subgraph->reveal()));
    }
}
