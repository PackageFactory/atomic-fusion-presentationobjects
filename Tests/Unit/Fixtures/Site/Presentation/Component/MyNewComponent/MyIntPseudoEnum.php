<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\MyNewComponent;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * Dummy int enum for test purposes
 * @Flow\Proxy(false)
 */
final class MyIntPseudoEnum implements PseudoEnumInterface
{
    const VALUE_ANSWER = 42;
    const VALUE_OTHER = 8472;

    /**
     * @var array<int,self>|self[]
     */
    private static array $instances = [];

    private int $value;

    private function __construct(int $value)
    {
        $this->value = $value;
    }

    public static function from(int $int): self
    {
        if (!isset(self::$instances[$int])) {
            if ($int !== self::VALUE_ANSWER
                && $int !== self::VALUE_OTHER) {
                throw MyIntPseudoEnumIsInvalid::becauseItMustBeOneOfTheDefinedConstants($int);
            }
            self::$instances[$int] = new self($int);
        }

        return self::$instances[$int];
    }

    public static function answer(): self
    {
        return self::from(self::VALUE_ANSWER);
    }

    public static function other(): self
    {
        return self::from(self::VALUE_OTHER);
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VALUE_ANSWER),
            self::from(self::VALUE_OTHER)
        ];
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
