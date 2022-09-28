<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Presentation\Slot;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable;
use Prophecy\Prophet;

final class EditableTest extends UnitTestCase
{
    /**
     * @var Prophet
     */
    private $prophet;

    /**
     * @before
     * @return void
     */
    public function setUpEditableTest(): void
    {
        $this->prophet = new Prophet();
    }

    /**
     * @test
     * @return void
     */
    public function holdsInformationOnInlineEditableElements(): void
    {
        $contentNode1 = $this->prophet
            ->prophesize(TraversableNodeInterface::class)
            ->willImplement(NodeInterface::class);
        $contentNode2 = $this->prophet
            ->prophesize(TraversableNodeInterface::class)
            ->willImplement(NodeInterface::class);

        $editable1 = Editable::fromNodeProperty($contentNode1->reveal(), 'someProperty');
        $editable2 = Editable::fromNodeProperty($contentNode2->reveal(), 'someOtherProperty', false);

        $this->assertInstanceOf(Editable::class, $editable1);
        $this->assertSame($contentNode1->reveal(), $editable1->node);
        $this->assertEquals('someProperty', $editable1->propertyName);
        $this->assertEquals(true, $editable1->isBlock);

        $this->assertInstanceOf(Editable::class, $editable2);
        $this->assertSame($contentNode2->reveal(), $editable2->node);
        $this->assertEquals('someOtherProperty', $editable2->propertyName);
        $this->assertEquals(false, $editable2->isBlock);
    }

    /**
     * @test
     * @return void
     */
    public function isRenderedAsEditableFusionPrototype(): void
    {
        $contentNode = $this->prophet
            ->prophesize(TraversableNodeInterface::class)
            ->willImplement(NodeInterface::class);

        $editable = Editable::fromNodeProperty($contentNode->reveal(), 'someProperty');

        $this->assertEquals('PackageFactory.AtomicFusion.PresentationObjects:Editable', $editable->getPrototypeName());
    }
}
