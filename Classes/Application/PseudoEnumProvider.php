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
use PackageFactory\AtomicFusion\PresentationObjects\Domain\Enum\PseudoEnumInterface;

class PseudoEnumProvider extends AbstractDataSource implements ProtectedContextAwareInterface, NodeTypePostprocessorInterface
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
        $this->validateEnumName($arguments['enumName']);
        $options = [];
        foreach ($this->getCases($arguments['enumName']) as $value) {
            $options[$value]['label'] = $this->getLabel($arguments['enumName'], $value);
        }

        return $options;
    }

    public function process(NodeType $nodeType, array &$configuration, array $options)
    {
        if (!isset($options['enumName'])) {
            throw new \InvalidArgumentException('Option "enumName" must be provided.', 1625298032);
        }
        $this->validateEnumName($options['enumName']);
        $cases = $this->getCases($options['enumName']);
        foreach ($options['propertyNames'] as $propertyName) {
            foreach ($cases as $case) {
                $configuration['properties'][$propertyName]['ui']['inspector']['editorOptions']['values'][$case] = [
                    'label' => $this->getLabel($options['enumName'], (string)$case)
                ];
            }
        }
    }

    private function getLabel(string $enumName, string $value): string
    {
        list($packageKey, $componentName) = explode('/Presentation/', $enumName);
        $pivot = \mb_strrpos($componentName, '/');
        $componentNamespace = \mb_substr($packageKey, 0 , $pivot);
        $enumShort = lcfirst(\mb_substr($packageKey, $pivot+1));

        return $this->translator->translateById(
            $enumShort . '.' . $value,
            [],
            null,
            null,
            \str_replace('/', '.', $componentNamespace),
            \str_replace('/', '.', $packageKey)
        ) ?: $value;
    }

    /**
     * @param class-string<mixed> $enumName
     * @return array|string[]|int[]
     */
    public function getCases(string $enumName): array
    {
        return array_map(function (PseudoEnumInterface $case) {
            return $case->getValue();
        }, $enumName::cases());
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
