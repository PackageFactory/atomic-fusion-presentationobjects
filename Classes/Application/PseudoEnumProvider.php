<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Application;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package.
 */

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\ContentRepository\Domain\Model\NodeType;
use Neos\ContentRepository\NodeTypePostprocessor\NodeTypePostprocessorInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\I18n\Translator;
use Neos\Neos\Service\DataSource\AbstractDataSource;
use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\EnumLabel;
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

final class PseudoEnumProvider extends AbstractDataSource implements ProtectedContextAwareInterface, NodeTypePostprocessorInterface
{
    /**
     * @Flow\Inject
     * @var Translator
     */
    protected $translator;

    /**
     * @var string
     */
    protected static $identifier = 'packagefactory-atomicfusion-presentationobjects-enumcases';

    public function getData(NodeInterface $node = null, array $arguments = []): array
    {
        if (!isset($arguments['enumName'])) {
            throw new \InvalidArgumentException('Argument "enumName" must be provided.', 1625297174);
        }
        $values = $this->getValues($arguments['enumName']);
        $enumLabel = EnumLabel::fromEnumName($arguments['enumName']);
        $options = [];
        foreach ($values as $value) {
            $options[$value]['label'] = $this->getLabel($enumLabel, (string)$value);
        }

        return $options;
    }

    public function process(NodeType $nodeType, array &$configuration, array $options)
    {
        if (!isset($options['enumName'])) {
            throw new \InvalidArgumentException('Option "enumName" must be provided.', 1625298032);
        }
        $values = $this->getValues($options['enumName']);
        $enumLabel = EnumLabel::fromEnumName($options['enumName']);
        foreach ($options['propertyNames'] as $propertyName) {
            foreach ($values as $value) {
                $configuration['properties'][$propertyName]['ui']['inspector']['editorOptions']['values'][$value] = [
                    'label' => $this->getLabel($enumLabel, (string)$value)
                ];
            }
        }
    }

    private function getLabel(EnumLabel $enumLabel, string $value): string
    {
        return $this->translator->translateById(
            $enumLabel->getLabelIdPrefix() . $value,
            [],
            null,
            null,
            $enumLabel->getSourceName(),
            $enumLabel->getPackageKey()
        ) ?: $value;
    }

    /**
     * @param class-string<mixed> $enumName
     * @return array|string[]|int[]
     */
    public function getValues(string $enumName): array
    {
        return array_map(function (PseudoEnumInterface $case) {
            return $case->getValue();
        }, $this->getCases($enumName));
    }

    /**
     * @param class-string<mixed> $enumName
     * @return array|PseudoEnumInterface[]
     */
    public function getCases(string $enumName): array
    {
        $this->validateEnumName($enumName);

        return $enumName::cases();
    }

    private function validateEnumName(string $enumName): void
    {
        if (!class_exists($enumName)) {
            throw new \InvalidArgumentException('Given enum "' . $enumName . '" does not exist.', 1625297031);
        }
        if (!in_array(PseudoEnumInterface::class, class_implements($enumName))) {
            throw new \InvalidArgumentException('Given enum "' . $enumName . '" does not implement the required ' . PseudoEnumInterface::class, 1625297122);
        }
    }

    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }
}
