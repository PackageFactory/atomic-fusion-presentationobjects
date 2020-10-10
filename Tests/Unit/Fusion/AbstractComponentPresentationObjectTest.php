<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Fusion;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\AbstractComponentPresentationObject;

/**
 * Test for the AbstractComponentPresentationObject
 */
final class AbstractComponentPresentationObjectTest extends UnitTestCase
{
    /**
     * @test
     * @small
     * @return void
     */
    public function enforcesStructuralPropertyAccessToCircumventFaultToleranceInEel(): void
    {
        $this->expectException(\BadMethodCallException::class);

        $presentationObject = new class extends AbstractComponentPresentationObject {
        };

        $presentationObject->getFoo();
    }
}
