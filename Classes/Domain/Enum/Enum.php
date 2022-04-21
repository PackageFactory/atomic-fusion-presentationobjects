<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum;

use Neos\Flow\Annotations as Flow;

#[Flow\Proxy(false)]
final class Enum
{
    public function __construct(
        public readonly EnumName $name,
        public readonly EnumType $type,
        /** @var array<string>|array<int> */
        public readonly array $cases
    ) {
        if (empty($cases)) {
            throw new \InvalidArgumentException('Enums must have at least one case, none given.', 1626541482);
        }
    }

    public function getClassContent(): string
    {
        return '<?php

/*
 * This file is part of the ' . $this->name->getPackageKey() . ' package.
 */

declare(strict_types=1);

namespace ' . $this->name->getPhpNamespace() . ';

enum ' . $this->name->name . ':' . $this->type->value . '
{
    ' . $this->renderCases() . '

    ' . $this->renderComparators() . '
}
';
    }

    private function renderCases(): string
    {
        $constants = [];
        foreach ($this->cases as $name => $case) {
            $renderedValue = $this->type->isString()
                ? '\'' . $case . '\''
                : $case;
            $constants[] = 'case ' . $this->getConstantName($name) . ' = ' . $renderedValue . ';';
        }

        return trim(implode("\n    ", $constants));
    }

    private function renderComparators(): string
    {
        $comparators = [];
        foreach ($this->cases as $name => $case) {
            $comparators[]  = 'public function getIs' . ucfirst($name) . '(): bool
    {
        return $this === self::' . $this->getConstantName($name) . ';
    }';
        }

        return trim(implode("\n\n    ", $comparators));
    }

    private function getConstantName(string $value): string
    {
        $value = $this->camelCaseToUpperSnakeCase($value);
        $parts = $this->splitName();
        if (count($parts) > 1) {
            return strtoupper(end($parts)) . '_' . $value;
        }

        return 'VALUE_' . $value;
    }

    private function camelCaseToUpperSnakeCase(string $value): string
    {
        return strtoupper(preg_replace('/(?<!^)[A-Z]/', '_$0', $value));
    }

    /**
     * @return array<string>
     */
    private function splitName(): array
    {
        $nameParts = [];
        $parts = preg_split("/([A-Z])/", $this->name->name, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        if (is_array($parts)) {
            foreach ($parts as $i => $part) {
                if ($i % 2 === 0) {
                    $nameParts[$i / 2] = $part;
                } else {
                    $nameParts[($i - 1) / 2] .= $part;
                }
            }
        }

        return $nameParts;
    }
}
