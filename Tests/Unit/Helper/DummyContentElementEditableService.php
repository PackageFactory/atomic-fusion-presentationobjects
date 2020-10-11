<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Helper;

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Neos\Service\ContentElementEditableService;

/**
 * The easily accessible dummy content element editable service for usage in unit tests, e.g. for presentation object factories
 */
class DummyContentElementEditableService extends ContentElementEditableService
{
    public function wrapContentProperty(NodeInterface $node, $property, $content)
    {
        return $content;
    }
}
