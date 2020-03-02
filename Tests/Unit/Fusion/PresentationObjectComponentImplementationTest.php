<?php
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Fusion;

use Neos\Flow\Tests\UnitTestCase;
use Neos\Fusion\Core\Runtime;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectDoesNotImplementRequiredInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterfaceIsMissing;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectInterfaceIsUndeclared;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\ComponentPresentationObjectIsMissing;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\PresentationObjectComponentImplementation;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Test cases for the PresentationObjectComponentImplementation
 */
class PresentationObjectComponentImplementationTest extends UnitTestCase
{
    /**
     * @test
     * @throws \ReflectionException
     */
    public function prepareProperlyMergesPropsToStubbedPresentationObjectInPreviewMode()
    {
        /** @var Runtime|MockObject $mockRuntime */
        $mockRuntime = $this->createMock(Runtime::class);

        $mockRuntime
            ->expects($this->any())
            ->method('evaluate')
            ->with($this->logicalOr(
                $this->equalTo('test/' . PresentationObjectComponentImplementation::PREVIEW_MODE),
                $this->equalTo('test/foo')
            ))
            ->will($this->returnCallback(function ($path) {
                if ($path === 'test/' . PresentationObjectComponentImplementation::PREVIEW_MODE) {
                    return true;
                }
                if ($path === 'test/foo') {
                    return 'bar';
                }
                return null;
            }));

        $subject = new PresentationObjectComponentImplementation($mockRuntime, 'test', 'My.Package:Component');
        $subject['foo'] = 'bar';

        $context = $this->getPrepare()->invokeArgs($subject, [[]]);

        $this->assertSame(['foo' => 'bar'], $context['props']);
        $this->assertSame(['foo' => 'bar'], $context[PresentationObjectComponentImplementation::OBJECT_NAME]);
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function prepareWritesPresentationObjectToContextWhenNotInPreviewMode()
    {
        $mockRuntime = $this->createMock(Runtime::class);

        $mockPresentationObject = $this->createMock(ComponentPresentationObjectInterface::class);
        /** @var Runtime|MockObject $mockRuntime */
        $mockRuntime
            ->expects($this->exactly(3))
            ->method('evaluate')
            ->with($this->logicalOr(
                $this->equalTo('test/' . PresentationObjectComponentImplementation::PREVIEW_MODE),
                $this->equalTo('test/' . PresentationObjectComponentImplementation::OBJECT_NAME),
                $this->equalTo('test/' . PresentationObjectComponentImplementation::INTERFACE_DECLARATION_NAME)
            ))
            ->will($this->returnCallback(function ($path) use($mockPresentationObject) {
                if ($path === 'test/' . PresentationObjectComponentImplementation::PREVIEW_MODE) {
                    return false;
                }
                if ($path === 'test/' . PresentationObjectComponentImplementation::OBJECT_NAME) {
                    return $mockPresentationObject;
                }
                if ($path === 'test/' . PresentationObjectComponentImplementation::INTERFACE_DECLARATION_NAME) {
                    return ComponentPresentationObjectInterface::class;
                }
                return null;
            }));

        $subject = new PresentationObjectComponentImplementation($mockRuntime, 'test', 'My.Package:Component');

        $context = $this->getPrepare()->invokeArgs($subject, [[]]);

        $this->assertSame($mockPresentationObject, $context[PresentationObjectComponentImplementation::OBJECT_NAME]);
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
     */
    public function evaluateThrowsExceptionWhenNotInPreviewModeAndWithoutGivenPresentationObject()
    {
        $mockRuntime = $this->createMock(Runtime::class);

        /** @var Runtime|MockObject $mockRuntime */
        $mockRuntime
            ->expects($this->exactly(2))
            ->method('evaluate')
            ->with($this->logicalOr(
                $this->equalTo('test/' . PresentationObjectComponentImplementation::PREVIEW_MODE),
                $this->equalTo('test/' . PresentationObjectComponentImplementation::OBJECT_NAME)
            ))
            ->will($this->returnCallback(function ($path) {
                if ($path === 'test/' . PresentationObjectComponentImplementation::PREVIEW_MODE) {
                    return false;
                }
                if ($path === 'test/' . PresentationObjectComponentImplementation::OBJECT_NAME) {
                    return null;
                }
                return null;
            }));

        $subject = new PresentationObjectComponentImplementation($mockRuntime, 'test', 'My.Package:Component');

        $this->expectException(ComponentPresentationObjectIsMissing::class);

        $subject->evaluate();
    }

    /**
     * @test
     */
    public function evaluateThrowsExceptionWhenNotInPreviewModeAndWithoutDeclaredPresentationObjectInterface()
    {
        $mockRuntime = $this->createMock(Runtime::class);

        /** @var Runtime|MockObject $mockRuntime */
        $mockRuntime
            ->expects($this->exactly(3))
            ->method('evaluate')
            ->with($this->logicalOr(
                $this->equalTo('test/' . PresentationObjectComponentImplementation::PREVIEW_MODE),
                $this->equalTo('test/' . PresentationObjectComponentImplementation::OBJECT_NAME),
                $this->equalTo('test/' . PresentationObjectComponentImplementation::INTERFACE_DECLARATION_NAME)
            ))
            ->will($this->returnCallback(function ($path) {
                if ($path === 'test/' . PresentationObjectComponentImplementation::PREVIEW_MODE) {
                    return false;
                }
                if ($path === 'test/' . PresentationObjectComponentImplementation::OBJECT_NAME) {
                    return new \DateTimeImmutable();
                }
                if ($path === 'test/' . PresentationObjectComponentImplementation::INTERFACE_DECLARATION_NAME) {
                    return null;
                }
                return null;
            }));

        $subject = new PresentationObjectComponentImplementation($mockRuntime, 'test', 'My.Package:Component');

        $this->expectException(ComponentPresentationObjectInterfaceIsUndeclared::class);

        $subject->evaluate();
    }

    /**
     * @test
     */
    public function evaluateThrowsExceptionWhenNotInPreviewModeAndWithoutExistingPresentationObjectInterface()
    {
        $mockRuntime = $this->createMock(Runtime::class);

        /** @var Runtime|MockObject $mockRuntime */
        $mockRuntime
            ->expects($this->exactly(3))
            ->method('evaluate')
            ->with($this->logicalOr(
                $this->equalTo('test/' . PresentationObjectComponentImplementation::PREVIEW_MODE),
                $this->equalTo('test/' . PresentationObjectComponentImplementation::OBJECT_NAME),
                $this->equalTo('test/' . PresentationObjectComponentImplementation::INTERFACE_DECLARATION_NAME)
            ))
            ->will($this->returnCallback(function ($path) {
                if ($path === 'test/' . PresentationObjectComponentImplementation::PREVIEW_MODE) {
                    return false;
                }
                if ($path === 'test/' . PresentationObjectComponentImplementation::OBJECT_NAME) {
                    return new \DateTimeImmutable();
                }
                if ($path === 'test/' . PresentationObjectComponentImplementation::INTERFACE_DECLARATION_NAME) {
                    return '\I\Do\Not\Exist';
                }
                return null;
            }));

        $subject = new PresentationObjectComponentImplementation($mockRuntime, 'test', 'My.Package:Component');

        $this->expectException(ComponentPresentationObjectInterfaceIsMissing::class);

        $subject->evaluate();
    }

    /**
     * @test
     */
    public function evaluateThrowsExceptionWhenNotInPreviewModeAndWithPresentationObjectNotImplementingTheDeclaredInterface()
    {
        $mockRuntime = $this->createMock(Runtime::class);

        /** @var Runtime|MockObject $mockRuntime */
        $mockRuntime
            ->expects($this->exactly(3))
            ->method('evaluate')
            ->with($this->logicalOr(
                $this->equalTo('test/' . PresentationObjectComponentImplementation::PREVIEW_MODE),
                $this->equalTo('test/' . PresentationObjectComponentImplementation::OBJECT_NAME),
                $this->equalTo('test/' . PresentationObjectComponentImplementation::INTERFACE_DECLARATION_NAME)
            ))
            ->will($this->returnCallback(function ($path) {
                if ($path === 'test/' . PresentationObjectComponentImplementation::PREVIEW_MODE) {
                    return false;
                }
                if ($path === 'test/' . PresentationObjectComponentImplementation::OBJECT_NAME) {
                    return new \stdClass();
                }
                if ($path === 'test/' . PresentationObjectComponentImplementation::INTERFACE_DECLARATION_NAME) {
                    return \DateTimeInterface::class;
                }
                return null;
            }));

        $subject = new PresentationObjectComponentImplementation($mockRuntime, 'test', 'My.Package:Component');

        $this->expectException(ComponentPresentationObjectDoesNotImplementRequiredInterface::class);

        $subject->evaluate();
    }

    /**
     * @test
     */
    public function evaluateThrowsExceptionWhenNotInPreviewModeAndWithPresentationObjectNotImplementingTheBaseInterface()
    {
        $mockRuntime = $this->createMock(Runtime::class);

        /** @var Runtime|MockObject $mockRuntime */
        $mockRuntime
            ->expects($this->exactly(3))
            ->method('evaluate')
            ->with($this->logicalOr(
                $this->equalTo('test/' . PresentationObjectComponentImplementation::PREVIEW_MODE),
                $this->equalTo('test/' . PresentationObjectComponentImplementation::OBJECT_NAME),
                $this->equalTo('test/' . PresentationObjectComponentImplementation::INTERFACE_DECLARATION_NAME)
            ))
            ->will($this->returnCallback(function ($path) {
                if ($path === 'test/' . PresentationObjectComponentImplementation::PREVIEW_MODE) {
                    return false;
                }
                if ($path === 'test/' . PresentationObjectComponentImplementation::OBJECT_NAME) {
                    return new \DateTimeImmutable();
                }
                if ($path === 'test/' . PresentationObjectComponentImplementation::INTERFACE_DECLARATION_NAME) {
                    return \DateTimeInterface::class;
                }
                return null;
            }));

        $subject = new PresentationObjectComponentImplementation($mockRuntime, 'test', 'My.Package:Component');

        $this->expectException(ComponentPresentationObjectDoesNotImplementRequiredInterface::class);

        $subject->evaluate();
    }
}
