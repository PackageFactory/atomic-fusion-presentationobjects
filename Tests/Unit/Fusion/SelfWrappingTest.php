<?php declare(strict_types=1);
namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Fusion;

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Fusion\SelfWrapping;

/**
 * Test for the SelfWrapping Trait
 */
final class SelfWrappingTest extends UnitTestCase
{
    /**
     * @test
     * @small
     * @return void
     */
    public function wrapsStringValueIfWrapperIsSet(): void
    {
        $wrapper = function (string $value) {
            return 'wrapped(' . $value . ')';
        };

        $selfWrappingSubject = new class($wrapper) {
            use SelfWrapping;

            public function __construct(callable $wrapper)
            {
                $this->wrapper = $wrapper;
            }
        };

        $value = 'Lorem ipsum...';

        $this->assertEquals('wrapped(Lorem ipsum...)', $selfWrappingSubject->wrap($value));
    }

    /**
     * @test
     * @small
     * @return void
     */
    public function returnsUnalteredStringValueIfWrapperIsNotSet(): void
    {
        $selfWrappingSubject = new class() {
            use SelfWrapping;
        };

        $value = 'Lorem ipsum...';

        $this->assertEquals('Lorem ipsum...', $selfWrappingSubject->wrap($value));
    }
}
