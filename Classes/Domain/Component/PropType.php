<?php
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use Psr\Http\Message\UriInterface;
use Sitegeist\Kaleidoscope\EelHelpers\ImageSourceHelperInterface;

/**
 * @Flow\Proxy(false)
 */
final class PropType
{
    /**
     * @var array|string[]
     */
    public static $primitives = [
        'string' => 'string',
        'int' => 'int',
        'float' => 'float',
        'bool' => 'bool'
    ];

    public static $globalValues = [
        'ImageSource' => ImageSourceHelperInterface::class,
        'Uri' => UriInterface::class
    ];

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $simpleName;

    /**
     * @var string
     */
    private $fullyQualifiedName;

    /**
     * @var bool
     */
    private $nullable;

    /**
     * @var PropTypeClass
     */
    private $class;

    private function __construct(string $name, string $simpleName, string $fullyQualifiedName, bool $nullable, PropTypeClass $class)
    {
        $this->name = $name;
        $this->simpleName = $simpleName;
        $this->fullyQualifiedName = $fullyQualifiedName;
        $this->nullable = $nullable;
        $this->class = $class;
    }

    public static function create(string $packageKey, string $componentName, string $type, PropTypeRepositoryInterface $propTypeRepository): self
    {
        if (!$identity = $propTypeRepository->findPropTypeIdentifier($packageKey, $componentName, $type)) {
            throw PropTypeIsInvalid::becauseItIsNoKnownComponentValueOrPrimitive($type);
        }

        return new self(
            $identity->getName(),
            $identity->getSimpleName(),
            $identity->getFullyQualifiedName(),
            $identity->isNullable(),
            $identity->getClass()
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSimpleName(): string
    {
        return $this->simpleName;
    }

    public function getFullyQualifiedName(): string
    {
        return $this->fullyQualifiedName;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function getClass(): PropTypeClass
    {
        return $this->class;
    }

    public function toUse(): string
    {
        return $this->fullyQualifiedName;
    }

    public function toType(): string
    {
        return ($this->isNullable() ? '?' : '') . $this->simpleName;
    }

    public function toVar(): string
    {
        return $this->simpleName . ($this->isNullable() ? '|null' : '');
    }

    public function toStyleGuidePropValue(): string
    {
        $styleGuideValue = '';
        if ($this->class->isPrimitive()) {
            switch ($this->name) {
                case 'string':
                    $styleGuideValue = '= \'Text\'';
                    break;
                case 'int':
                    $styleGuideValue = '= 4711';
                    break;
                case 'float':
                    $styleGuideValue = '= 47.11';
                    break;
                case 'bool':
                     $styleGuideValue = '= true';
                    break;
            }
        } elseif ($this->class->isGlobalValue()) {
            switch ($this->name) {
                case 'ImageSourceHelperInterface':
                    $styleGuideValue = '= Sitegeist.Kaleidoscope:DummyImageSource {
                height = 1920
                width = 1080
            }';
                    break;
                case 'UriInterface':
                    $styleGuideValue = '= \'https://neos.io\'';
                    break;
            }
        } elseif ($this->class->isValue()) {
            $styleGuideValue = '= \'\'';
        } elseif ($this->class->isComponent()) {
            $styleGuideValue = '{
            }';
        }

        return $styleGuideValue;
    }
}
