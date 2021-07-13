<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Component;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
final class PropTypeIdentifier
{
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

    public function __construct(string $name, string $shortName, string $fullyQualifiedName, bool $nullable, PropTypeClass $class)
    {
        $this->name = $name;
        $this->simpleName = $shortName;
        $this->fullyQualifiedName = $fullyQualifiedName;
        $this->nullable = $nullable;
        $this->class = $class;
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
}
