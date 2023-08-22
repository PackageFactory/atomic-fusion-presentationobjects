<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use Neos\ContentRepository\Core\Projection\ContentGraph\Nodes;
use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final readonly class Collection implements SlotInterface
{
    /**
     * @var array<int,SlotInterface>
     */
    public array $items;

    private function __construct(SlotInterface ...$items)
    {
        /** @var array<int,SlotInterface> $items */
        $this->items = $items;
    }

    public static function fromSlots(SlotInterface ...$items): self
    {
        return new self(...$items);
    }

    /**
     * @param iterable<mixed> $iterable
     */
    public static function fromIterable(iterable $iterable, ?callable $itemRenderer = null): self
    {
        $items = [];
        $iteration = Iteration::fromIterable($iterable);
        $itemRenderer = $itemRenderer ?? function ($any): ?SlotInterface {
            if (is_null($any)) {
                return null;
            } elseif ($any instanceof SlotInterface) {
                return $any;
            }
            return Value::fromAny($any);
        };

        /** @var array<int,mixed> $current */
        $current = null;
        $started = false;
        foreach ($iterable as $key => $item) {
            if (is_null($item)) {
                continue;
            }
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

    public static function fromNodes(Nodes $nodes, ?callable $itemRenderer = null): self
    {
        $itemRenderer = $itemRenderer ?? function (Node $node): Content {
            return Content::fromNode($node);
        };

        return self::fromIterable($nodes, $itemRenderer);
    }

    /**
     * @return array<int,SlotInterface>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public static function getIdentityFunction(): \Closure
    {
        return fn (SlotInterface $slot): SlotInterface => $slot;
    }

    public function getPrototypeName(): string
    {
        return 'PackageFactory.AtomicFusion.PresentationObjects:Collection';
    }
}
