<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Presentation\Slot;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Model\NodeType;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\CollectionInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\ContentInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Iteration;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\ValueInterface;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;
use Vendor\Site\Presentation\Component\Text\Text;
use Vendor\Site\Presentation\Component\Text\TextInterface;

final class CollectionTest extends UnitTestCase
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
    public function canBeCreatedFromIterables(): void
    {
        $collection = Collection::fromIterable(['Foo', 'Bar', 'Baz', 'Qux']);

        $this->assertInstanceOf(CollectionInterface::class, $collection);

        /** @var ValueInterface[] $items */
        $items = $collection->getItems();

        $this->assertInstanceOf(ValueInterface::class, $items[0]);
        $this->assertEquals('Foo', (string) $items[0]);

        $this->assertInstanceOf(ValueInterface::class, $items[1]);
        $this->assertEquals('Bar', (string) $items[1]);

        $this->assertInstanceOf(ValueInterface::class, $items[2]);
        $this->assertEquals('Baz', (string) $items[2]);

        $this->assertInstanceOf(ValueInterface::class, $items[3]);
        $this->assertEquals('Qux', (string) $items[3]);
    }

    /**
     * @test
     * @return void
     */
    public function allowsForCustomItemRenderingForIterables(): void
    {
        $collection = Collection::fromIterable(
            ['Foo', 'Bar', 'Baz', 'Qux'],
            function (string $item): ValueInterface {
                return Value::fromString('(' . $item . ')');
            }
        );

        $this->assertInstanceOf(CollectionInterface::class, $collection);

        /** @var ValueInterface[] $items */
        $items = $collection->getItems();

        $this->assertInstanceOf(ValueInterface::class, $items[0]);
        $this->assertEquals('(Foo)', (string) $items[0]);

        $this->assertInstanceOf(ValueInterface::class, $items[1]);
        $this->assertEquals('(Bar)', (string) $items[1]);

        $this->assertInstanceOf(ValueInterface::class, $items[2]);
        $this->assertEquals('(Baz)', (string) $items[2]);

        $this->assertInstanceOf(ValueInterface::class, $items[3]);
        $this->assertEquals('(Qux)', (string) $items[3]);
    }

    /**
     * @test
     * @return void
     */
    public function providesIterationInfoForIterables(): void
    {
        $keys = [];
        $iterations = [];

        Collection::fromIterable(
            ['Foo', 'Bar', 'Baz', 'Qux'],
            function (string $item, int $key, Iteration $it) use (&$keys, &$iterations): ValueInterface {
                $keys[] = $key;
                $iterations[] = $it;

                return Value::fromString('(' . $item . ')');
            }
        );

        $this->assertCount(4, $keys);
        $this->assertCount(4, $iterations);

        $this->assertEquals(0, $keys[0]);
        $this->assertEquals(0, $iterations[0]->getIndex());
        $this->assertEquals(1, $iterations[0]->getCycle());
        $this->assertEquals(4, $iterations[0]->getCount());
        $this->assertEquals(true, $iterations[0]->isFirst());
        $this->assertEquals(false, $iterations[0]->isLast());
        $this->assertEquals(true, $iterations[0]->isOdd());
        $this->assertEquals(false, $iterations[0]->isEven());

        $this->assertEquals(1, $keys[1]);
        $this->assertEquals(1, $iterations[1]->getIndex());
        $this->assertEquals(2, $iterations[1]->getCycle());
        $this->assertEquals(4, $iterations[1]->getCount());
        $this->assertEquals(false, $iterations[1]->isFirst());
        $this->assertEquals(false, $iterations[1]->isLast());
        $this->assertEquals(false, $iterations[1]->isOdd());
        $this->assertEquals(true, $iterations[1]->isEven());

        $this->assertEquals(2, $keys[2]);
        $this->assertEquals(2, $iterations[2]->getIndex());
        $this->assertEquals(3, $iterations[2]->getCycle());
        $this->assertEquals(4, $iterations[2]->getCount());
        $this->assertEquals(false, $iterations[2]->isFirst());
        $this->assertEquals(false, $iterations[2]->isLast());
        $this->assertEquals(true, $iterations[2]->isOdd());
        $this->assertEquals(false, $iterations[2]->isEven());

        $this->assertEquals(3, $keys[3]);
        $this->assertEquals(3, $iterations[3]->getIndex());
        $this->assertEquals(4, $iterations[3]->getCycle());
        $this->assertEquals(4, $iterations[3]->getCount());
        $this->assertEquals(false, $iterations[3]->isFirst());
        $this->assertEquals(true, $iterations[3]->isLast());
        $this->assertEquals(false, $iterations[3]->isOdd());
        $this->assertEquals(true, $iterations[3]->isEven());
    }

    /**
     * @test
     * @return void
     */
    public function keepsSlotsInIterables(): void
    {
        $collection = Collection::fromIterable(
            [new Text('Text')]
        );

        $this->assertInstanceOf(CollectionInterface::class, $collection);

        /** @var array<int,TextInterface> $items */
        $items = $collection->getItems();

        $this->assertInstanceOf(Text::class, $items[0]);
        $this->assertEquals('Text', $items[0]->getText());
    }

    /**
     * @test
     * @return void
     */
    public function acceptsButRemovesNullValuesInIterables(): void
    {
        $collection = Collection::fromIterable([
            new Text('Text'),
            null,
            'Foo'
        ]);

        $this->assertInstanceOf(CollectionInterface::class, $collection);

        /** @var array<int,SlotInterface> $items */
        $items = $collection->getItems();

        $this->assertSame(2, count($items));

        $firstItem = $items[0];
        $this->assertInstanceOf(Text::class, $firstItem);
        /** @var Text $firstItem */
        $this->assertEquals('Text', $firstItem->getText());

        $secondItem = $items[1];
        $this->assertInstanceOf(ValueInterface::class, $secondItem);
        /** @var ValueInterface $secondItem */
        $this->assertEquals('Foo', (string) $secondItem);
    }

    /**
     * @test
     * @return void
     */
    public function canBeCreatedFromTraversableNodes(): void
    {
        $makeNode = function (string $nodeTypeName): ObjectProphecy {
            $nodeType = $this->prophet->prophesize(NodeType::class);
            $nodeType->getName()->willReturn($nodeTypeName);

            $node = $this->prophet
                ->prophesize(TraversableNodeInterface::class)
                ->willImplement(NodeInterface::class);
            $node->getNodeType()->willReturn($nodeType->reveal());

            return $node;
        };

        $keyVisualNode = $makeNode('Vendor.Site:Content.KeyVisual');
        $deckNode = $makeNode('Vendor.Site:Content.Deck');
        $newsletterSubscriptionNode = $makeNode('Vendor.Site:Content.NewsletterSubscription');

        $nodes = TraversableNodes::fromArray([
            $keyVisualNode->reveal(),
            $deckNode->reveal(),
            $newsletterSubscriptionNode->reveal()
        ]);

        $collection = Collection::fromNodes($nodes);

        $this->assertInstanceOf(CollectionInterface::class, $collection);

        /** @var ContentInterface[] $items */
        $items = $collection->getItems();

        $this->assertInstanceOf(ContentInterface::class, $items[0]);
        $this->assertSame($keyVisualNode->reveal(), $items[0]->getContentNode());
        $this->assertEquals('Vendor.Site:Content.KeyVisual', $items[0]->getContentPrototypeName());

        $this->assertInstanceOf(ContentInterface::class, $items[1]);
        $this->assertSame($deckNode->reveal(), $items[1]->getContentNode());
        $this->assertEquals('Vendor.Site:Content.Deck', $items[1]->getContentPrototypeName());

        $this->assertInstanceOf(ContentInterface::class, $items[2]);
        $this->assertSame($newsletterSubscriptionNode->reveal(), $items[2]->getContentNode());
        $this->assertEquals('Vendor.Site:Content.NewsletterSubscription', $items[2]->getContentPrototypeName());
    }

    /**
     * @test
     * @return void
     */
    public function allowsForCustomItemRenderingForTraversableNodes(): void
    {
        $makeNode = function (string $nodeTypeName): ObjectProphecy {
            $nodeType = $this->prophet->prophesize(NodeType::class);
            $nodeType->getName()->willReturn($nodeTypeName);

            $node = $this->prophet
                ->prophesize(TraversableNodeInterface::class)
                ->willImplement(NodeInterface::class);
            $node->getNodeType()->willReturn($nodeType->reveal());

            return $node;
        };

        $keyVisualNode = $makeNode('Vendor.Site:Content.KeyVisual');
        $deckNode = $makeNode('Vendor.Site:Content.Deck');
        $newsletterSubscriptionNode = $makeNode('Vendor.Site:Content.NewsletterSubscription');

        $nodes = TraversableNodes::fromArray([
            $keyVisualNode->reveal(),
            $deckNode->reveal(),
            $newsletterSubscriptionNode->reveal()
        ]);

        $collection = Collection::fromNodes(
            $nodes,
            function (TraversableNodeInterface $node): ValueInterface {
                return Value::fromString($node->getNodeType()->getName());
            }
        );

        $this->assertInstanceOf(CollectionInterface::class, $collection);

        /** @var ValueInterface[] $items */
        $items = $collection->getItems();

        $this->assertInstanceOf(ValueInterface::class, $items[0]);
        $this->assertEquals('Vendor.Site:Content.KeyVisual', (string) $items[0]);

        $this->assertInstanceOf(ValueInterface::class, $items[1]);
        $this->assertEquals('Vendor.Site:Content.Deck', (string) $items[1]);

        $this->assertInstanceOf(ValueInterface::class, $items[2]);
        $this->assertEquals('Vendor.Site:Content.NewsletterSubscription', (string) $items[2]);
    }

    /**
     * @test
     * @return void
     */
    public function providesIterationInfoForTraversableNodes(): void
    {
        $keys = [];
        $iterations = [];

        $makeNode = function (string $nodeTypeName): ObjectProphecy {
            $nodeType = $this->prophet->prophesize(NodeType::class);
            $nodeType->getName()->willReturn($nodeTypeName);

            $node = $this->prophet
                ->prophesize(TraversableNodeInterface::class)
                ->willImplement(NodeInterface::class);
            $node->getNodeType()->willReturn($nodeType->reveal());

            return $node;
        };

        $keyVisualNode = $makeNode('Vendor.Site:Content.KeyVisual');
        $deckNode = $makeNode('Vendor.Site:Content.Deck');
        $newletterSubscriptionNode = $makeNode('Vendor.Site:Content.NewsletterSubscription');

        $nodes = TraversableNodes::fromArray([
            $keyVisualNode->reveal(),
            $deckNode->reveal(),
            $newletterSubscriptionNode->reveal()
        ]);

        Collection::fromNodes(
            $nodes,
            function (TraversableNodeInterface $node, int $key, Iteration $it) use (&$keys, &$iterations): ValueInterface {
                $keys[] = $key;
                $iterations[] = $it;

                return Value::fromString($node->getNodeType()->getName());
            }
        );

        $this->assertCount(3, $keys);
        $this->assertCount(3, $iterations);

        $this->assertEquals(0, $keys[0]);
        $this->assertEquals(0, $iterations[0]->getIndex());
        $this->assertEquals(1, $iterations[0]->getCycle());
        $this->assertEquals(true, $iterations[0]->isFirst());
        $this->assertEquals(false, $iterations[0]->isLast());
        $this->assertEquals(true, $iterations[0]->isOdd());
        $this->assertEquals(false, $iterations[0]->isEven());

        $this->assertEquals(1, $keys[1]);
        $this->assertEquals(1, $iterations[1]->getIndex());
        $this->assertEquals(2, $iterations[1]->getCycle());
        $this->assertEquals(false, $iterations[1]->isFirst());
        $this->assertEquals(false, $iterations[1]->isLast());
        $this->assertEquals(false, $iterations[1]->isOdd());
        $this->assertEquals(true, $iterations[1]->isEven());

        $this->assertEquals(2, $keys[2]);
        $this->assertEquals(2, $iterations[2]->getIndex());
        $this->assertEquals(3, $iterations[2]->getCycle());
        $this->assertEquals(false, $iterations[2]->isFirst());
        $this->assertEquals(true, $iterations[2]->isLast());
        $this->assertEquals(true, $iterations[2]->isOdd());
        $this->assertEquals(false, $iterations[2]->isEven());
    }

    /**
     * @test
     * @return void
     */
    public function isRenderedAsCollectionFusionPrototype(): void
    {
        $collection = Collection::fromIterable([]);
        $this->assertEquals('PackageFactory.AtomicFusion.PresentationObjects:Collection', $collection->getPrototypeName());
    }
}
