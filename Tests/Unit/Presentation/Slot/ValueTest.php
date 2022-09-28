<?php

/*
 * This file is part of the PackageFactory.AtomicFusion.PresentationObjects package
 */

declare(strict_types=1);

namespace PackageFactory\AtomicFusion\PresentationObjects\Tests\Unit\Presentation\Slot;

use Neos\Flow\Tests\UnitTestCase;
use PackageFactory\AtomicFusion\PresentationObjects\Presentation\Slot\Value;

final class ValueTest extends UnitTestCase
{
    /**
     * @test
     * @return void
     */
    public function holdsStringValues(): void
    {
        $value = Value::fromString('Some Value');
        $this->assertEquals('Some Value', (string) $value);
        $this->assertInstanceOf(Value::class, $value);
    }

    /**
     * @test
     * @return void
     */
    public function isRenderedAsValueFusionPrototype(): void
    {
        $value = Value::fromString('Some Value');
        $this->assertEquals('PackageFactory.AtomicFusion.PresentationObjects:Value', $value->getPrototypeName());
    }

    /**
     * @return array<mixed>
     */
    public function inputValueProvider(): array
    {
        if ($resource = fopen('/dev/null', 'r')) {
            fclose($resource);
        }

        return [
            'null' =>
                [null, ''],
            'array of strings' =>
                [['Hello', 'World'], 'HelloWorld'],
            'array of integers' =>
                [[1, 2, 3, 4], '1234'],
            'array of floats' =>
                [[3.14,42.23], '3.1442.23'],
            'callable' =>
                [function () {
                }, '[callable]'],
            'object with __toString method' =>
                [new class {
                    public function __toString(): string
                    {
                        return 'Hello Object!';
                    }
                }, 'Hello Object!'],
            'object without __toString method' =>
                [new \stdClass(), '[stdClass]'],
            'resource' =>
                [$resource, '[unknown type: resource (closed)]'],
        ];
    }

    /**
     * @test
     * @dataProvider inputValueProvider
     * @param mixed $inputValue
     * @param string $expectedString
     * @return void
     */
    public function canbeCreatedFromAnyInputValue($inputValue, $expectedString): void
    {
        $value = Value::fromAny($inputValue);

        $this->assertInstanceOf(Value::class, $value);
        $this->assertEquals($expectedString, (string) $value);
    }
}
