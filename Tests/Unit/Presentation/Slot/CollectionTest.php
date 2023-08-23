<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Presentation\Slot;

use Neos\ContentRepository\Core\NodeType\NodeType;
use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use Neos\ContentRepository\Core\Projection\ContentGraph\Nodes;
use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Collection;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Iteration;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\SlotInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;
use Vendor\Site\Presentation\Component\Text\Text;

final class CollectionTest extends UnitTestCase
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

    public function testCanBeCreatedFromIterables(): void
    {
        $collection = Collection::fromIterable(['Foo', 'Bar', 'Baz', 'Qux']);

        $this->assertInstanceOf(Collection::class, $collection);

        /** @var Value[] $items */
        $items = $collection->items;

        $this->assertInstanceOf(Value::class, $items[0]);
        $this->assertEquals('Foo', (string) $items[0]);

        $this->assertInstanceOf(Value::class, $items[1]);
        $this->assertEquals('Bar', (string) $items[1]);

        $this->assertInstanceOf(Value::class, $items[2]);
        $this->assertEquals('Baz', (string) $items[2]);

        $this->assertInstanceOf(Value::class, $items[3]);
        $this->assertEquals('Qux', (string) $items[3]);
    }

    public function testAllowsForCustomItemRenderingForIterables(): void
    {
        $collection = Collection::fromIterable(
            ['Foo', 'Bar', 'Baz', 'Qux'],
            function (string $item): Value {
                return Value::fromString('(' . $item . ')');
            }
        );

        $this->assertInstanceOf(Collection::class, $collection);

        /** @var Value[] $items */
        $items = $collection->items;

        $this->assertInstanceOf(Value::class, $items[0]);
        $this->assertEquals('(Foo)', (string) $items[0]);

        $this->assertInstanceOf(Value::class, $items[1]);
        $this->assertEquals('(Bar)', (string) $items[1]);

        $this->assertInstanceOf(Value::class, $items[2]);
        $this->assertEquals('(Baz)', (string) $items[2]);

        $this->assertInstanceOf(Value::class, $items[3]);
        $this->assertEquals('(Qux)', (string) $items[3]);
    }

    public function testProvidesIterationInfoForIterables(): void
    {
        $keys = [];
        /** @var array<int,Iteration> $iterations */
        $iterations = [];

        Collection::fromIterable(
            ['Foo', 'Bar', 'Baz', 'Qux'],
            function (string $item, int $key, Iteration $it) use (&$keys, &$iterations): Value {
                $keys[] = $key;
                $iterations[] = $it;

                return Value::fromString('(' . $item . ')');
            }
        );

        $this->assertCount(4, $keys);
        $this->assertCount(4, $iterations);

        $this->assertEquals(0, $keys[0]);
        $this->assertEquals(0, $iterations[0]->index);
        $this->assertEquals(1, $iterations[0]->getCycle());
        $this->assertEquals(4, $iterations[0]->count);
        $this->assertEquals(true, $iterations[0]->isFirst);
        $this->assertEquals(false, $iterations[0]->isLast);
        $this->assertEquals(true, $iterations[0]->isOdd());
        $this->assertEquals(false, $iterations[0]->isEven());

        $this->assertEquals(1, $keys[1]);
        $this->assertEquals(1, $iterations[1]->index);
        $this->assertEquals(2, $iterations[1]->getCycle());
        $this->assertEquals(4, $iterations[1]->count);
        $this->assertEquals(false, $iterations[1]->isFirst);
        $this->assertEquals(false, $iterations[1]->isLast);
        $this->assertEquals(false, $iterations[1]->isOdd());
        $this->assertEquals(true, $iterations[1]->isEven());

        $this->assertEquals(2, $keys[2]);
        $this->assertEquals(2, $iterations[2]->index);
        $this->assertEquals(3, $iterations[2]->getCycle());
        $this->assertEquals(4, $iterations[2]->count);
        $this->assertEquals(false, $iterations[2]->isFirst);
        $this->assertEquals(false, $iterations[2]->isLast);
        $this->assertEquals(true, $iterations[2]->isOdd());
        $this->assertEquals(false, $iterations[2]->isEven());

        $this->assertEquals(3, $keys[3]);
        $this->assertEquals(3, $iterations[3]->index);
        $this->assertEquals(4, $iterations[3]->getCycle());
        $this->assertEquals(4, $iterations[3]->count);
        $this->assertEquals(false, $iterations[3]->isFirst);
        $this->assertEquals(true, $iterations[3]->isLast);
        $this->assertEquals(false, $iterations[3]->isOdd());
        $this->assertEquals(true, $iterations[3]->isEven());
    }

    public function testKeepsSlotsInIterables(): void
    {
        $collection = Collection::fromIterable(
            [new Text('Text')]
        );

        $this->assertInstanceOf(Collection::class, $collection);

        /** @var array<int,Text> $items */
        $items = $collection->getItems();

        $this->assertInstanceOf(Text::class, $items[0]);
        $this->assertEquals('Text', $items[0]->text);
    }

    public function testAcceptsButRemovesNullValuesInIterables(): void
    {
        $collection = Collection::fromIterable([
            new Text('Text'),
            null,
            'Foo'
        ]);

        $this->assertInstanceOf(Collection::class, $collection);

        /** @var array<int,SlotInterface> $items */
        $items = $collection->getItems();

        $this->assertSame(2, count($items));

        $firstItem = $items[0];
        $this->assertInstanceOf(Text::class, $firstItem);
        /** @var Text $firstItem */
        $this->assertEquals('Text', $firstItem->text);

        $secondItem = $items[1];
        $this->assertInstanceOf(Value::class, $secondItem);
        /** @var Value $secondItem */
        $this->assertEquals('Foo', (string) $secondItem);
    }

    public function testCanBeCreatedFromNodes(): void
    {
        $this->markTestSkipped('Cannot mock nodes yet');
        $makeNode = function (string $nodeTypeName): ObjectProphecy {
            $nodeType = $this->prophet->prophesize(NodeType::class);
            $nodeType->getName()->willReturn($nodeTypeName);

            $node = $this->prophet->prophesize(Node::class);
            $node->nodeType->willReturn($nodeType->reveal());

            return $node;
        };

        $keyVisualNode = $makeNode('Vendor.Site:Content.KeyVisual');
        $deckNode = $makeNode('Vendor.Site:Content.Deck');
        $newsletterSubscriptionNode = $makeNode('Vendor.Site:Content.NewsletterSubscription');

        $nodes = Nodes::fromArray([
            $keyVisualNode->reveal(),
            $deckNode->reveal(),
            $newsletterSubscriptionNode->reveal()
        ]);

        $collection = Collection::fromNodes($nodes);

        $this->assertInstanceOf(Collection::class, $collection);

        /** @var Content[] $items */
        $items = $collection->items;

        $this->assertInstanceOf(Content::class, $items[0]);
        $this->assertSame($keyVisualNode->reveal(), $items[0]->contentNode);
        $this->assertEquals('Vendor.Site:Content.KeyVisual', $items[0]->contentPrototypeName);

        $this->assertInstanceOf(Content::class, $items[1]);
        $this->assertSame($deckNode->reveal(), $items[1]->contentNode);
        $this->assertEquals('Vendor.Site:Content.Deck', $items[1]->contentPrototypeName);

        $this->assertInstanceOf(Content::class, $items[2]);
        $this->assertSame($newsletterSubscriptionNode->reveal(), $items[2]->contentNode);
        $this->assertEquals('Vendor.Site:Content.NewsletterSubscription', $items[2]->contentPrototypeName);
    }

    public function testAllowsForCustomItemRenderingForNodes(): void
    {
        $this->markTestSkipped('Cannot mock nodes yet');
        $makeNode = function (string $nodeTypeName): ObjectProphecy {
            $nodeType = $this->prophet->prophesize(NodeType::class);
            $nodeType->getName()->willReturn($nodeTypeName);

            $node = $this->prophet->prophesize(Node::class);
            $node->nodeType->willReturn($nodeType->reveal());

            return $node;
        };

        $keyVisualNode = $makeNode('Vendor.Site:Content.KeyVisual');
        $deckNode = $makeNode('Vendor.Site:Content.Deck');
        $newsletterSubscriptionNode = $makeNode('Vendor.Site:Content.NewsletterSubscription');

        $nodes = Nodes::fromArray([
            $keyVisualNode->reveal(),
            $deckNode->reveal(),
            $newsletterSubscriptionNode->reveal()
        ]);

        $collection = Collection::fromNodes(
            $nodes,
            function (Node $node): Value {
                return Value::fromString($node->nodeTypeName->value);
            }
        );

        $this->assertInstanceOf(Collection::class, $collection);

        /** @var Value[] $items */
        $items = $collection->items;

        $this->assertInstanceOf(Value::class, $items[0]);
        $this->assertEquals('Vendor.Site:Content.KeyVisual', (string) $items[0]);

        $this->assertInstanceOf(Value::class, $items[1]);
        $this->assertEquals('Vendor.Site:Content.Deck', (string) $items[1]);

        $this->assertInstanceOf(Value::class, $items[2]);
        $this->assertEquals('Vendor.Site:Content.NewsletterSubscription', (string) $items[2]);
    }

    public function testProvidesIterationInfoForNodes(): void
    {
        $this->markTestSkipped('Cannot mock nodes yet');
        $keys = [];
        /** @var array<int,Iteration> $iterations */
        $iterations = [];

        $makeNode = function (string $nodeTypeName): ObjectProphecy {
            $nodeType = $this->prophet->prophesize(NodeType::class);
            $nodeType->getName()->willReturn($nodeTypeName);

            $node = $this->prophet->prophesize(Node::class);
            $node->nodeType->willReturn($nodeType->reveal());

            return $node;
        };

        $keyVisualNode = $makeNode('Vendor.Site:Content.KeyVisual');
        $deckNode = $makeNode('Vendor.Site:Content.Deck');
        $newletterSubscriptionNode = $makeNode('Vendor.Site:Content.NewsletterSubscription');

        $nodes = Nodes::fromArray([
            $keyVisualNode->reveal(),
            $deckNode->reveal(),
            $newletterSubscriptionNode->reveal()
        ]);

        Collection::fromNodes(
            $nodes,
            function (Node $node, int $key, Iteration $it) use (&$keys, &$iterations): Value {
                $keys[] = $key;
                $iterations[] = $it;

                return Value::fromString($node->nodeTypeName->value);
            }
        );

        $this->assertCount(3, $keys);
        $this->assertCount(3, $iterations);

        $this->assertEquals(0, $keys[0]);
        $this->assertEquals(0, $iterations[0]->index);
        $this->assertEquals(1, $iterations[0]->getCycle());
        $this->assertEquals(true, $iterations[0]->isFirst);
        $this->assertEquals(false, $iterations[0]->isLast);
        $this->assertEquals(true, $iterations[0]->isOdd());
        $this->assertEquals(false, $iterations[0]->isEven());

        $this->assertEquals(1, $keys[1]);
        $this->assertEquals(1, $iterations[1]->index);
        $this->assertEquals(2, $iterations[1]->getCycle());
        $this->assertEquals(false, $iterations[1]->isFirst);
        $this->assertEquals(false, $iterations[1]->isLast);
        $this->assertEquals(false, $iterations[1]->isOdd());
        $this->assertEquals(true, $iterations[1]->isEven());

        $this->assertEquals(2, $keys[2]);
        $this->assertEquals(2, $iterations[2]->index);
        $this->assertEquals(3, $iterations[2]->getCycle());
        $this->assertEquals(false, $iterations[2]->isFirst);
        $this->assertEquals(true, $iterations[2]->isLast);
        $this->assertEquals(true, $iterations[2]->isOdd());
        $this->assertEquals(false, $iterations[2]->isEven());
    }

    public function testIsRenderedAsCollectionFusionPrototype(): void
    {
        $collection = Collection::fromIterable([]);
        $this->assertEquals('PackageFactory.AtomicFusion.PresentationObjects:Collection', $collection->getPrototypeName());
    }
}
