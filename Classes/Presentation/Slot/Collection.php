<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class Collection implements CollectionInterface
{
    /**
     * @var array|SlotInterface[]
     */
    private $items;

    /**
     * @param SlotInterface ...$items
     */
    public function __construct(SlotInterface ...$items)
    {
        $this->items = $items;
    }

    public static function fromSlots(SlotInterface ...$items)
    {
        return new self(...$items);
    }

    /**
     * @param iterable<mixed> $iterable
     * @param callable|null $itemRenderer
     * @return self
     */
    public static function fromIterable(iterable $iterable, ?callable $itemRenderer = null): self
    {
        $items = [];
        $iteration = Iteration::fromIterable($iterable);
        $itemRenderer = $itemRenderer ?? function ($any): ValueInterface {
            return Value::fromAny($any);
        };

        $current = null;
        $started = false;
        foreach ($iterable as $key => $item) {
            if ($started) {
                $items[] = $itemRenderer($current[0], $current[1], $iteration);
                $iteration = $iteration->next();
            }

            $current = [$item, $key];
            $started = true;
        }

        if ($started) {
            $iteration = $iteration->last();
            $items[] = $itemRenderer($current[0], $current[1], $iteration);
        }

        return new self(...$items);
    }

    /**
     * @param TraversableNodes<mixed> $nodes
     * @param null|callable $itemRenderer
     * @return self
     */
    public static function fromNodes(TraversableNodes $nodes, ?callable $itemRenderer = null): self
    {
        $itemRenderer = $itemRenderer ?? function (TraversableNodeInterface $node): ContentInterface {
            return Content::fromNode($node);
        };

        return self::fromIterable($nodes, $itemRenderer);
    }

    /**
     * @return array|SlotInterface[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public static function getIdentityFunction(): \Closure
    {
        return fn (SlotInterface $slot): SlotInterface => $slot;
    }

    /**
     * @return string
     */
    public function getPrototypeName(): string
    {
        return 'PackageFactory.AtomicFusion.PresentationObjects:Collection';
    }
}
