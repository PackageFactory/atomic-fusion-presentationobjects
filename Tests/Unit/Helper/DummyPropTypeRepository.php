<?php
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Helper;

use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropTypeIdentifier;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropTypeRepository;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropTypeRepositoryInterface;

/**
 * The easily accessible dummy prop type repository for use in unit tests
 */
class DummyPropTypeRepository implements PropTypeRepositoryInterface
{
    /**
     * The known prop type identifiers by
     * * package key
     * * component name
     * * type
     *
     * @var array|PropTypeIdentifier[][][]
     */
    public $propTypeIdentifiers;

    /**
     * @var PropTypeRepository
     */
    private $realPropTypeRepository;

    public function __construct()
    {
        $this->realPropTypeRepository = new PropTypeRepository();
    }

    public function findByType(?string $packageKey, ?string $componentName, string $type): ?PropType
    {
        if (!$this->knowsByType($packageKey, $componentName, $type)) {
            return null;
        }

        return PropType::create($packageKey, $componentName, $type, $this);
    }

    public function findPropTypeIdentifier(string $packageKey, string $componentName, string $type): ?PropTypeIdentifier
    {
        $alternativeComponentName = trim($type, '?');

        return $this->realPropTypeRepository->findPropTypeIdentifier($packageKey, $componentName, $type)
            ?: ($this->propTypeIdentifiers[$packageKey][$componentName][$type] ?? ($this->propTypeIdentifiers[$packageKey][$alternativeComponentName][$type] ?? null));
    }

    public function knowsByType(string $packageKey, string $componentName, string $type): bool
    {
        $alternativeComponentName = trim($type, '?');

        return $this->realPropTypeRepository->knowsByType($packageKey, $componentName, $type)
            || isset($this->propTypeIdentifiers[$packageKey][$componentName][$type])
            || isset($this->propTypeIdentifiers[$packageKey][$alternativeComponentName][$type]);
    }
}
