<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Aspect;

use Neos\Eel\ProtectedContext;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Aop\JoinPointInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Infrastructure\PresentationObjectAwareContext;
use PackageFactory\AtomicFusion\PresentationObjects\Infrastructure\PresentationObjectAwareProtectedContext;

#[Flow\Aspect]
final class CompilingEvaluatorPreprocessor
{
    #[Flow\Before('method(Neos\Eel\CompilingEvaluator->evaluate())')]
    public function preprocessEvaluate(JoinPointInterface $joinPoint): void
    {
        $context = $joinPoint->getMethodArgument('context');
        $joinPoint->setMethodArgument(
            'context',
            $context instanceof ProtectedContext
                ? PresentationObjectAwareProtectedContext::fromContext($context)
                : PresentationObjectAwareContext::fromContext($context)
        );
    }
}
