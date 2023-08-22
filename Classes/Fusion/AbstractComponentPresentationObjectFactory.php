<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Fusion;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\NodeType\NodeTypeConstraintFactory;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodeInterface;
use Neos\ContentRepository\Domain\Projection\Content\TraversableNodes;
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
    protected UriServiceInterface $uriService;

    /**
     * @Flow\Inject
     * @var Translator
     */
    protected $translator;

    /**
     * @Flow\Inject
     * @var NodeTypeConstraintFactory
     */
    protected $nodeTypeConstraintFactory;

    /**
     * @param TraversableNodeInterface $parentNode
     * @param string $nodeTypeFilterString
     * @phpstan-return TraversableNodes<TraversableNodeInterface>
     * @return TraversableNodes
     */
    final protected function findChildNodesByNodeTypeFilterString(TraversableNodeInterface $parentNode, string $nodeTypeFilterString): TraversableNodes
    {
        return $parentNode->findChildNodes($this->nodeTypeConstraintFactory->parseFilterString($nodeTypeFilterString));
    }

    /**
     * All methods are considered safe
     *
     * @param string $methodName
     * @return boolean
     */
    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }
}
