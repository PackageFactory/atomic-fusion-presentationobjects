<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Fusion;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Tests\UnitTestCase;
use Neos\Fusion\Core\Runtime;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectDoesNotImplementRequiredInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterfaceIsMissing;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterfaceIsUndeclared;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectIsMissing;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\PresentationObjectComponentImplementation;
use Prophecy\Prophet;

/**
 * Test cases for the PresentationObjectComponentImplementation
 */
class PresentationObjectComponentImplementationTest extends UnitTestCase
{
    /**
     * @var Prophet
     */
    private $prophet;

    /**
     * @before
     * @return void
     */
    public function setUpPresentationObjectComponentImplementation(): void
    {
        $this->prophet = new Prophet();
    }

    /**
     * @after
     * @return void
     */
    public function tearDownPresentationObjectComponentImplementation(): void
    {
        $this->prophet->checkPredictions();
    }

    /**
     * @test
     * @throws \ReflectionException
     * @return void
     */
    public function prepareProperlyMergesPropsToStubbedPresentationObjectInPreviewMode(): void
    {
        $runtime = $this->prophet->prophesize(Runtime::class);

        $subject = new PresentationObjectComponentImplementation(
            $runtime->reveal(),
            'test',
            'My.Package:Component'
        );
        $subject['foo'] = 'bar';

        $runtime
            ->getCurrentContext()
            ->willReturn([]);
        $runtime
            ->evaluate('test/foo', $subject)
            ->willReturn('bar');

        $context = $this->getPrepare()->invokeArgs($subject, [[]]);

        $this->assertSame(['foo' => 'bar'], $context['props']);
        $this->assertSame(['foo' => 'bar'], $context[PresentationObjectComponentImplementation::OBJECT_NAME]);
    }

    /**
     * @test
     * @throws \ReflectionException
     * @return void
     */
    public function prepareWritesPresentationObjectToContextWhenNotInPreviewMode(): void
    {
        $runtime = $this->prophet->prophesize(Runtime::class);
        $presentationObject = $this->prophet->prophesize(ComponentPresentationObjectInterface::class);

        $subject = new PresentationObjectComponentImplementation(
            $runtime->reveal(),
            'test',
            'My.Package:Component'
        );

        $runtime
            ->getCurrentContext()
            ->willReturn([]);
        $runtime
            ->evaluate('test/' . PresentationObjectComponentImplementation::OBJECT_NAME, $subject)
            ->willReturn($presentationObject);
        $runtime
            ->evaluate('test/' . PresentationObjectComponentImplementation::INTERFACE_DECLARATION_NAME, $subject)
            ->willReturn(ComponentPresentationObjectInterface::class);

        $context = $this->getPrepare()->invokeArgs($subject, [[]]);

        $this->assertSame(
            $presentationObject->reveal(),
            $context[PresentationObjectComponentImplementation::OBJECT_NAME]
        );
    }

    /**
     * @return \ReflectionMethod
     * @throws \ReflectionException
     */
    protected function getPrepare(): \ReflectionMethod
    {
        $reflection = new \ReflectionClass(PresentationObjectComponentImplementation::class);
        $prepare = $reflection->getMethod('prepare');
        $prepare->setAccessible(true);

        return $prepare;
    }

    /**
     * @test
     * @return void
     */
    public function publishesItsOwnPath(): void
    {
        $subject = new PresentationObjectComponentImplementation(
            $this->prophet->prophesize(Runtime::class)->reveal(),
            'path/to/button/integration',
            'Vendor.Site:Component.Button'
        );

        $this->assertEquals('path/to/button/integration', $subject->getPath());
    }

    /**
     * @test
     * @return void
     */
    public function evaluateThrowsExceptionWhenNotInPreviewModeAndWithoutGivenPresentationObject(): void
    {
        $this->expectException(ComponentPresentationObjectIsMissing::class);

        $runtime = $this->prophet->prophesize(Runtime::class);
        $subject = new PresentationObjectComponentImplementation(
            $runtime->reveal(),
            'test',
            'My.Package:Component'
        );

        $runtime
            ->getCurrentContext()
            ->willReturn([]);
        $runtime
            ->evaluate('test/' . PresentationObjectComponentImplementation::OBJECT_NAME, $subject)
            ->willReturn(null);

        $this->expectException(ComponentPresentationObjectIsMissing::class);

        $subject->evaluate();
    }

    /**
     * @test
     * @return void
     */
    public function evaluateThrowsExceptionWhenNotInPreviewModeAndWithoutDeclaredPresentationObjectInterface(): void
    {
        $this->expectException(ComponentPresentationObjectInterfaceIsUndeclared::class);

        $runtime = $this->prophet->prophesize(Runtime::class);
        $subject = new PresentationObjectComponentImplementation(
            $runtime->reveal(),
            'test',
            'My.Package:Component'
        );

        $runtime
            ->getCurrentContext()
            ->willReturn([]);
        $runtime
            ->evaluate('test/' . PresentationObjectComponentImplementation::OBJECT_NAME, $subject)
            ->willReturn(new \DateTimeImmutable);
        $runtime
            ->evaluate('test/' . PresentationObjectComponentImplementation::INTERFACE_DECLARATION_NAME, $subject)
            ->willReturn(null);

        $this->expectException(ComponentPresentationObjectInterfaceIsUndeclared::class);

        $subject->evaluate();
    }

    /**
     * @test
     * @return void
     */
    public function evaluateThrowsExceptionWhenNotInPreviewModeAndWithoutExistingPresentationObjectInterface(): void
    {
        $this->expectException(ComponentPresentationObjectInterfaceIsMissing::class);

        $runtime = $this->prophet->prophesize(Runtime::class);
        $subject = new PresentationObjectComponentImplementation(
            $runtime->reveal(),
            'test',
            'My.Package:Component'
        );

        $runtime
            ->getCurrentContext()
            ->willReturn([]);
        $runtime
            ->evaluate('test/' . PresentationObjectComponentImplementation::OBJECT_NAME, $subject)
            ->willReturn(new \DateTimeImmutable);
        $runtime
            ->evaluate('test/' . PresentationObjectComponentImplementation::INTERFACE_DECLARATION_NAME, $subject)
            ->willReturn('\I\Do\Not\Exist');

        $this->expectException(ComponentPresentationObjectInterfaceIsMissing::class);

        $subject->evaluate();
    }

    /**
     * @test
     * @return void
     */
    public function evaluateThrowsExceptionWhenNotInPreviewModeAndWithPresentationObjectNotImplementingTheDeclaredInterface(): void
    {
        $this->expectException(ComponentPresentationObjectDoesNotImplementRequiredInterface::class);

        $runtime = $this->prophet->prophesize(Runtime::class);
        $subject = new PresentationObjectComponentImplementation(
            $runtime->reveal(),
            'test',
            'My.Package:Component'
        );

        $runtime
            ->getCurrentContext()
            ->willReturn([]);
        $runtime
            ->evaluate('test/' . PresentationObjectComponentImplementation::OBJECT_NAME, $subject)
            ->willReturn(new \stdClass);
        $runtime
            ->evaluate('test/' . PresentationObjectComponentImplementation::INTERFACE_DECLARATION_NAME, $subject)
            ->willReturn(\DateTimeInterface::class);

        $this->expectException(ComponentPresentationObjectDoesNotImplementRequiredInterface::class);

        $subject->evaluate();
    }

    /**
     * @test
     * @return void
     */
    public function evaluateThrowsExceptionWhenNotInPreviewModeAndWithPresentationObjectNotImplementingTheBaseInterface(): void
    {
        $this->expectException(ComponentPresentationObjectDoesNotImplementRequiredInterface::class);

        $runtime = $this->prophet->prophesize(Runtime::class);
        $subject = new PresentationObjectComponentImplementation(
            $runtime->reveal(),
            'test',
            'My.Package:Component'
        );

        $runtime
            ->getCurrentContext()
            ->willReturn([]);
        $runtime
            ->evaluate('test/' . PresentationObjectComponentImplementation::OBJECT_NAME, $subject)
            ->willReturn(new \DateTimeImmutable);
        $runtime
            ->evaluate('test/' . PresentationObjectComponentImplementation::INTERFACE_DECLARATION_NAME, $subject)
            ->willReturn(\DateTimeInterface::class);

        $this->expectException(ComponentPresentationObjectDoesNotImplementRequiredInterface::class);

        $subject->evaluate();
    }
}
