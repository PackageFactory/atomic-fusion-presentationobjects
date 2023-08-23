<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Presentation\Slot;

use Neos\ContentRepository\Core\NodeType\NodeType;
use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;
use Prophecy\Prophet;

final class ContentTest extends UnitTestCase
{
    /**
     * @var Prophet
     */
    private $prophet;

    /**
     * @before
     */
    public function setUpContentTest(): void
    {
        $this->prophet = new Prophet();
    }

    public function testHoldsInformationOnContentElements(): void
    {
        $this->markTestSkipped('Cannot mock nodes yet');
        $contentNodeType = $this->prophet->prophesize(NodeType::class);
        $contentNodeType->getName()->willReturn('Vendor.Site:Content.Element');

        $contentNode = $this->prophet->prophesize(Node::class);
        $contentNode->nodeType->willReturn($contentNodeType->reveal());

        $content = Content::fromNode($contentNode->reveal());

        $this->assertInstanceOf(Content::class, $content);
        $this->assertSame($contentNode->reveal(), $content->contentNode);
        $this->assertEquals('Vendor.Site:Content.Element', $content->contentPrototypeName);
    }

    public function testAllowsForCustomContentElementRendering(): void
    {
        $this->markTestSkipped('Cannot mock nodes yet');
        $contentNode = $this->prophet->prophesize(Node::class);
        $contentNode->nodeType->shouldNotBeCalled();

        $content = Content::fromNode($contentNode->reveal(), 'Vendor.Site:Custom.Prototype');

        $this->assertInstanceOf(Content::class, $content);
        $this->assertSame($contentNode->reveal(), $content->contentNode);
        $this->assertEquals('Vendor.Site:Custom.Prototype', $content->contentPrototypeName);
    }

    public function testIsRenderedAsContentFusionPrototype(): void
    {
        $this->markTestSkipped('Cannot mock nodes yet');
        $contentNode = $this->prophet->prophesize(Node::class);

        $content = Content::fromNode($contentNode->reveal(), '');

        $this->assertEquals('PackageFactory.AtomicFusion.PresentationObjects:Content', $content->getPrototypeName());
    }
}
