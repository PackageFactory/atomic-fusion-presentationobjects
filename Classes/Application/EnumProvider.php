<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Application;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Model\NodeType;
use Neos\ContentRepository\NodeTypePostprocessor\NodeTypePostprocessorInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Translator;
use Neos\Neos\Service\DataSource\AbstractDataSource;
use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Component\PropType\IsEnum;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\EnumLabel;

final class EnumProvider extends AbstractDataSource implements ProtectedContextAwareInterface, NodeTypePostprocessorInterface
{
    /**
     * @Flow\Inject(lazy=false)
     * @var Translator
     */
    protected $translator;

    /**
     * @var string
     */
    protected static $identifier = 'packagefactory-atomicfusion-presentationobjects-enumcases';

    /**
     * @param NodeInterface|null $node
     * @param array<string|int,string> $arguments
     * @return array<string|int,array<string,string>>
     */
    public function getData(NodeInterface $node = null, array $arguments = []): array
    {
        if (!array_key_exists('enumName', $arguments) || !is_string($arguments['enumName'])) {
            throw new \InvalidArgumentException('Argument "enumName" must be provided.', 1625297174);
        }
        /** @var class-string<mixed> $enumName */
        $enumName = $arguments['enumName'];

        $values = $this->getValues($enumName);
        $enumLabel = EnumLabel::fromEnumName($enumName);
        $options = [];
        foreach ($values as $value) {
            $options[$value]['label'] = $enumLabel->translate((string)$value, $this->translator);
        }

        return $options;
    }

    /**
     * @param NodeType $nodeType
     * @param array<mixed> $configuration
     * @param array<mixed> $options
     */
    public function process(NodeType $nodeType, array &$configuration, array $options)
    {
        if (!array_key_exists('enumName', $options) || !is_string($options['enumName'])) {
            throw new \InvalidArgumentException('Option "enumName" must be provided.', 1625298032);
        }
        if (!array_key_exists('propertyNames', $options) || !is_array($options['propertyNames'])) {
            throw new \InvalidArgumentException('Option "propertyNames" must be provided.', 1626540931);
        }
        /** @var class-string<mixed> $enumName */
        $enumName = $options['enumName'];
        $values = $this->getValues($enumName);
        $enumLabel = EnumLabel::fromEnumName($enumName);
        foreach ($options['propertyNames'] as $propertyName) {
            foreach ($values as $value) {
                $configuration['properties'][$propertyName]['ui']['inspector']['editorOptions']['values'][$value] = [
                    'label' => $enumLabel->translate((string)$value, $this->translator)
                ];
            }
        }
    }

    /**
     * @param class-string<mixed> $enumName
     * @return array|string[]|int[]
     */
    public function getValues(string $enumName): array
    {
        return array_map(function (\BackedEnum $case) {
            return $case->getValue();
        }, $this->getCases($enumName));
    }

    /**
     * @param class-string<mixed> $enumName
     * @return array<int,\BackedEnum>
     */
    public function getCases(string $enumName): array
    {
        if (!IsEnum::isSatisfiedByClassName($enumName)) {
            throw new \InvalidArgumentException('Given enum "' . $enumName . '" does not exist or does not implement the required ' . \BackedEnum::class, 1625297031);
        }

        return $enumName::cases();
    }

    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }
}
