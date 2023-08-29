<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Aspect;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Aop\JoinPointInterface;
use Neos\Flow\Mvc\Controller\ControllerContext;
use PackageFactory\AtomicFusion\PresentationObjects\Infrastructure\UriService;

#[Flow\Aspect]
#[Flow\Scope('singleton')]
final class UriServiceInitializationAspect
{
    #[Flow\Inject]
    protected UriService $uriService;

    #[Flow\Before('method(Neos\Flow\Mvc\View\AbstractView->setControllerContext())')]
    public function relayControllerContext(JoinPointInterface $joinPoint): void
    {
        /** @var ControllerContext $controllerContext */
        $controllerContext = $joinPoint->getMethodArgument('controllerContext');
        $this->uriService->useControllerContext($controllerContext);
    }
}
