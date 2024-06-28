<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Fusion;

use Neos\ContentRepository\Core\Projection\ContentGraph\Node;
use Neos\ContentRepositoryRegistry\ContentRepositoryRegistry;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Translator;

/**
 * The generic abstract component presentation object factory implementation
 */
abstract class AbstractComponentPresentationObjectFactory implements
    ComponentPresentationObjectFactoryInterface,
    ProtectedContextAwareInterface
{
    #[Flow\Inject]
    protected ContentRepositoryRegistry $contentRepositoryRegistry;

    #[Flow\Inject]
    protected UriServiceInterface $uriService;

    #[Flow\Inject]
    protected Translator $translator;

    /**
     * @template T
     * @param class-string<T> $expectedType
     * @return ?T
     */
    final protected static function getObjectValue(Node $node, string $propertyName, string $expectedType): mixed
    {
        $propertyValue = $node->getProperty($propertyName);

        return $propertyValue instanceof $expectedType
            ? $propertyValue
            : null;
    }

    /**
     * @template T
     * @param class-string<T> $expectedType
     * @return array<int,T>|null
     */
    final protected static function getObjectArrayValue(Node $node, string $propertyName, string $expectedType): ?array
    {
        $propertyValue = $node->getProperty($propertyName);
        if (!is_array($propertyValue)) {
            return null;
        }
        return array_filter(
            $propertyValue,
            fn (mixed $item): bool => $item instanceof $expectedType
        );
    }

    final protected static function getStringValue(Node $node, string $propertyName): ?string
    {
        $propertyValue = $node->getProperty($propertyName);

        return is_string($propertyValue) ? $propertyValue : null;
    }

    final protected static function getBoolValue(Node $node, string $propertyName): ?bool
    {
        $propertyValue = $node->getProperty($propertyName);

        return is_bool($propertyValue) ? $propertyValue : null;
    }

    final protected static function getIntValue(Node $node, string $propertyName): ?int
    {
        $propertyValue = $node->getProperty($propertyName);

        return is_int($propertyValue) ? $propertyValue : null;
    }

    final protected static function getFloatValue(Node $node, string $propertyName): ?float
    {
        $propertyValue = $node->getProperty($propertyName);

        return is_float($propertyValue) ? $propertyValue : null;
    }

    /**
     * All methods are considered safe
     */
    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }
}
