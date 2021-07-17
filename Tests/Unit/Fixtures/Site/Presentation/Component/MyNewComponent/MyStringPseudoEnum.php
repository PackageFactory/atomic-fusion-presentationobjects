<?php declare(strict_types=1);
namespace Vendor\Site\Presentation\Component\MyNewComponent;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

/**
 * Dummy string enum for test purposes
 * @Flow\Proxy(false)
 */
final class MyStringPseudoEnum implements PseudoEnumInterface
{
    const VALUE_MY_VALUE = 'myValue';
    const VALUE_MY_OTHER_VALUE = 'myOtherValue';

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
            if ($string !== self::VALUE_MY_VALUE
                && $string !== self::VALUE_MY_OTHER_VALUE) {
                throw MyStringPseudoEnumIsInvalid::becauseItMustBeOneOfTheDefinedConstants($string);
            }
            self::$instances[$string] = new self($string);
        }

        return self::$instances[$string];
    }

    public static function myValue(): self
    {
        return self::from(self::VALUE_MY_VALUE);
    }

    public static function myOtherValue(): self
    {
        return self::from(self::VALUE_MY_OTHER_VALUE);
    }

    /**
     * @return array<int,self>|self[]
     */
    public static function cases(): array
    {
        return [
            self::from(self::VALUE_MY_VALUE),
            self::from(self::VALUE_MY_OTHER_VALUE)
        ];
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
