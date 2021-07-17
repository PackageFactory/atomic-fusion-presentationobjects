<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\Headline;

/*
 * This file is part of the Vendor.Site package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * HeadlineType enum for test purposes
 * @Flow\Proxy(false)
 */
final class HeadlineType implements PseudoEnumInterface
{
    const TYPE_H1 = 'h1';

    private string $value;

    /**
     * @var array<string,self>|self[]
     */
    private static array $instances = [];

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function from(string $string): self
    {
        if (!isset(self::$instances[$string])) {
            if ($string !== self::TYPE_H1) {
                throw HeadlineTypeIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function h1(): self
    {
        return self::from(self::TYPE_H1);
    }

    public static function cases(): array
    {
        return [
            self::from(self::TYPE_H1)
        ];
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
