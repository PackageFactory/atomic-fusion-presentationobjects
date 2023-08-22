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
use Neos\Neos\Service\ContentElementEditableService;
use Neos\Neos\Service\ContentElementWrappingService;

/**
 * The generic abstract component presentation object factory implementation
 */
abstract class AbstractComponentPresentationObjectFactory implements
    ComponentPresentationObjectFactoryInterface,
    ProtectedContextAwareInterface
{
    /**
     * @Flow\Inject
     * @var ContentElementWrappingService
     */
    protected $contentElementWrappingService;

    /**
     * @Flow\Inject
     * @var ContentElementEditableService
     */
    protected $contentElementEditableService;

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
     * @param TraversableNodeInterface $node
     * @param PresentationObjectComponentImplementation $fusionObject
     * @return callable
     * @deprecated since 3.0 Use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Content for content integration pusposes instead
     */
    final protected function createWrapper(TraversableNodeInterface $node, PresentationObjectComponentImplementation $fusionObject): callable
    {
        $wrappingService = $this->contentElementWrappingService;

        return function (string $content) use ($node, $fusionObject, $wrappingService) {
            /** @var NodeInterface $node */
            return $wrappingService->wrapContentObject($node, $content, $fusionObject->getPath());
        };
    }

    /**
     * @param TraversableNodeInterface $node
     * @param string $propertyName
     * @param boolean $block
     * @return string
     * @deprecated since 3.0 Use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Editable for editable property integration pusposes instead
     */
    final protected function getEditableProperty(TraversableNodeInterface $node, string $propertyName, bool $block = false): string
    {
        /** @var NodeInterface $node */
        return $this->contentElementEditableService->wrapContentProperty(
            $node,
            $propertyName,
            ($block ? '<div>' : '')
                . ($node->getProperty($propertyName) ?: '')
                . ($block ? '</div>' : '')
        );
    }

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
