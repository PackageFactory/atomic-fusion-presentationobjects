<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Fusion;

use Neos\Flow\Tests\UnitTestCase;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraintFactory;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraints;
use Neos\ContentRepository\Domain\NodeType\NodeTypeName;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use Neos\Neos\Service\ContentElementEditableService;
use Neos\Neos\Service\ContentElementWrappingService;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObjectFactory;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\PresentationObjectComponentImplementation;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

/**
 * Test for the AbstractComponentPresentationObject
 */
final class AbstractComponentPresentationObjectFactoryTest extends UnitTestCase
{
    /**
     * @var Prophet
     */
    private $prophet;

    /**
     * @var ObjectProphecy<ContentElementWrappingService>
     */
    private $contentElementWrappingService;

    /**
     * @var ObjectProphecy<ContentElementEditableService>
     */
    private $contentElementEditableService;

    /**
     * @var ObjectProphecy<NodeTypeConstraintFactory>
     */
    private $nodeTypeConstraintFactory;

    /**
     * @var AbstractComponentPresentationObjectFactory
     */
    private $factory;

    /**
     * @before
     * @return void
     */
    public function setUpComponentPresentationObjectFactory(): void
    {
        $this->prophet = new Prophet();

        $this->contentElementWrappingService = $this->prophet->prophesize(ContentElementWrappingService::class);
        $this->contentElementWrappingService
            ->wrapContentObject(Argument::any(), Argument::any(), Argument::any())
            ->will(function ($args) {
                $node = $args[0];
                $content = $args[1];
                $fusionPath = $args[2];

                return vsprintf('<div class="content-element" data-node="%s" data-path="%s">%s</div>', [
                    $node->getIdentifier(),
                    $fusionPath,
                    $content
                ]);
            });

        $this->contentElementEditableService = $this->prophet->prophesize(ContentElementEditableService::class);
        $this->contentElementEditableService
            ->wrapContentProperty(Argument::any(), Argument::any(), Argument::any())
            ->will(function ($args) {
                $node = $args[0];
                $propertyName = $args[1];
                $currentValue = $args[2];

                return vsprintf('<div class="editable" data-node="%s" data-property="%s">%s</div>', [
                    $node->getIdentifier(),
                    $propertyName,
                    $currentValue
                ]);
            });

        $this->nodeTypeConstraintFactory = $this->prophet->prophesize(NodeTypeConstraintFactory::class);

        $this->factory = new class extends AbstractComponentPresentationObjectFactory {
            /**
             * @param TraversableNodeInterface $node
             * @param PresentationObjectComponentImplementation $fusionObject
             * @return callable
             */
            public function createWrapperForTest(TraversableNodeInterface $node, PresentationObjectComponentImplementation $fusionObject): callable
            {
                return $this->createWrapper($node, $fusionObject);
            }

            /**
             * @param TraversableNodeInterface $node
             * @param string $propertyName
             * @param boolean $block
             * @return string
             */
            public function getEditablePropertyForTest(TraversableNodeInterface $node, string $propertyName, bool $block): string
            {
                return $this->getEditableProperty($node, $propertyName, $block);
            }

            /**
             * @param TraversableNodeInterface $parentNode
             * @param string $nodeTypeFilterString
             * @return TraversableNodes<TraversableNodeInterface>
             */
            public function findChildNodesByNodeTypeFilterStringForTest(TraversableNodeInterface $parentNode, string $nodeTypeFilterString): TraversableNodes
            {
                return $this->findChildNodesByNodeTypeFilterString($parentNode, $nodeTypeFilterString);
            }
        };

        $this->inject($this->factory, 'contentElementWrappingService', $this->contentElementWrappingService->reveal());
        $this->inject($this->factory, 'contentElementEditableService', $this->contentElementEditableService->reveal());
        $this->inject($this->factory, 'nodeTypeConstraintFactory', $this->nodeTypeConstraintFactory->reveal());
    }

    /**
     * @after
     * @return void
     */
    public function tearDownComponentPresentationObjectFactory(): void
    {
        $this->prophet->checkPredictions();
    }

    /**
     * @test
     * @return void
     */
    public function createsWrappersForSelfWrappingTrait(): void
    {
        $content = '<p>Lorem ipsum...</p>';

        $textNode = $this->prophet
            ->prophesize(TraversableNodeInterface::class)
            ->willImplement(NodeInterface::class);
        $textNode->getIdentifier()->willReturn('text-node');

        $fusionObject = $this->prophet
            ->prophesize(PresentationObjectComponentImplementation::class);
        $fusionObject->getPath()->willReturn('/page/text');

        /** @var mixed $factory */
        $factory = $this->factory;

        $wrapper = $factory->createWrapperForTest($textNode->reveal(), $fusionObject->reveal());

        $this->assertTrue($wrapper instanceof \Closure);

        $this->assertEquals(
            '<div class="content-element" data-node="text-node" data-path="/page/text"><p>Lorem ipsum...</p></div>',
            $wrapper($content)
        );
    }

    /**
     * @test
     * @return void
     */
    public function providesInlineEditableNodeProperties(): void
    {
        $textNode = $this->prophet
            ->prophesize(TraversableNodeInterface::class)
            ->willImplement(NodeInterface::class);
        $textNode->getIdentifier()->willReturn('text-node');
        $textNode->getProperty('content')->willReturn('<p><strong>Lorem</strong> ipsum...</p>');

        /** @var mixed $factory */
        $factory = $this->factory;

        $this->assertEquals(
            '<div class="editable" data-node="text-node" data-property="content"><p><strong>Lorem</strong> ipsum...</p></div>',
            $factory->getEditablePropertyForTest($textNode->reveal(), 'content', false)
        );
    }

    /**
     * @test
     * @return void
     */
    public function providesBlockEditableNodeProperties(): void
    {
        $textNode = $this->prophet
            ->prophesize(TraversableNodeInterface::class)
            ->willImplement(NodeInterface::class);
        $textNode->getIdentifier()->willReturn('text-node');
        $textNode->getProperty('content')->willReturn('<p><strong>Lorem</strong> ipsum...</p>');

        /** @var mixed $factory */
        $factory = $this->factory;

        $this->assertEquals(
            '<div class="editable" data-node="text-node" data-property="content"><div><p><strong>Lorem</strong> ipsum...</p></div></div>',
            $factory->getEditablePropertyForTest($textNode->reveal(), 'content', true)
        );
    }

    /**
     * @test
     * @return void
     */
    public function findsChildNodesByNodeTypeFilterString(): void
    {
        $nodeTypeFilterString = 'Neos.Neos:Document,!Neos.Neos:Shortcut';
        $constraints = new NodeTypeConstraints(
            false,
            [NodeTypeName::fromString('Neos.Neos:Document')],
            [NodeTypeName::fromString('Neos.Neos:Shortcut')]
        );

        $homePageNode = $this->prophet
            ->prophesize(TraversableNodeInterface::class)
            ->willImplement(NodeInterface::class);
        $blogNode = $this->prophet
            ->prophesize(TraversableNodeInterface::class)
            ->willImplement(NodeInterface::class);
        $aboutUsNode = $this->prophet
            ->prophesize(TraversableNodeInterface::class)
            ->willImplement(NodeInterface::class);
        $imprintNode = $this->prophet
            ->prophesize(TraversableNodeInterface::class)
            ->willImplement(NodeInterface::class);

        $result = TraversableNodes::fromArray([
            $blogNode->reveal(),
            $aboutUsNode->reveal(),
            $imprintNode->reveal()
        ]);

        $this->nodeTypeConstraintFactory->parseFilterString($nodeTypeFilterString)->willReturn($constraints);
        $homePageNode->findChildNodes($constraints)->willReturn($result);

        /** @var mixed $factory */
        $factory = $this->factory;

        $this->assertSame($result, $factory->findChildNodesByNodeTypeFilterStringForTest($homePageNode->reveal(), $nodeTypeFilterString));
    }
}
