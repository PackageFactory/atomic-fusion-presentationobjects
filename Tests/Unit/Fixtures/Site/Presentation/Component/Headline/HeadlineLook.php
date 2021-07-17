<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\Headline;

/*
 * This file is part of the Vendor.Site package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * HeadlineLook enum for test purposes
 * @Flow\Proxy(false)
 */
final class HeadlineLook implements PseudoEnumInterface
{
    const LOOK_LARGE = 'large';

    /**
     * @var array<string,self>|self[]
     */
    private static array $instances = [];

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function from(string $string): self
    {
        if (!isset(self::$instances[$string])) {
            if ($string !== self::LOOK_LARGE) {
                throw HeadlineLookIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function large(): self
    {
        return self::from(self::LOOK_LARGE);
    }

    public static function cases(): array
    {
        return [
            self::from(self::LOOK_LARGE)
        ];
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
