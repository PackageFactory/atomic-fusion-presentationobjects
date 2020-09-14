<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Application;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Neos\Service\DataSource\AbstractDataSource;
use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\AtomicFusion\PresentationObjects\Framework\Type\Enum;

/**
 * A universal data provider for Enums
 */
class EnumDataProvider extends AbstractDataSource implements ProtectedContextAwareInterface
{
    /**
     * @var string
     */
    protected static $identifier = 'packagefactory-atomicfusion-presentationobjects-enum';

    /**
     * @param NodeInterface $node
     * @param array $arguments
     * @return array
     */
    public function getData(NodeInterface $node = null, array $arguments = []): array
    {
        assert(isset($arguments['enum']), new \InvalidArgumentException('Argument "enum" must be provided for EnumDataProvider.', 1600109378));
        assert(is_subclass_of($arguments['enum'], Enum::class), new \InvalidArgumentException('Argument "enum" (' . $arguments['enum'] . ') does not refer to a class that extends ' . Enum::class . '. .', 1600109379));

        /** @var Enum $enumClass */
        $enumClass = '\\' . $arguments['enum'];
        $enumClass::getAll();

        $result = [];
        foreach ($enumClass::getAll() as $enum) {
            $result[$enum->getValue()]['label'] = $enum->getName();
        }

        return $result;
    }

    /**
     * @param string $methodName
     * @return boolean
     */
    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }
}
