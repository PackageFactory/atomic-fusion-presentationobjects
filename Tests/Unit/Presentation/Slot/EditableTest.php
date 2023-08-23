<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Presentation\Slot;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
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
     */
    public function setUpEditableTest(): void
    {
        $this->prophet = new Prophet();
    }

    public function testHoldsInformationOnInlineEditableElements(): void
    {
        $this->markTestSkipped('Cannot mock nodes yet');
        $contentNode1 = $this->prophet->prophesize(Node::class);
        $contentNode2 = $this->prophet->prophesize(Node::class);

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

    public function testIsRenderedAsEditableFusionPrototype(): void
    {
        $this->markTestSkipped('Cannot mock nodes yet');
        $contentNode = $this->prophet->prophesize(Node::class);

        $editable = Editable::fromNodeProperty($contentNode->reveal(), 'someProperty');

        $this->assertEquals('PackageFactory.AtomicFusion.PresentationObjects:Editable', $editable->getPrototypeName());
    }
}
