<?php declare(strict_types=1);
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
     * @phpstan-var array<string,string>
     * @var array|string[]
     */
    public static array $primitives = [
        'string' => 'string',
        'int' => 'int',
        'float' => 'float',
        'bool' => 'bool'
    ];

    /**
     * @phpstan-var array<string,class-string>
     * @var array|string[]
     */
    public static $globalValues = [
        'ImageSource' => ImageSourceHelperInterface::class,
        'Uri' => UriInterface::class
    ];

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $simpleName;

    /**
     * @var string
     */
    private string $fullyQualifiedName;

    /**
     * @var bool
     */
    private bool $nullable;

    /**
     * @var PropTypeClass
     */
    private PropTypeClass $class;

    /**
     * @param string $name
     * @param string $simpleName
     * @param string $fullyQualifiedName
     * @param boolean $nullable
     * @param PropTypeClass $class
     */
    public function __construct(string $name, string $simpleName, string $fullyQualifiedName, bool $nullable, PropTypeClass $class)
    {
        $this->name = $name;
        $this->simpleName = $simpleName;
        $this->fullyQualifiedName = $fullyQualifiedName;
        $this->nullable = $nullable;
        $this->class = $class;
    }

    /**
     * @param string $packageKey
     * @param string $componentName
     * @param string $type
     * @param PropTypeRepositoryInterface $propTypeRepository
     * @return self
     */
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

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSimpleName(): string
    {
        return $this->simpleName;
    }

    /**
     * @return string
     */
    public function getFullyQualifiedName(): string
    {
        return $this->fullyQualifiedName;
    }

    /**
     * @return boolean
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * @return PropTypeClass
     */
    public function getClass(): PropTypeClass
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function toUse(): string
    {
        return $this->fullyQualifiedName;
    }

    /**
     * @return string
     */
    public function toType(): string
    {
        return ($this->isNullable() ? '?' : '') . $this->simpleName;
    }

    /**
     * @return string
     */
    public function toVar(): string
    {
        return $this->simpleName . ($this->isNullable() ? '|null' : '');
    }

    /**
     * @return string
     */
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
