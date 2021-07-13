<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropTypeClass;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropTypeIdentifier;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropTypeIsInvalid;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropTypeRepositoryInterface;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

/**
 * Test for the PropType value object
 */
final class PropTypeTest extends UnitTestCase
{
    /**
     * @var Prophet
     */
    private $prophet;

    /**
     * @var ObjectProphecy<PropTypeRepositoryInterface>
     */
    private $propTypeRepository;

    /**
     * @before
     * @return void
     */
    public function setUpPropTypeTest(): void
    {
        $this->prophet = new Prophet();

        $this->propTypeRepository = $this->prophet->prophesize(PropTypeRepositoryInterface::class);
        $this->propTypeRepository
            ->findPropTypeIdentifier('Vendor.Site', 'TestComponent', '?string')
            ->willReturn(new PropTypeIdentifier('string', 'string', 'string', true, PropTypeClass::primitive()));
        $this->propTypeRepository
            ->findPropTypeIdentifier('Vendor.Site', 'TestComponent', 'Some\\UnknownClass')
            ->willReturn(null);
    }

    /**
     * @after
     * @return void
     */
    public function tearDownPropTypeTest(): void
    {
        $this->prophet->checkPredictions();
    }

    /**
     * @test
     * @return void
     */
    public function canBeCreatedFromKnownTypeReference(): void
    {
        $propType = PropType::create(
            'Vendor.Site',
            'TestComponent',
            '?string',
            $this->propTypeRepository->reveal()
        );

        $this->assertEquals('string', $propType->getName());
        $this->assertEquals('string', $propType->getSimpleName());
        $this->assertEquals('string', $propType->getFullyQualifiedName());
        $this->assertEquals(true, $propType->isNullable());
        $this->assertEquals(true, $propType->getClass()->isPrimitive());
        $this->assertEquals('string', $propType->toUse());
        $this->assertEquals('?string', $propType->toType());
        $this->assertEquals('string|null', $propType->toVar());
        $this->assertEquals('= \'Text\'', $propType->toStyleGuidePropValue());
    }

    /**
     * @test
     * @return void
     */
    public function cannotBeCreatedFromUnknownTypeReference(): void
    {
        $this->expectException(PropTypeIsInvalid::class);

        PropType::create(
            'Vendor.Site',
            'TestComponent',
            'Some\\UnknownClass',
            $this->propTypeRepository->reveal()
        );
    }
}
