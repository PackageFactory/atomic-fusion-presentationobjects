<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Presentation\Slot;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Model\NodeType;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\ContentInterface;
use Prophecy\Prophet;

final class ContentTest extends UnitTestCase
{
    /**
     * @var Prophet
     */
    private $prophet;

    /**
     * @before
     * @return void
     */
    public function setUpContentTest(): void
    {
        $this->prophet = new Prophet();
    }

    /**
     * @test
     * @return void
     */
    public function holdsInformationOnContentElements(): void
    {
        $contentNodeType = $this->prophet->prophesize(NodeType::class);
        $contentNodeType->getName()->willReturn('Vendor.Site:Content.Element');

        $contentNode = $this->prophet
            ->prophesize(TraversableNodeInterface::class)
            ->willImplement(NodeInterface::class);
        $contentNode->getNodeType()->willReturn($contentNodeType->reveal());

        $content = Content::fromNode($contentNode->reveal());

        $this->assertInstanceOf(ContentInterface::class, $content);
        $this->assertSame($contentNode->reveal(), $content->getContentNode());
        $this->assertEquals('Vendor.Site:Content.Element', $content->getContentPrototypeName());
    }

    /**
     * @test
     * @return void
     */
    public function allowsForCustomContentElementRendering(): void
    {
        $contentNode = $this->prophet
            ->prophesize(TraversableNodeInterface::class)
            ->willImplement(NodeInterface::class);
        $contentNode->getNodeType()->shouldNotBeCalled();

        $content = Content::fromNode($contentNode->reveal(), 'Vendor.Site:Custom.Prototype');

        $this->assertInstanceOf(ContentInterface::class, $content);
        $this->assertSame($contentNode->reveal(), $content->getContentNode());
        $this->assertEquals('Vendor.Site:Custom.Prototype', $content->getContentPrototypeName());
    }

    /**
     * @test
     * @return void
     */
    public function isRenderedAsContentFusionPrototype(): void
    {
        $contentNode = $this->prophet
        ->prophesize(TraversableNodeInterface::class)
        ->willImplement(NodeInterface::class);

        $content = Content::fromNode($contentNode->reveal(), '');

        $this->assertEquals('PackageFactory.AtomicFusion.PresentationObjects:Content', $content->getPrototypeName());
    }
}
